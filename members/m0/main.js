/**
 * ============================================================================
 * members/m0/main.js — Interaktivitas JavaScript Bersih (Clean Vanilla JS)
 * ============================================================================
 *
 * 💡 PRINSIP JAVASCRIPT BERSIH:
 * 1. Event 'DOMContentLoaded': Menjamin elemen HTML sudah selesai dimuat sebelum
 *    dimanipulasi, mencegah error null reference.
 * 2. No Global Pollution: Seluruh variabel dan logika berada dalam lingkup fungsi tertutup.
 * 3. Persistence with LocalStorage: Pilihan tema pengunjung diingat bahkan setelah reload.
 * 4. Minimalist & Performant: Tanpa library eksternal (jQuery, React, dll) yang memberatkan.
 */

document.addEventListener('DOMContentLoaded', () => {

    // -------------------------------------------------------------------------
    // INTERAKSI 1: Dark Mode / Light Mode Switcher
    // -------------------------------------------------------------------------
    const themeBtn = document.getElementById('themeToggle');
    const rootHtml = document.documentElement;

    // Periksa apakah pengunjung pernah menyimpan tema sebelumnya di browser
    const currentSavedTheme = localStorage.getItem('m0_theme') || 
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

    // Terapkan tema awal
    applyTheme(currentSavedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const activeTheme = rootHtml.getAttribute('data-theme') || 'light';
            const targetTheme = (activeTheme === 'dark') ? 'light' : 'dark';
            applyTheme(targetTheme);
            localStorage.setItem('m0_theme', targetTheme);
        });
    }

    function applyTheme(theme) {
        rootHtml.setAttribute('data-theme', theme);
        if (themeBtn) {
            themeBtn.textContent = (theme === 'dark') ? '☀️' : '🌙';
            themeBtn.setAttribute('title', (theme === 'dark') ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap');
        }
    }

    // -------------------------------------------------------------------------
    // INTERAKSI 2: Filter Interaktif Kategori Portofolio
    // -------------------------------------------------------------------------
    const filterButtons = document.querySelectorAll('#portfolioFilter .m0-filter-btn');
    const portfolioCards = document.querySelectorAll('#portfolioGrid .m0-portfolio-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // 1. Pindahkan kelas 'active' ke tombol yang baru saja diklik
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // 2. Baca kategori yang dipilih
            const selectedCategory = btn.getAttribute('data-filter');

            // 3. Saring kartu portofolio yang cocok
            portfolioCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.25s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
