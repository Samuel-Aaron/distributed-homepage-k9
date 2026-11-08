<?php
// Integration test: creates and drops its own random database, never kelompok_db.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/app/lib/offline_queue.php';

function visit(string $name, string $route = 'm1'): array
{
    return [
        'event_id' => md5($name),
        'route_id' => $route,
        'target_member' => 'm2',
        'is_failover' => 1,
        'jenis' => 'auto',
        'ip_hash' => hash('sha256', 'test-ip'),
        'user_agent' => 'T08 integration',
        'occurred_at' => 1700000000,
    ];
}

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function connectTestServer(): PDO
{
    $dsn = getenv('T08_TEST_DSN') ?: 'mysql:host=127.0.0.1;port=3306;charset=utf8mb4';
    check(stripos($dsn, 'dbname=') === false, 'Use a server DSN without dbname.');
    return new PDO($dsn, getenv('T08_TEST_USER') ?: 'root', getenv('T08_TEST_PASS') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

if (($argv[1] ?? '') === 'worker') {
    $pdo = null;
    if ($argv[4] === 'online') {
        $pdo = connectTestServer();
        $pdo->exec('USE `' . $argv[5] . '`');
    }
    exit((new OfflineVisitQueue($argv[2]))->record($pdo, visit('worker-' . $argv[3])) ? 0 : 1);
}

$pdo = connectTestServer();
$database = 't08_test_' . bin2hex(random_bytes(6));
$directory = sys_get_temp_dir() . '/t08-' . bin2hex(random_bytes(6));
mkdir($directory);
ini_set('error_log', $directory . '/errors.log');
$path = $directory . '/pending_sync.json';
$queue = new OfflineVisitQueue($path);
$root = dirname(__DIR__);
$processes = [];
try {
    $schema = str_replace('kelompok_db', $database, file_get_contents($root . '/app/sql/schema.sql'));
    $pdo->exec($schema);
    $pdo->exec('ALTER TABLE kunjungan DROP INDEX uq_kunjungan_event, DROP COLUMN event_id');
    $pdo->exec("INSERT INTO kunjungan (route_id,target_member,ip_hash) VALUES ('m0','m0',REPEAT('a',64))");
    $pdo->exec(str_replace('kelompok_db', $database,
        file_get_contents($root . '/app/sql/migrations/008_offline_sync.sql')));
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan WHERE event_id IS NULL')->fetchColumn() === 1,
        'Migration must preserve legacy data.');
    echo "PASS migration preserves existing visits\n";

    $event = visit('offline');
    check($queue->record(null, $event), 'Offline visit must be accepted.');
    check(json_decode(file_get_contents($path), true) === [$event], 'Offline payload must survive unchanged.');
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 1, 'Offline must not write to DB.');
    $pdo->exec("SET time_zone = '-07:00'");
    check($queue->record($pdo, visit('recovery')), 'Recovery must accept current visit.');
    $saved = $pdo->query("SELECT route_id,target_member,is_failover,ip_hash,UNIX_TIMESTAMP(created_at) AS occurred_at
        FROM kunjungan WHERE event_id = '" . $event['event_id'] . "'")->fetch(PDO::FETCH_ASSOC);
    check($saved['route_id'] === 'm1' && $saved['target_member'] === 'm2' && (int)$saved['is_failover'] === 1
        && $saved['ip_hash'] === $event['ip_hash'] && (int)$saved['occurred_at'] === $event['occurred_at'],
        'Sync must preserve origin, failover, IP hash and occurrence time across time zones.');
    check(json_decode(file_get_contents($path), true) === [], 'Committed queue must drain.');
    echo "PASS offline -> recovery preserves original metadata/time\n";

    // Model process death after COMMIT but before clearing the queue.
    file_put_contents($path, json_encode([$event]));
    check($queue->record($pdo, visit('replay')), 'Replay must succeed.');
    check((int)$pdo->query("SELECT COUNT(*) FROM kunjungan WHERE event_id = '" . $event['event_id'] . "'")
        ->fetchColumn() === 1, 'Retry must not duplicate a committed event.');
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 4, 'Replay row count.');
    echo "PASS uncertain commit replay is idempotent\n";

    $batch = [];
    for ($i = 0; $i < 205; $i++) {
        $batch[] = visit('bulk-' . $i);
    }
    file_put_contents($path, json_encode($batch));
    check($queue->record($pdo, visit('bulk-current')), 'Multi-batch sync must succeed.');
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 210, 'All bulk rows must be inserted.');
    check(json_decode(file_get_contents($path), true) === [], 'Bulk queue must drain.');
    echo "PASS bulk sync spans multiple 100-row batches\n";

    $batch = [];
    for ($i = 0; $i < 100; $i++) {
        $batch[] = visit('rollback-' . $i);
    }
    $batch[] = visit('bad-route', 'missing');
    file_put_contents($path, json_encode($batch));
    check($queue->record($pdo, visit('during-failure')), 'DB rejection must retain accepted visit.');
    check(count(json_decode(file_get_contents($path), true)) === 102, 'Failed batch must preserve all pending rows.');
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 210,
        'A later batch failure must roll back earlier batches.');
    // The administrator repairs the missing FK prerequisite, not the queue.
    $pdo->exec("INSERT INTO anggota(route_id,nim,nama) VALUES ('missing','-','Test prerequisite')");
    $queue->record($pdo, visit('after-repair'));
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 313, 'Recovery after constraint repair.');
    check(json_decode(file_get_contents($path), true) === [], 'Recovered batch must drain.');
    echo "PASS transaction failure retains queue and rolls back whole sync\n";

    $pdo->exec('ALTER TABLE kunjungan DROP INDEX uq_kunjungan_event, DROP COLUMN event_id');
    $queue->record($pdo, visit('before-migration'));
    check(count(json_decode(file_get_contents($path), true)) === 1, 'Missing migration must retain event.');
    $pdo->exec(str_replace('kelompok_db', $database,
        file_get_contents($root . '/app/sql/migrations/008_offline_sync.sql')));
    $queue->record($pdo, visit('after-migration'));
    check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === 315, 'Pending data must recover after migration.');
    echo "PASS missing migration buffers visits until schema is ready\n";

    foreach (['{broken', '{}', '[{"event_id":"broken"}]'] as $corrupt) {
        file_put_contents($path, $corrupt);
        check(!$queue->record(null, visit('corrupt')), 'Corrupt queue must fail closed.');
        check(file_get_contents($path) === $corrupt, 'Corrupt queue must not be erased.');
    }
    file_put_contents($path, '[]');
    echo "PASS malformed queue is preserved, not reset\n";

    $pdo->beginTransaction();
    $queue->record($pdo, visit('external-transaction'));
    check($pdo->inTransaction(), 'Caller transaction must remain open.');
    check(count(json_decode(file_get_contents($path), true)) === 1, 'Uncommitted caller transaction cannot clear queue.');
    $pdo->rollBack();
    $queue->record($pdo, visit('after-external-transaction'));
    echo "PASS caller transaction cannot acknowledge the queue\n";

    foreach (['offline', 'online'] as $mode) {
        $beforeCount = (int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn();
        for ($i = 0; $i < 12; $i++) {
            $pipes = [];
            $process = proc_open([PHP_BINARY, __FILE__, 'worker', $path, $mode . '-' . $i, $mode, $database],
                [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            check(is_resource($process), 'Worker must start.');
            fclose($pipes[0]);
            $processes[] = [$process, $pipes];
        }
        foreach ($processes as [$process, $pipes]) {
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            check(proc_close($process) === 0, 'Concurrent worker failed: ' . $stdout . $stderr);
        }
        $processes = [];
        if ($mode === 'offline') {
            $pending = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            check(count($pending) === 12 && count(array_unique(array_column($pending, 'event_id'))) === 12,
                'Concurrent offline writes must not overwrite each other.');
        }
        $queue->record($pdo, visit('drain-' . $mode));
        check((int)$pdo->query('SELECT COUNT(*) FROM kunjungan')->fetchColumn() === $beforeCount + 13,
            'Concurrent visits must each appear exactly once.');
        check(json_decode(file_get_contents($path), true) === [], 'Concurrent sync must drain queue.');
        echo "PASS 12 concurrent {$mode} processes: no loss/duplicates\n";
    }
} finally {
    foreach ($processes as [$process, $pipes]) {
        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
        if (is_resource($process)) {
            proc_terminate($process);
            proc_close($process);
        }
    }
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $pdo->exec('DROP DATABASE IF EXISTS `' . $database . '`');
    foreach (glob($directory . '/*') as $file) {
        unlink($file);
    }
    rmdir($directory);
}
