(() => {
    'use strict';

    const root = document.documentElement;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Gate: reveal styles only hide content when JS is actually running.
    root.classList.add('js-enabled');
    window.requestAnimationFrame(() => root.classList.add('is-loaded'));

    /* ---------------------------------------------------------------- Theme */
    const themeButton = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    function applyTheme(theme) {
        const chosen = theme === 'light' ? 'light' : 'dark';
        root.dataset.theme = chosen;
        if (themeIcon) themeIcon.textContent = chosen === 'dark' ? '☼' : '☾';
        if (themeButton) {
            themeButton.setAttribute('aria-label', chosen === 'dark' ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
        }
    }

    let savedTheme = 'dark';
    try { savedTheme = localStorage.getItem('personal-theme') || 'dark'; } catch (_) {}
    applyTheme(savedTheme);

    themeButton?.addEventListener('click', () => {
        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        try { localStorage.setItem('personal-theme', next); } catch (_) {}
    });

    /* ------------------------------------------------------------ Mobile nav */
    const menuButton = document.getElementById('menu-toggle');
    const nav = document.getElementById('primary-nav');

    function setMenu(open) {
        if (!nav || !menuButton) return;
        nav.classList.toggle('is-open', open);
        menuButton.setAttribute('aria-expanded', String(open));
        menuButton.setAttribute('aria-label', open ? 'Tutup navigasi' : 'Buka navigasi');
    }

    menuButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        setMenu(!nav.classList.contains('is-open'));
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setMenu(false);
    });

    document.addEventListener('click', (event) => {
        if (!nav?.classList.contains('is-open')) return;
        if (nav.contains(event.target) || menuButton?.contains(event.target)) return;
        setMenu(false);
    });

    /* ------------------------------------------------------- Scroll progress */
    const progressRoot = root;
    const header = document.querySelector('.header');
    const timelines = [...document.querySelectorAll('[data-timeline]')];
    const toTop = document.getElementById('to-top');
    const toTopFill = document.getElementById('to-top-fill');
    const RING_LENGTH = 2 * Math.PI * 18;
    let frameQueued = false;

    function paintTimelines() {
        const vh = window.innerHeight;
        timelines.forEach((el) => {
            const rect = el.getBoundingClientRect();
            const travel = rect.height + vh * 0.6;
            const done = vh * 0.85 - rect.top;
            const ratio = travel <= 0 ? 1 : Math.min(1, Math.max(0, done / travel));
            el.style.setProperty('--timeline-progress', (ratio * 100).toFixed(2) + '%');
        });
    }

    function paintScroll() {
        frameQueued = false;
        const doc = document.documentElement;
        const max = Math.max(1, doc.scrollHeight - window.innerHeight);
        const ratio = Math.min(1, Math.max(0, window.scrollY / max));

        progressRoot.style.setProperty('--scroll-progress', (ratio * 100).toFixed(2) + '%');
        if (header) header.classList.toggle('is-scrolled', window.scrollY > 16);

        if (toTop) toTop.classList.toggle('is-visible', window.scrollY > 560);
        if (toTopFill) toTopFill.style.strokeDashoffset = String(RING_LENGTH * (1 - ratio));

        paintTimelines();
    }

    function queuePaint() {
        if (frameQueued) return;
        frameQueued = true;
        window.requestAnimationFrame(paintScroll);
        // Throttled frames (background tab, embedded preview) can starve rAF and
        // leave the flag stuck, so guarantee the paint still happens.
        window.setTimeout(() => { if (frameQueued) paintScroll(); }, 100);
    }

    window.addEventListener('scroll', queuePaint, { passive: true });
    window.addEventListener('resize', queuePaint, { passive: true });
    paintScroll();

    /* ------------------------------------------------------------ Active nav */
    const navLinks = [...document.querySelectorAll('#primary-nav a[href^="#"]')];
    const sectionPairs = navLinks
        .map((link) => {
            const id = link.getAttribute('href').slice(1);
            const section = id ? document.getElementById(id) : null;
            return section ? { link, section } : null;
        })
        .filter(Boolean);

    function markActive(activeLink) {
        navLinks.forEach((link) => {
            const on = link === activeLink;
            link.classList.toggle('is-active', on);
            if (on) link.setAttribute('aria-current', 'true');
            else link.removeAttribute('aria-current');
        });
    }

    if (sectionPairs.length && 'IntersectionObserver' in window) {
        const visible = new Set();
        const navObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) visible.add(entry.target);
                else visible.delete(entry.target);
            });
            const first = sectionPairs.find((pair) => visible.has(pair.section));
            markActive(first ? first.link : null);
        }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });

        sectionPairs.forEach((pair) => navObserver.observe(pair.section));
    }

    /* ---------------------------------------------------------- Scroll reveal */
    const revealTargets = [...document.querySelectorAll('[data-reveal]')];

    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealTargets.forEach((el) => el.classList.add('is-visible'));
    } else {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

        revealTargets.forEach((el) => revealObserver.observe(el));
    }

    /* --------------------------------------------------------- Stat counters */
    const counters = [...document.querySelectorAll('[data-count]')];

    function finalValue(el) {
        const to = Number.parseInt(el.dataset.countTo || '0', 10);
        const pad = Number.parseInt(el.dataset.countPad || '0', 10);
        const safe = Number.isFinite(to) ? to : 0;
        return pad > 0 ? String(safe).padStart(pad, '0') : String(safe);
    }

    function runCounter(el) {
        const to = Number.parseInt(el.dataset.countTo || '0', 10);
        const pad = Number.parseInt(el.dataset.countPad || '0', 10);
        const suffix = el.dataset.countSuffix || '';
        const target = Number.isFinite(to) ? to : 0;

        if (reduceMotion || target === 0) {
            el.textContent = finalValue(el) + suffix;
            return;
        }

        const duration = 1100;
        const start = performance.now();

        function tick(now) {
            const elapsed = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - elapsed, 3);
            const value = Math.round(target * eased);
            el.textContent = (pad > 0 ? String(value).padStart(pad, '0') : String(value)) + suffix;
            if (elapsed < 1) window.requestAnimationFrame(tick);
            else el.textContent = finalValue(el) + suffix;
        }

        window.requestAnimationFrame(tick);
        // Safety net: rAF is throttled in background tabs, so guarantee the final value.
        window.setTimeout(() => { el.textContent = finalValue(el) + suffix; }, duration + 150);
    }

    if (counters.length) {
        if (reduceMotion || !('IntersectionObserver' in window)) {
            counters.forEach(runCounter);
        } else {
            const countObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    runCounter(entry.target);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.4 });

            counters.forEach((el) => countObserver.observe(el));
        }
    }

    /* --------------------------------------------------- Portfolio filtering */
    const filterButtons = [...document.querySelectorAll('.filter-btn')];
    const cards = [...document.querySelectorAll('.project-card')];
    const announcement = document.getElementById('filter-announcement');

    filterButtons.forEach((button) => button.addEventListener('click', () => {
        const selected = button.dataset.filter;
        let visible = 0;

        filterButtons.forEach((item) => {
            const isSelected = item === button;
            item.classList.toggle('is-active', isSelected);
            item.setAttribute('aria-pressed', String(isSelected));
        });

        cards.forEach((card) => {
            const matches = selected === 'all' || card.dataset.category === selected;
            card.hidden = !matches;
            if (!matches) return;
            visible += 1;
            if (!card.classList.contains('is-visible')) return;
            card.classList.remove('is-entering');
            void card.offsetWidth;
            card.classList.add('is-entering');
        });

        if (announcement) {
            announcement.textContent = `${visible} proyek ditampilkan untuk kategori ${button.textContent.trim()}.`;
        }
    }));

    /* ------------------------------------------------------- Copy e-mail */
    const copyButton = document.getElementById('copy-email');
    const feedback = document.getElementById('copy-feedback');

    copyButton?.addEventListener('click', async () => {
        const email = copyButton.dataset.email || '';
        try {
            if (!navigator.clipboard?.writeText) throw new Error('Clipboard tidak tersedia');
            await navigator.clipboard.writeText(email);
            if (feedback) feedback.textContent = 'Alamat email berhasil disalin.';
        } catch (_) {
            if (feedback) feedback.textContent = `Salin alamat ini secara manual: ${email}`;
        }
    });

    /* ----------------------------------------------------- Back to top */
    toTop?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
})();
