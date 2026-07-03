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
            const noGameText = (window.currentLang === 'id') ? 'Game tidak ditemukan' : 'No games found';
            searchSuggest.innerHTML = `
                <div style="padding: 15px; text-align: center; color: var(--text-muted); font-size: 13px;">
                    ${noGameText}
                </div>
            `;
        } else {
            let html = '';
            matches.forEach(game => {
                const initials = game.name.split(' ').map(n => n[0]).join('');
                const topupLink = window.baseUrl + 'user/topup/game.php?slug=' + game.slug;
                
                // Map game slug to asset image filename
                const gameImages = {
                    'mobile-legends': 'MLBB.png',
                    'free-fire': 'FREEFIRE.png',
                    'pubg-mobile': 'PUBG.png',
                    'genshin-impact': 'Genshin Impact.jpg'
                };
                
                let bannerContent = `<div class="suggest-banner">${initials}</div>`;
                if (gameImages[game.slug]) {
                    const imgUrl = window.baseUrl + 'assets/images/' + gameImages[game.slug];
                    bannerContent = `<div class="suggest-banner" style="background: transparent; padding: 0; overflow: hidden; border: 1px solid var(--card-border);">
                        <img src="${imgUrl}" alt="${game.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    </div>`;
                }

                html += `
                    <a href="${topupLink}" class="suggest-item">
                        ${bannerContent}
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
