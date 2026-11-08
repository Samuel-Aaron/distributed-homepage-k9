<?php

final class OfflineVisitQueue
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /** True means persisted locally; a failed sync leaves the queue intact. */
    public function record(?PDO $pdo, array $visit): bool
    {
        $lock = null;
        try {
            // Lock a separate file: replacing the JSON must not replace its lock.
            $lock = fopen($this->path . '.lock', 'c');
            if ($lock === false || !flock($lock, LOCK_EX)) {
                throw new RuntimeException('Tidak dapat mengunci antrean kunjungan.');
            }

            $pending = $this->read();
            $pending[] = $visit;
            // Write ahead of the database, including online requests. An uncertain
            // COMMIT can then be retried safely using the same event_id.
            $this->write($pending);

            if ($pdo !== null) {
                try {
                    $this->flush($pdo, $pending);
                    $this->write([]);
                } catch (Throwable $e) {
                    error_log('[Offline Sync] Antrean dipertahankan: ' . $e->getMessage());
                }
            }
            return true;
        } catch (Throwable $e) {
            error_log('[Offline Sync] Gagal menyimpan kunjungan: ' . $e->getMessage());
            return false;
        } finally {
            if (is_resource($lock)) {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    private function read(): array
    {
        if (!is_file($this->path)) {
            return [];
        }
        $json = file_get_contents($this->path);
        if ($json === false) {
            throw new RuntimeException('Antrean tidak dapat dibaca.');
        }
        if (substr(ltrim($json), 0, 1) !== '[') {
            throw new RuntimeException('Format antrean bukan daftar JSON.');
        }
        $pending = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        // Never overwrite an invalid queue with an empty one.
        if (!is_array($pending) || array_values($pending) !== $pending) {
            throw new RuntimeException('Format antrean bukan daftar kunjungan.');
        }
        foreach ($pending as $visit) {
            if (!is_array($visit)
                || !isset($visit['event_id'], $visit['route_id'], $visit['target_member'],
                    $visit['is_failover'], $visit['jenis'], $visit['ip_hash'],
                    $visit['user_agent'], $visit['occurred_at'])
                || !is_string($visit['event_id'])
                || preg_match('/^[a-f0-9]{32}$/D', $visit['event_id']) !== 1
                || !is_int($visit['occurred_at'])) {
                throw new RuntimeException('Data antrean kunjungan tidak valid.');
            }
        }
        return $pending;
    }

    private function write(array $pending): void
    {
        $json = json_encode($pending, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        $temporary = tempnam(dirname($this->path), 'pending-');
        if ($temporary === false) {
            throw new RuntimeException('Tidak dapat membuat berkas antrean sementara.');
        }
        $handle = null;
        try {
            $handle = fopen($temporary, 'wb');
            if ($handle === false) {
                throw new RuntimeException('Tidak dapat menulis antrean sementara.');
            }
            $length = strlen($json);
            $offset = 0;
            while ($offset < $length) {
                $written = fwrite($handle, substr($json, $offset));
                if ($written === false || $written === 0) {
                    throw new RuntimeException('Penulisan antrean tidak lengkap.');
                }
                $offset += $written;
            }
            if (!fflush($handle) || (function_exists('fsync') && !fsync($handle))) {
                throw new RuntimeException('Gagal melakukan flush antrean.');
            }
            fclose($handle);
            $handle = null;
            if (!rename($temporary, $this->path)) {
                throw new RuntimeException('Gagal mengganti berkas antrean.');
            }
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
            if (is_file($temporary)) {
                unlink($temporary);
            }
        }
    }

    private function flush(PDO $pdo, array $pending): void
    {
        // A caller-owned transaction cannot acknowledge a durable queue safely.
        if ($pdo->inTransaction()) {
            throw new RuntimeException('Sinkronisasi memerlukan transaksi sendiri.');
        }
        $pdo->beginTransaction();
        try {
            foreach (array_chunk($pending, 100) as $batch) {
                $rows = [];
                $values = [];
                foreach ($batch as $visit) {
                    $rows[] = '(?, ?, ?, ?, ?, ?, ?, FROM_UNIXTIME(?))';
                    array_push($values, $visit['event_id'], $visit['route_id'],
                        $visit['target_member'], $visit['is_failover'], $visit['jenis'],
                        $visit['ip_hash'], $visit['user_agent'], $visit['occurred_at']);
                }
                // Unlike INSERT IGNORE, real schema/constraint errors still fail.
                $stmt = $pdo->prepare(
                    'INSERT INTO kunjungan (event_id, route_id, target_member, is_failover,
                        jenis, ip_hash, user_agent, created_at) VALUES ' . implode(', ', $rows) .
                    ' ON DUPLICATE KEY UPDATE event_id = VALUES(event_id)'
                );
                $stmt->execute($values);
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
