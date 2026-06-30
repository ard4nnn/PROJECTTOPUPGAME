</main>

<footer class="footer">
    <div class="footer-glow-bar"></div>
    <div class="footer-grid">
        <!-- Brand Column -->
        <div class="footer-brand-col">
            <a href="<?php echo $base_url; ?>" class="logo-wrapper">
                <svg class="lightning-svg" viewBox="0 0 24 30" width="22" height="28">
                    <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" class="lightning-path" />
                </svg>
                <span class="logo-text">FUN<span>topup</span></span>
            </a>
            <p class="footer-desc">
                <?php echo $current_lang === 'id' ? 'FUNtopup menyediakan pengisian saldo voucher game paling murah, instan, dan aman 24 jam non-stop.' : 'FUNtopup provides the cheapest, instant, and safe game voucher top-ups 24 hours non-stop.'; ?>
            </p>
            <p class="footer-copy">
                &copy; <?php echo date("Y"); ?> FUNtopup. <?php echo $current_lang === 'id' ? 'Semua Hak Cipta Dilindungi.' : 'All Rights Reserved.'; ?>
            </p>
        </div>

        <!-- Links Column -->
        <div class="footer-links-grid">
            <!-- Layanan -->
            <div class="footer-links-col">
                <h3><?php echo $current_lang === 'id' ? 'Layanan' : 'Services'; ?></h3>
                <ul>
                    <li><a href="<?php echo $base_url; ?>"><?php echo __('topup'); ?></a></li>
                    <li><a href="<?php echo $base_url; ?>cek-transaksi.php"><?php echo __('cek_transaksi'); ?></a></li>
                    <li><a href="<?php echo $base_url; ?>leaderboard.php"><?php echo __('leaderboard'); ?></a></li>
                    <li><a href="<?php echo $base_url; ?>kalkulator.php"><?php echo __('kalkulator'); ?></a></li>
                </ul>
            </div>
            <!-- Perusahaan -->
            <div class="footer-links-col">
                <h3><?php echo $current_lang === 'id' ? 'Perusahaan' : 'Company'; ?></h3>
                <ul>
                    <li><a href="#about"><?php echo $current_lang === 'id' ? 'Tentang Kami' : 'About Us'; ?></a></li>
                    <li><a href="#privacy"><?php echo $current_lang === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy'; ?></a></li>
                    <li><a href="#terms"><?php echo $current_lang === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions'; ?></a></li>
                    <li><a href="#faqs"><?php echo $current_lang === 'id' ? 'Bantuan & FAQ' : 'Help & FAQ'; ?></a></li>
                </ul>
            </div>
            <!-- Ikuti Kami -->
            <div class="footer-links-col">
                <h3><?php echo $current_lang === 'id' ? 'Ikuti Kami' : 'Follow Us'; ?></h3>
                <ul class="social-list">
                    <li>
                        <a href="#" class="social-link">
                            <svg class="social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            Facebook
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <svg class="social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 6px;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line></svg>
                            Instagram
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <svg class="social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"></polygon></svg>
                            Youtube
                        </a>
                    </li>
                    <li>
                        <a href="#" class="social-link">
                            <svg class="social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path></svg>
                            WhatsApp
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script>
    window.currentLang = '<?php echo $current_lang; ?>';
    window.baseUrl = '<?php echo $base_url; ?>';
</script>
<script src="<?php echo $base_url; ?>assets/js/language.js"></script>
<script src="<?php echo $base_url; ?>assets/js/search.js"></script>
</body>
</html>
