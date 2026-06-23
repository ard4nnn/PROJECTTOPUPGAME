</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            FUN<span>topup</span>
        </div>
        <p class="footer-copyright">&copy; <?php echo date("Y"); ?> FUNtopup. <?php echo $current_lang === 'id' ? 'Semua Hak Cipta Dilindungi.' : 'All Rights Reserved.'; ?></p>
        <p class="footer-description">
            <?php echo $current_lang === 'id' ? 'FUNtopup menyediakan pengisian saldo voucher game paling murah, instan, dan aman 24 jam non-stop.' : 'FUNtopup provides the cheapest, instant, and safe game voucher top-ups 24 hours non-stop.'; ?>
        </p>
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
