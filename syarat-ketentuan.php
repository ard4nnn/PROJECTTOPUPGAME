<?php
require_once 'includes/init.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan – FUNtopup</title>
    <meta name="description" content="Syarat dan Ketentuan FUNtopup – Aturan dan regulasi penggunaan layanan topup voucher game di website FUNtopup.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        /* ── Terms Page Specific Styles (reuses privacy-* pattern) ── */
        .terms-hero {
            background: linear-gradient(135deg, #0b0e11 0%, #131820 50%, #0d1117 100%);
            border-bottom: 1px solid var(--card-border);
            padding: 64px 24px 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .terms-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 300px;
            background: radial-gradient(ellipse at center, rgba(252, 213, 53, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .terms-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(252, 213, 53, 0.1);
            border: 1px solid rgba(252, 213, 53, 0.25);
            color: var(--primary-color);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .terms-hero-badge svg {
            width: 14px;
            height: 14px;
        }

        .terms-hero h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .terms-hero h1 span {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .terms-hero-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .terms-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .terms-hero-meta-item svg {
            width: 15px;
            height: 15px;
            color: var(--primary-color);
        }

        /* ── Layout ── */
        .terms-layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 24px 80px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 40px;
            align-items: start;
        }

        /* ── Sticky Table of Contents ── */
        .terms-toc {
            position: sticky;
            top: 80px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 22px;
        }

        .terms-toc-title {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--card-border);
        }

        .terms-toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .terms-toc-list a {
            display: block;
            padding: 8px 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            transition: color 0.2s, background 0.2s;
            line-height: 1.4;
        }

        .terms-toc-list a:hover,
        .terms-toc-list a.active {
            color: var(--primary-color);
            background: rgba(252, 213, 53, 0.08);
        }

        /* ── Content ── */
        .terms-content {
            min-width: 0;
        }

        .terms-intro-card {
            background: linear-gradient(135deg, rgba(252,213,53,0.06), rgba(45,189,182,0.04));
            border: 1px solid rgba(252, 213, 53, 0.15);
            border-radius: var(--radius-xl);
            padding: 24px 28px;
            margin-bottom: 36px;
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .terms-intro-card strong {
            color: var(--primary-color);
        }

        .terms-section {
            margin-bottom: 44px;
            scroll-margin-top: 90px;
        }

        .terms-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--card-border);
        }

        .terms-section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(252, 213, 53, 0.1);
            border: 1px solid rgba(252, 213, 53, 0.2);
            border-radius: var(--radius-md);
            flex-shrink: 0;
            color: var(--primary-color);
        }

        .terms-section-icon svg {
            width: 18px;
            height: 18px;
        }

        .terms-section h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        .terms-section p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .terms-section p:last-child {
            margin-bottom: 0;
        }

        .terms-list {
            list-style: none;
            padding: 0;
            margin: 12px 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .terms-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .terms-list li::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            min-width: 6px;
            background: var(--primary-color);
            border-radius: 50%;
            margin-top: 7px;
        }

        /* ── Prohibition cards grid ── */
        .prohibit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .prohibit-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 18px;
            transition: border-color 0.25s, transform 0.2s, box-shadow 0.25s;
        }

        .prohibit-card:hover {
            border-color: rgba(246, 70, 93, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .prohibit-card-icon {
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .prohibit-card-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--danger-color);
            margin-bottom: 6px;
        }

        .prohibit-card-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.55;
        }

        /* ── Transaction rules grid ── */
        .transaction-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .transaction-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            transition: border-color 0.25s, transform 0.2s, box-shadow 0.25s;
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .transaction-card:hover {
            border-color: rgba(252, 213, 53, 0.35);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .transaction-card-num {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            min-width: 28px;
            background: rgba(252, 213, 53, 0.12);
            border: 1px solid rgba(252, 213, 53, 0.25);
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .transaction-card-body {
            flex: 1;
        }

        .transaction-card-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .transaction-card-desc {
            font-size: 0.82rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ── Warning note ── */
        .terms-note {
            background: rgba(246, 70, 93, 0.07);
            border-left: 3px solid var(--danger-color);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            padding: 14px 18px;
            margin-top: 12px;
        }

        .terms-note p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.87rem;
            line-height: 1.6;
        }

        .terms-note p strong {
            color: #f6465d;
        }

        /* ── Info banner ── */
        .terms-info-banner {
            background: linear-gradient(135deg, rgba(14, 203, 129, 0.08), rgba(14, 203, 129, 0.04));
            border: 1px solid rgba(14, 203, 129, 0.2);
            border-radius: var(--radius-xl);
            padding: 20px 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-top: 14px;
        }

        .terms-info-banner-icon {
            color: var(--success-color);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .terms-info-banner-icon svg {
            width: 22px;
            height: 22px;
        }

        .terms-info-banner p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.87rem;
            line-height: 1.65;
        }

        .terms-info-banner p strong {
            color: var(--success-color);
        }

        /* ── Amber/warning banner ── */
        .terms-warn-banner {
            background: linear-gradient(135deg, rgba(252, 213, 53, 0.06), rgba(252, 213, 53, 0.02));
            border: 1px solid rgba(252, 213, 53, 0.18);
            border-radius: var(--radius-xl);
            padding: 20px 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-top: 14px;
        }

        .terms-warn-banner-icon {
            color: var(--primary-color);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .terms-warn-banner-icon svg {
            width: 22px;
            height: 22px;
        }

        .terms-warn-banner p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.87rem;
            line-height: 1.65;
        }

        .terms-warn-banner p strong {
            color: var(--primary-color);
        }

        /* ── Last Updated Footer ── */
        .terms-footer-note {
            margin-top: 48px;
            padding-top: 28px;
            border-top: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .terms-footer-note p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.82rem;
        }

        .terms-footer-note strong {
            color: var(--text-color);
        }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .terms-layout {
                grid-template-columns: 1fr;
                gap: 28px;
                padding: 32px 16px 60px;
            }

            .terms-toc {
                position: static;
            }

            .terms-toc-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 4px;
            }

            .prohibit-grid {
                grid-template-columns: 1fr 1fr;
            }

            .transaction-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .terms-hero {
                padding: 44px 16px 36px;
            }

            .prohibit-grid {
                grid-template-columns: 1fr;
            }

            .terms-toc-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>


<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main-content">

    <!-- Hero Section -->
    <section class="terms-hero">
        <div class="terms-hero-badge">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Syarat & Ketentuan
        </div>
        <h1>Syarat & <span>Ketentuan</span></h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 520px; margin: 0 auto; line-height: 1.65;">
            Aturan dan regulasi yang berlaku untuk penggunaan website FUNtopup. Dengan mengakses situs ini, Anda dianggap telah menyetujui seluruh ketentuan berikut.
        </p>
        <div class="terms-hero-meta">
            <span class="terms-hero-meta-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Terakhir diperbarui: 2026
            </span>
            <span class="terms-hero-meta-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                Berlaku untuk: www.funtopup.com
            </span>
        </div>
    </section>

    <!-- Main Layout -->
    <div class="terms-layout">

        <!-- Table of Contents (Sticky Sidebar) -->
        <aside class="terms-toc">
            <div class="terms-toc-title">Daftar Isi</div>
            <ul class="terms-toc-list" id="toc-list">
                <li><a href="#cookies" class="toc-link">Cookies</a></li>
                <li><a href="#lisensi" class="toc-link">Lisensi</a></li>
                <li><a href="#ulasan" class="toc-link">Ulasan & Komentar</a></li>
                <li><a href="#transaksi" class="toc-link">Ketentuan Transaksi</a></li>
                <li><a href="#hyperlink" class="toc-link">Hyperlink</a></li>
                <li><a href="#iframes" class="toc-link">iFrames</a></li>
                <li><a href="#tanggung-jawab" class="toc-link">Tanggung Jawab Konten</a></li>
                <li><a href="#privasi" class="toc-link">Privasi Anda</a></li>
                <li><a href="#perubahan" class="toc-link">Hak Perubahan</a></li>
                <li><a href="#penghapusan" class="toc-link">Penghapusan Tautan</a></li>
                <li><a href="#disclaimer" class="toc-link">Penyangkalan</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <div class="terms-content">

            <!-- Intro Card -->
            <div class="terms-intro-card">
                Selamat datang di <strong>FUNtopup</strong>! Syarat dan ketentuan ini menguraikan aturan dan regulasi untuk penggunaan Website FUNtopup, yang beralamat di <strong>https://www.funtopup.com</strong>.
                <br><br>
                Dengan mengakses situs web ini, kami menganggap Anda menerima syarat dan ketentuan ini. Jangan melanjutkan penggunaan FUNtopup jika Anda tidak menyetujui seluruh syarat dan ketentuan yang tercantum di halaman ini.
                <br><br>
                Terminologi berikut berlaku untuk Syarat dan Ketentuan ini: <em>"Klien"</em>, <em>"Anda"</em>, dan <em>"Milik Anda"</em> mengacu pada Anda, orang yang mengakses situs web ini. <em>"Perusahaan"</em>, <em>"Kami"</em>, <em>"Milik Kami"</em>, dan <em>"Kita"</em> mengacu pada FUNtopup. Semua istilah mengacu pada penawaran, penerimaan, dan pertimbangan pembayaran yang diperlukan untuk memenuhi kebutuhan Klien sehubungan dengan penyediaan layanan topup voucher game, sesuai dengan hukum yang berlaku di <strong>Republik Indonesia</strong>.
            </div>

            <!-- Section: Cookies -->
            <section class="terms-section" id="cookies">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <h2>Cookies</h2>
                </div>
                <p>Kami menggunakan cookies. Dengan mengakses FUNtopup, Anda setuju untuk menggunakan cookies sesuai dengan Kebijakan Privasi FUNtopup.</p>
                <p>Sebagian besar situs web interaktif menggunakan cookies untuk memungkinkan kami mengambil detail pengguna pada setiap kunjungan. Cookies digunakan oleh situs kami untuk mengaktifkan fungsi area tertentu agar memudahkan pengunjung situs kami, seperti fitur <strong>Cek Transaksi</strong> dan <strong>Kalkulator</strong>. Beberapa mitra afiliasi/periklanan kami juga dapat menggunakan cookies.</p>
            </section>

            <!-- Section: Lisensi -->
            <section class="terms-section" id="lisensi">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h2>Lisensi</h2>
                </div>
                <p>Kecuali dinyatakan lain, FUNtopup dan/atau pemberi lisensinya memiliki hak kekayaan intelektual atas seluruh materi di FUNtopup, termasuk namun tidak terbatas pada logo, desain antarmuka, kalkulator harga, dan konten tertulis di situs. Semua hak kekayaan intelektual dilindungi. Anda dapat mengakses situs ini untuk penggunaan pribadi Anda sendiri, tunduk pada batasan yang ditetapkan dalam syarat dan ketentuan ini.</p>
                <p>Anda <strong>tidak boleh</strong>:</p>
                <div class="prohibit-grid">
                    <div class="prohibit-card">
                        <div class="prohibit-card-icon">🚫</div>
                        <div class="prohibit-card-title">Mempublikasikan Ulang</div>
                        <div class="prohibit-card-desc">Mempublikasikan ulang materi dari FUNtopup ke media atau platform lain tanpa izin.</div>
                    </div>
                    <div class="prohibit-card">
                        <div class="prohibit-card-icon">💰</div>
                        <div class="prohibit-card-title">Menjual / Menyewakan</div>
                        <div class="prohibit-card-desc">Menjual, menyewakan, atau mensublisensikan materi dari FUNtopup.</div>
                    </div>
                    <div class="prohibit-card">
                        <div class="prohibit-card-icon">📋</div>
                        <div class="prohibit-card-title">Menduplikasi / Menyalin</div>
                        <div class="prohibit-card-desc">Memperbanyak, menduplikasi, atau menyalin materi dari FUNtopup.</div>
                    </div>
                    <div class="prohibit-card">
                        <div class="prohibit-card-icon">📤</div>
                        <div class="prohibit-card-title">Mendistribusikan Ulang</div>
                        <div class="prohibit-card-desc">Mendistribusikan ulang konten dari FUNtopup tanpa persetujuan tertulis.</div>
                    </div>
                </div>
                <p style="margin-top: 16px;">Perjanjian ini mulai berlaku sejak tanggal Anda pertama kali mengakses situs.</p>
            </section>

            <!-- Section: Ulasan & Komentar -->
            <section class="terms-section" id="ulasan">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h2>Ulasan dan Komentar Pengguna</h2>
                </div>
                <p>Sebagian dari situs ini memberikan kesempatan bagi pengguna untuk memposting ulasan, komentar, atau berbagi pengalaman terkait layanan topup kami (misalnya pada fitur Leaderboard atau media sosial resmi kami). FUNtopup tidak menyaring, mengedit, mempublikasikan, atau meninjau komentar tersebut sebelum tayang di situs. Komentar tidak mencerminkan pandangan dan pendapat FUNtopup, agennya, dan/atau afiliasinya.</p>
                <p>FUNtopup berhak memantau seluruh komentar dan menghapus komentar apa pun yang dianggap tidak pantas, menyinggung, atau melanggar Syarat dan Ketentuan ini.</p>
                <p>Dengan memposting komentar, Anda menjamin bahwa:</p>
                <ul class="terms-list">
                    <li>Anda berhak memposting komentar tersebut dan memiliki seluruh lisensi serta izin yang diperlukan</li>
                    <li>Komentar tidak melanggar hak kekayaan intelektual pihak ketiga mana pun</li>
                    <li>Komentar tidak mengandung materi yang bersifat fitnah, mencemarkan nama baik, menyinggung, tidak senonoh, atau melanggar hukum lainnya yang merupakan pelanggaran privasi</li>
                    <li>Komentar tidak akan digunakan untuk menyolisitasi atau mempromosikan bisnis, atau menyajikan aktivitas komersial atau aktivitas ilegal</li>
                </ul>
                <div class="terms-info-banner">
                    <div class="terms-info-banner-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p>Dengan memposting komentar, Anda memberikan lisensi <strong>non-eksklusif</strong> kepada FUNtopup untuk menggunakan, memperbanyak, mengedit, dan mengizinkan pihak lain menggunakan komentar Anda dalam segala bentuk, format, atau media.</p>
                </div>
            </section>

            <!-- Section: Ketentuan Transaksi Topup -->
            <section class="terms-section" id="transaksi">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h2>Ketentuan Transaksi Topup</h2>
                </div>
                <p>Karena FUNtopup bergerak di bidang layanan topup voucher/saldo game, ketentuan tambahan berikut berlaku untuk setiap transaksi:</p>
                <div class="transaction-grid">
                    <div class="transaction-card">
                        <div class="transaction-card-num">1</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Kebenaran Data</div>
                            <div class="transaction-card-desc">Anda bertanggung jawab penuh atas kebenaran dan ketepatan data yang dimasukkan saat melakukan transaksi, termasuk User ID, Server ID, nomor akun, atau data identitas game lainnya.</div>
                        </div>
                    </div>
                    <div class="transaction-card">
                        <div class="transaction-card-num">2</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Kesalahan Input</div>
                            <div class="transaction-card-desc">FUNtopup tidak bertanggung jawab atas kesalahan pengiriman produk yang diakibatkan oleh kesalahan input data dari pengguna. Produk yang terkirim ke akun yang tercantum dianggap transaksi sah dan selesai.</div>
                        </div>
                    </div>
                    <div class="transaction-card">
                        <div class="transaction-card-num">3</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Non-Refundable</div>
                            <div class="transaction-card-desc">Produk digital (voucher, saldo, item virtual) yang telah berhasil diproses <strong>tidak dapat dibatalkan atau dikembalikan</strong>, kecuali terbukti terjadi kesalahan sistem dari pihak FUNtopup.</div>
                        </div>
                    </div>
                    <div class="transaction-card">
                        <div class="transaction-card-num">4</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Estimasi Waktu</div>
                            <div class="transaction-card-desc">Estimasi waktu pemrosesan bersifat estimasi dan dapat berubah sewaktu-waktu tergantung pada ketersediaan sistem penyedia (provider) game terkait.</div>
                        </div>
                    </div>
                    <div class="transaction-card">
                        <div class="transaction-card-num">5</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Pencegahan Fraud</div>
                            <div class="transaction-card-desc">FUNtopup berhak menolak atau membatalkan transaksi yang terindikasi kecurangan (fraud), penyalahgunaan sistem, atau pelanggaran ketentuan penyedia layanan.</div>
                        </div>
                    </div>
                    <div class="transaction-card">
                        <div class="transaction-card-num">6</div>
                        <div class="transaction-card-body">
                            <div class="transaction-card-title">Perubahan Harga</div>
                            <div class="transaction-card-desc">Harga yang tertera di situs (termasuk hasil dari fitur Kalkulator) dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</div>
                        </div>
                    </div>
                </div>
                <div class="terms-note" style="margin-top: 18px;">
                    <p><strong>Penting:</strong> Pastikan seluruh data yang Anda masukkan sudah benar sebelum menyelesaikan pembayaran. Transaksi yang telah berhasil diproses bersifat final dan tidak dapat dikembalikan.</p>
                </div>
            </section>

            <!-- Section: Hyperlink -->
            <section class="terms-section" id="hyperlink">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h2>Hyperlink ke Konten Kami</h2>
                </div>
                <p>Organisasi berikut dapat memberikan tautan ke Situs Web kami tanpa persetujuan tertulis sebelumnya:</p>
                <ul class="terms-list">
                    <li>Lembaga pemerintah</li>
                    <li>Mesin pencari</li>
                    <li>Organisasi berita</li>
                    <li>Distributor direktori online</li>
                </ul>
                <p>Organisasi-organisasi ini dapat memberikan tautan ke halaman utama kami selama tautan tersebut: (a) tidak menipu dengan cara apa pun; (b) tidak secara keliru menyiratkan sponsor, dukungan, atau persetujuan dari pihak yang memberi tautan; dan (c) sesuai dengan konteks situs pihak yang memberi tautan.</p>
                <p>Jika Anda merupakan salah satu organisasi yang tercantum di atas dan tertarik untuk memberikan tautan ke situs web kami, Anda harus memberi tahu kami dengan mengirimkan email kepada FUNtopup. Harap sertakan nama Anda, nama organisasi, informasi kontak, URL situs Anda, daftar URL yang bermaksud Anda gunakan untuk menautkan ke Situs Web kami, dan daftar URL di situs kami yang ingin Anda tautkan. Tunggu 2-3 minggu untuk mendapatkan balasan.</p>
                <div class="terms-warn-banner">
                    <div class="terms-warn-banner-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p>Tidak diperkenankan menggunakan <strong>logo atau karya seni FUNtopup</strong> lainnya untuk keperluan tautan tanpa adanya perjanjian lisensi merek dagang.</p>
                </div>
            </section>

            <!-- Section: iFrames -->
            <section class="terms-section" id="iframes">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <h2>iFrames</h2>
                </div>
                <p>Tanpa persetujuan dan izin tertulis sebelumnya, Anda tidak boleh membuat frame di sekitar Halaman Web kami yang mengubah dengan cara apa pun presentasi visual atau tampilan Situs Web kami.</p>
            </section>

            <!-- Section: Tanggung Jawab Konten -->
            <section class="terms-section" id="tanggung-jawab">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2>Tanggung Jawab Konten</h2>
                </div>
                <p>Kami tidak bertanggung jawab atas konten apa pun yang muncul di Situs Web Anda. Anda setuju untuk melindungi dan membela kami dari semua klaim yang timbul dari Situs Web Anda. Tidak boleh ada tautan yang muncul di Situs Web mana pun yang dapat ditafsirkan sebagai bersifat fitnah, tidak senonoh, atau kriminal, atau yang melanggar hak pihak ketiga mana pun.</p>
            </section>

            <!-- Section: Privasi Anda -->
            <section class="terms-section" id="privasi">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h2>Privasi Anda</h2>
                </div>
                <p>Silakan baca <strong>Kebijakan Privasi</strong> kami untuk informasi lebih lanjut tentang bagaimana kami mengumpulkan dan menggunakan data Anda.</p>
                <div class="terms-info-banner">
                    <div class="terms-info-banner-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <p>Informasi selengkapnya mengenai bagaimana kami melindungi data Anda dapat ditemukan di halaman <a href="<?php echo $base_url; ?>kebijakan-privasi.php" style="color: var(--success-color); text-decoration: underline;"><strong>Kebijakan Privasi</strong></a> kami.</p>
                </div>
            </section>

            <!-- Section: Hak untuk Melakukan Perubahan -->
            <section class="terms-section" id="perubahan">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h2>Hak untuk Melakukan Perubahan</h2>
                </div>
                <p>Kami berhak meminta Anda untuk menghapus semua tautan atau tautan tertentu ke Situs Web kami. Anda setuju untuk segera menghapus semua tautan ke Situs Web kami atas permintaan kami.</p>
                <p>Kami juga berhak mengubah syarat dan ketentuan ini serta kebijakan tautannya kapan saja. Dengan terus menautkan ke Situs Web kami, Anda setuju untuk terikat dan mengikuti syarat dan ketentuan tautan ini.</p>
            </section>

            <!-- Section: Penghapusan Tautan -->
            <section class="terms-section" id="penghapusan">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h2>Penghapusan Tautan dari Situs Web Kami</h2>
                </div>
                <p>Jika Anda menemukan tautan apa pun di Situs Web kami yang dianggap menyinggung karena alasan apa pun, Anda bebas menghubungi dan memberi tahu kami kapan saja. Kami akan mempertimbangkan permintaan untuk menghapus tautan tersebut, namun kami tidak berkewajiban untuk melakukannya atau merespons Anda secara langsung.</p>
                <p>Kami tidak menjamin bahwa informasi di situs web ini benar, kami tidak menjamin kelengkapan atau keakuratannya; kami juga tidak berjanji untuk memastikan bahwa situs web tetap tersedia atau bahwa materi di situs web selalu diperbarui.</p>
            </section>

            <!-- Section: Penyangkalan (Disclaimer) -->
            <section class="terms-section" id="disclaimer">
                <div class="terms-section-header">
                    <div class="terms-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h2>Penyangkalan (Disclaimer)</h2>
                </div>
                <p>Sepanjang diizinkan oleh hukum yang berlaku, kami mengecualikan semua pernyataan, jaminan, dan ketentuan yang berkaitan dengan situs web kami dan penggunaan situs web ini. Tidak ada satu pun dalam penyangkalan ini yang akan:</p>
                <ul class="terms-list">
                    <li>Membatasi atau mengecualikan tanggung jawab kami atau Anda atas kematian atau cedera pribadi</li>
                    <li>Membatasi atau mengecualikan tanggung jawab kami atau Anda atas penipuan atau kesalahan penyajian yang bersifat menipu</li>
                    <li>Membatasi tanggung jawab kami atau Anda dengan cara apa pun yang tidak diizinkan berdasarkan hukum yang berlaku</li>
                    <li>Mengecualikan tanggung jawab kami atau Anda yang tidak dapat dikecualikan berdasarkan hukum yang berlaku</li>
                </ul>
                <div class="terms-note">
                    <p><strong>Perhatian:</strong> Selama situs web serta informasi dan layanan di dalamnya disediakan secara gratis, kami tidak akan bertanggung jawab atas kerugian atau kerusakan dalam bentuk apa pun, kecuali untuk hal-hal yang secara tegas diatur dalam ketentuan transaksi topup di atas.</p>
                </div>
            </section>

            <!-- Footer Note -->
            <div class="terms-footer-note">
                <p>Syarat & Ketentuan ini terakhir diperbarui pada <strong>2026</strong>.</p>
                <p>© <?php echo date("Y"); ?> FUNtopup. Semua Hak Cipta Dilindungi.</p>
            </div>

        </div><!-- /.terms-content -->
    </div><!-- /.terms-layout -->

<?php require_once 'includes/footer.php'; ?>

<script>
    // Smooth scrolling for TOC links
    document.querySelectorAll('.toc-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Active TOC link on scroll
    const sections = document.querySelectorAll('.terms-section');
    const tocLinks = document.querySelectorAll('.toc-link');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                tocLinks.forEach(link => link.classList.remove('active'));
                const activeLink = document.querySelector(`.toc-link[href="#${entry.target.id}"]`);
                if (activeLink) activeLink.classList.add('active');
            }
        });
    }, { rootMargin: '-20% 0px -70% 0px' });

    sections.forEach(section => observer.observe(section));
</script>
