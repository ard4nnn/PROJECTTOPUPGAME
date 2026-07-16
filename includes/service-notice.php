<?php
/**
 * service-notice.php
 * Reusable partial to display service disruption notice in a clean, consistent amber/yellow warning style.
 */
?>
<div class="service-notice-container" style="background: rgba(251, 191, 36, 0.05); border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 12px; padding: 32px 24px; text-align: center; margin: 20px auto; max-width: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
    <div style="font-size: 3rem; margin-bottom: 16px; color: #fbbf24; line-height: 1;">⚠️</div>
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #ffffff; margin: 0 0 10px 0; font-family: 'Outfit', sans-serif;">
        <?php echo $current_lang === 'id' ? 'Layanan Tidak Tersedia' : 'Service Unavailable'; ?>
    </h3>
    <p style="font-size: 0.9rem; color: #a1a1aa; margin: 0; line-height: 1.6; font-family: 'Outfit', sans-serif;">
        <?php echo isset($notice_message) ? $notice_message : __('layanan_gangguan'); ?>
    </p>
</div>
