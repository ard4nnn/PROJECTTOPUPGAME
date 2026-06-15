</main>

<footer style="background-color: var(--nav-bg); color: var(--nav-text); border-top: 1px solid var(--card-border); padding: 30px 20px; text-align: center; font-size: 14px; margin-top: 60px;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 10px; align-items: center;">
        <div style="font-weight: 800; font-size: 20px; letter-spacing: -0.5px;">
            FUN<span style="color: var(--primary-color);">topup</span>
        </div>
        <p style="margin: 0; opacity: 0.7;">&copy; <?php echo date("Y"); ?> FUNtopup. Semua Hak Cipta Dilindungi.</p>
        <p style="margin: 0; font-size: 12px; opacity: 0.5;">Didesain khusus untuk kemudahan dan kecepatan top up voucher game favorit Anda.</p>
    </div>
</footer>

<script>
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(savedTheme);

    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    });

    function setTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        if (theme === 'dark') {
            themeIcon.textContent = '🌙';
            themeText.textContent = 'Dark';
        } else {
            themeIcon.textContent = '☀️';
            themeText.textContent = 'Light';
        }
    }
</script>
</body>
</html>
