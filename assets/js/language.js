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
