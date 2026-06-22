</main>

<footer style="background-color: var(--card-bg); color: var(--text-color); border-top: 1px solid var(--card-border); padding: 40px 24px; text-align: center; font-size: 14px; margin-top: 60px; box-shadow: var(--shadow-sm);">
    <div style="max-width: 1300px; margin: 0 auto; display: flex; flex-direction: column; gap: 15px; align-items: center;">
        <div style="font-weight: 900; font-size: 24px; letter-spacing: -1px; font-style: italic; color: #fcd535; text-transform: uppercase;">
            FUN<span style="color: var(--text-color); font-weight: 500; font-style: normal; text-transform: lowercase;">topup</span>
        </div>
        <p style="margin: 0; opacity: 0.8; font-weight: 500;">&copy; <?php echo date("Y"); ?> FUNtopup. <?php echo $current_lang === 'id' ? 'Semua Hak Cipta Dilindungi.' : 'All Rights Reserved.'; ?></p>
        <p style="margin: 0; font-size: 12.5px; color: var(--text-muted); max-width: 500px; line-height: 1.6;">
            <?php echo $current_lang === 'id' ? 'FUNtopup menyediakan pengisian saldo voucher game paling murah, instan, dan aman 24 jam non-stop.' : 'FUNtopup provides the cheapest, instant, and safe game voucher top-ups 24 hours non-stop.'; ?>
        </p>
    </div>
</footer>

<script>
    // Theme Switcher Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }

    function setTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        if (themeIcon) {
            themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
        }
    }

    // Language Dropdown Logic
    const langToggle = document.getElementById('lang-toggle');
    const langMenu = document.getElementById('lang-menu');

    if (langToggle && langMenu) {
        langToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            langMenu.style.display = langMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', () => {
            langMenu.style.display = 'none';
        });
    }

    function changeLanguage(lang) {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);
        window.location.href = url.toString();
    }

    // Header Search Suggest Logic
    const gamesList = [
        { name: 'Mobile Legends', slug: 'mobile-legends', desc: 'Top up Diamond instant' },
        { name: 'Free Fire', slug: 'free-fire', desc: 'Top up Diamond cheap' },
        { name: 'PUBG Mobile', slug: 'pubg-mobile', desc: 'Top up UC global' },
        { name: 'Genshin Impact', slug: 'genshin-impact', desc: 'Top up Genesis Crystals' }
    ];

    const headerSearch = document.getElementById('header-search');
    const searchSuggest = document.getElementById('search-suggest');

    if (headerSearch && searchSuggest) {
        headerSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            if (query.length === 0) {
                searchSuggest.style.display = 'none';
                return;
            }

            const matches = gamesList.filter(game => game.name.toLowerCase().includes(query));
            if (matches.length === 0) {
                searchSuggest.innerHTML = `
                    <div style="padding: 15px; text-align: center; color: var(--text-muted); font-size: 13px;">
                        ${'<?php echo $current_lang; ?>' === 'id' ? 'Game tidak ditemukan' : 'No games found'}
                    </div>
                `;
            } else {
                let html = '';
                matches.forEach(game => {
                    const initials = game.name.split(' ').map(n => n[0]).join('');
                    const topupLink = '<?php echo $base_url; ?>user/topup/game.php?slug=' + game.slug;
                    html += `
                        <a href="${topupLink}" class="suggest-item">
                            <div class="suggest-banner">${initials}</div>
                            <div class="suggest-info">
                                <span class="suggest-name">${game.name}</span>
                                <span class="suggest-desc">${game.desc}</span>
                            </div>
                        </a>
                    `;
                });
                searchSuggest.innerHTML = html;
            }
            searchSuggest.style.display = 'block';
        });

        // Close suggest box on click outside
        document.addEventListener('click', function(e) {
            if (!headerSearch.contains(e.target) && !searchSuggest.contains(e.target)) {
                searchSuggest.style.display = 'none';
            }
        });

        // Also sync search with main index search if on homepage
        headerSearch.addEventListener('keyup', function() {
            const indexSearch = document.getElementById('search-input');
            if (indexSearch) {
                indexSearch.value = this.value;
                indexSearch.dispatchEvent(new Event('input'));
            }
        });
    }
</script>
</body>
</html>
