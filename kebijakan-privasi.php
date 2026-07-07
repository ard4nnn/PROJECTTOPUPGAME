<?php
require_once 'includes/init.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi – FUNtopup</title>
    <meta name="description" content="Kebijakan Privasi FUNtopup – Pelajari bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan topup voucher game kami.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        /* ── Privacy Page Specific Styles ── */
        .privacy-hero {
            background: linear-gradient(135deg, #0b0e11 0%, #131820 50%, #0d1117 100%);
            border-bottom: 1px solid var(--card-border);
            padding: 64px 24px 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .privacy-hero::before {
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

        .privacy-hero-badge {
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

        .privacy-hero-badge svg {
            width: 14px;
            height: 14px;
        }

        .privacy-hero h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .privacy-hero h1 span {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .privacy-hero-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .privacy-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .privacy-hero-meta-item svg {
            width: 15px;
            height: 15px;
            color: var(--primary-color);
        }

        /* ── Layout ── */
        .privacy-layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 24px 80px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 40px;
            align-items: start;
        }

        /* ── Sticky Table of Contents ── */
        .privacy-toc {
            position: sticky;
            top: 80px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 22px;
        }

        .privacy-toc-title {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--card-border);
        }

        .privacy-toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .privacy-toc-list a {
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

        .privacy-toc-list a:hover,
        .privacy-toc-list a.active {
            color: var(--primary-color);
            background: rgba(252, 213, 53, 0.08);
        }

        /* ── Content ── */
        .privacy-content {
            min-width: 0;
        }

        .privacy-intro-card {
            background: linear-gradient(135deg, rgba(252,213,53,0.06), rgba(45,189,182,0.04));
            border: 1px solid rgba(252, 213, 53, 0.15);
            border-radius: var(--radius-xl);
            padding: 24px 28px;
            margin-bottom: 36px;
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .privacy-intro-card strong {
            color: var(--primary-color);
        }

        .privacy-section {
            margin-bottom: 44px;
            scroll-margin-top: 90px;
        }

        .privacy-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--card-border);
        }

        .privacy-section-icon {
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

        .privacy-section-icon svg {
            width: 18px;
            height: 18px;
        }

        .privacy-section h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        .privacy-section p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .privacy-section p:last-child {
            margin-bottom: 0;
        }

        .privacy-list {
            list-style: none;
            padding: 0;
            margin: 12px 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .privacy-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .privacy-list li::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            min-width: 6px;
            background: var(--primary-color);
            border-radius: 50%;
            margin-top: 7px;
        }

        /* ── Rights Grid ── */
        .rights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .right-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 18px;
            transition: border-color 0.25s, transform 0.2s, box-shadow 0.25s;
        }

        .right-card:hover {
            border-color: rgba(252, 213, 53, 0.35);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .right-card-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 6px;
        }

        .right-card-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.55;
        }

        /* ── Security banner ── */
        .security-banner {
            background: linear-gradient(135deg, rgba(14, 203, 129, 0.08), rgba(14, 203, 129, 0.04));
            border: 1px solid rgba(14, 203, 129, 0.2);
            border-radius: var(--radius-xl);
            padding: 20px 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-top: 14px;
        }

        .security-banner-icon {
            color: var(--success-color);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .security-banner-icon svg {
            width: 22px;
            height: 22px;
        }

        .security-banner p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.87rem;
            line-height: 1.65;
        }

        .security-banner p strong {
            color: var(--success-color);
        }

        /* ── Warning note ── */
        .privacy-note {
            background: rgba(246, 70, 93, 0.07);
            border-left: 3px solid var(--danger-color);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            padding: 14px 18px;
            margin-top: 12px;
        }

        .privacy-note p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.87rem;
            line-height: 1.6;
        }

        .privacy-note p strong {
            color: #f6465d;
        }

        /* ── Last Updated Footer ── */
        .privacy-footer-note {
            margin-top: 48px;
            padding-top: 28px;
            border-top: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .privacy-footer-note p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.82rem;
        }

        .privacy-footer-note strong {
            color: var(--text-color);
        }

        /* ── Responsive ── */
        @media (max-width: 860px) {
            .privacy-layout {
                grid-template-columns: 1fr;
                gap: 28px;
                padding: 32px 16px 60px;
            }

            .privacy-toc {
                position: static;
            }

            .privacy-toc-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 4px;
            }

            .rights-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .privacy-hero {
                padding: 44px 16px 36px;
            }

            .rights-grid {
                grid-template-columns: 1fr;
            }

            .privacy-toc-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>


<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<main class="main-content">

    <!-- Hero Section -->
    <section class="privacy-hero">
        <div class="privacy-hero-badge">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Privasi & Keamanan
        </div>
        <h1>Kebijakan <span>Privasi</span></h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 520px; margin: 0 auto; line-height: 1.65;">
            Kami berkomitmen untuk melindungi dan menghormati privasi Anda. Dokumen ini menjelaskan bagaimana FUNtopup mengelola informasi Anda.
        </p>
        <div class="privacy-hero-meta">
            <span class="privacy-hero-meta-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Terakhir diperbarui: 2026
            </span>
            <span class="privacy-hero-meta-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                Berlaku untuk: www.funtopup.com
            </span>
        </div>
    </section>

    <!-- Main Layout -->
    <div class="privacy-layout">

        <!-- Table of Contents (Sticky Sidebar) -->
        <aside class="privacy-toc">
            <div class="privacy-toc-title">Daftar Isi</div>
            <ul class="privacy-toc-list" id="toc-list">
                <li><a href="#persetujuan" class="toc-link">Persetujuan</a></li>
                <li><a href="#informasi-dikumpulkan" class="toc-link">Informasi yang Dikumpulkan</a></li>
                <li><a href="#penggunaan-informasi" class="toc-link">Penggunaan Informasi</a></li>
                <li><a href="#file-log" class="toc-link">File Log</a></li>
                <li><a href="#cookies" class="toc-link">Cookies</a></li>
                <li><a href="#mitra-periklanan" class="toc-link">Mitra Periklanan</a></li>
                <li><a href="#pihak-ketiga" class="toc-link">Pihak Ketiga</a></li>
                <li><a href="#keamanan" class="toc-link">Keamanan Data</a></li>
                <li><a href="#hak-privasi" class="toc-link">Hak Privasi</a></li>
                <li><a href="#anak-anak" class="toc-link">Perlindungan Anak</a></li>
                <li><a href="#perubahan" class="toc-link">Perubahan Kebijakan</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <div class="privacy-content">

            <!-- Intro Card -->
            <div class="privacy-intro-card">
                Di <strong>FUNtopup</strong>, yang dapat diakses dari <strong>https://www.funtopup.com</strong>, salah satu prioritas utama kami adalah privasi para pengunjung dan pelanggan. Dokumen ini berisi informasi mengenai jenis data yang kami kumpulkan serta cara penggunaannya.
                <br><br>
                Jika Anda memiliki pertanyaan tambahan, jangan ragu untuk menghubungi kami. Kebijakan ini hanya berlaku untuk aktivitas online kami dan tidak mencakup informasi yang dikumpulkan secara offline.
            </div>

            <!-- Section: Persetujuan -->
            <section class="privacy-section" id="persetujuan">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2>Persetujuan</h2>
                </div>
                <p>Dengan menggunakan situs kami, Anda dengan ini menyetujui Kebijakan Privasi kami dan setuju dengan syarat-syaratnya.</p>
            </section>

            <!-- Section: Informasi yang Dikumpulkan -->
            <section class="privacy-section" id="informasi-dikumpulkan">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2>Informasi yang Kami Kumpulkan</h2>
                </div>
                <p>Informasi pribadi yang diminta dari Anda, dan alasan mengapa Anda diminta memberikannya, akan dijelaskan dengan jelas pada saat kami meminta informasi tersebut. Untuk layanan topup voucher game, informasi ini umumnya meliputi:</p>
                <ul class="privacy-list">
                    <li>Nama dan informasi kontak (email, nomor WhatsApp/telepon)</li>
                    <li>ID akun game / User ID dan Server ID yang Anda masukkan untuk proses topup</li>
                    <li>Riwayat dan detail transaksi (produk yang dibeli, nominal, metode pembayaran, waktu transaksi)</li>
                    <li>Informasi yang Anda berikan saat menghubungi layanan pelanggan kami</li>
                </ul>
                <p>Jika Anda menghubungi kami secara langsung, kami mungkin menerima informasi tambahan seperti nama, alamat email, nomor telepon, isi pesan dan/atau lampiran yang Anda kirimkan.</p>
                <p>Jika Anda mendaftar untuk sebuah Akun di FUNtopup, kami dapat meminta informasi kontak Anda, termasuk nama, alamat email, dan nomor telepon.</p>
            </section>

            <!-- Section: Bagaimana Kami Menggunakan Informasi -->
            <section class="privacy-section" id="penggunaan-informasi">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h2>Bagaimana Kami Menggunakan Informasi Anda</h2>
                </div>
                <p>Kami menggunakan informasi yang kami kumpulkan dengan berbagai cara, termasuk untuk:</p>
                <ul class="privacy-list">
                    <li>Memproses dan menyelesaikan transaksi topup voucher game Anda</li>
                    <li>Mengoperasikan, memelihara, dan meningkatkan situs FUNtopup</li>
                    <li>Mempersonalisasi dan mengembangkan pengalaman pengguna di situs</li>
                    <li>Memahami dan menganalisis bagaimana Anda menggunakan situs kami (termasuk fitur Cek Transaksi, Leaderboard, dan Kalkulator)</li>
                    <li>Mengembangkan produk, layanan, fitur, dan fungsi baru</li>
                    <li>Berkomunikasi dengan Anda untuk keperluan layanan pelanggan, pemberitahuan status transaksi, promosi, dan informasi terkait situs</li>
                    <li>Mengirimkan email atau notifikasi terkait transaksi dan promo</li>
                    <li>Mendeteksi dan mencegah penipuan (fraud) dalam transaksi topup</li>
                </ul>
            </section>

            <!-- Section: File Log -->
            <section class="privacy-section" id="file-log">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2>File Log</h2>
                </div>
                <p>FUNtopup mengikuti prosedur standar penggunaan file log. File ini mencatat pengunjung saat mereka mengakses situs. Semua perusahaan hosting melakukan hal ini sebagai bagian dari analitik layanan hosting.</p>
                <p>Informasi yang dikumpulkan oleh file log meliputi alamat IP, jenis browser, Internet Service Provider (ISP), tanggal dan cap waktu, halaman rujukan/keluar, dan kemungkinan jumlah klik. Informasi ini tidak terkait dengan data yang dapat mengidentifikasi seseorang secara pribadi. Tujuannya adalah untuk menganalisis tren, mengelola situs, melacak pergerakan pengguna di situs, dan mengumpulkan informasi demografis.</p>
            </section>

            <!-- Section: Cookies -->
            <section class="privacy-section" id="cookies">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <h2>Cookies dan Web Beacon</h2>
                </div>
                <p>Seperti situs web lainnya, FUNtopup menggunakan <em>'cookies'</em>. Cookies ini digunakan untuk menyimpan informasi termasuk preferensi pengunjung dan halaman situs yang diakses atau dikunjungi.</p>
                <p>Informasi ini digunakan untuk mengoptimalkan pengalaman pengguna dengan menyesuaikan konten halaman web berdasarkan jenis browser pengunjung dan/atau informasi lainnya. Untuk informasi lebih lanjut tentang cookies, silakan baca artikel terkait di Cookie Consent.</p>
            </section>

            <!-- Section: Mitra Periklanan -->
            <section class="privacy-section" id="mitra-periklanan">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <h2>Mitra Periklanan Kami</h2>
                </div>
                <p>Beberapa pengiklan di situs kami dapat menggunakan cookies dan web beacon. Setiap mitra periklanan kami memiliki Kebijakan Privasi masing-masing terkait kebijakan data pengguna mereka.</p>
                <p>Server iklan atau jaringan iklan pihak ketiga menggunakan teknologi seperti cookies, JavaScript, atau Web Beacon yang digunakan dalam iklan dan tautan masing-masing yang muncul di FUNtopup, yang dikirim langsung ke browser pengguna. Mereka secara otomatis menerima alamat IP Anda saat hal ini terjadi. Teknologi ini digunakan untuk mengukur efektivitas kampanye iklan mereka dan/atau mempersonalisasi konten iklan yang Anda lihat.</p>
                <p>Perlu dicatat bahwa FUNtopup tidak memiliki akses atau kendali atas cookies yang digunakan oleh pengiklan pihak ketiga ini.</p>
            </section>

            <!-- Section: Pihak Ketiga -->
            <section class="privacy-section" id="pihak-ketiga">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h2>Kebijakan Privasi Pihak Ketiga</h2>
                </div>
                <p>Kebijakan Privasi FUNtopup tidak berlaku untuk pengiklan atau situs web lain, termasuk penyedia metode pembayaran (payment gateway) yang kami gunakan untuk memproses transaksi Anda. Oleh karena itu, kami menyarankan Anda untuk membaca Kebijakan Privasi masing-masing pihak ketiga tersebut untuk informasi lebih rinci.</p>
                <p>Anda dapat memilih untuk menonaktifkan cookies melalui pengaturan browser individual Anda. Untuk informasi lebih rinci tentang pengelolaan cookies dengan browser web tertentu, dapat ditemukan di situs web masing-masing browser.</p>
            </section>

            <!-- Section: Keamanan -->
            <section class="privacy-section" id="keamanan">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h2>Keamanan Transaksi dan Data Pembayaran</h2>
                </div>
                <div class="security-banner">
                    <div class="security-banner-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <p>FUNtopup bekerja sama dengan penyedia <strong>payment gateway tepercaya</strong> untuk memproses pembayaran Anda. Kami tidak menyimpan detail kartu kredit/debit atau kredensial e-wallet Anda secara langsung di server kami; proses pembayaran ditangani oleh mitra payment gateway yang telah memenuhi standar keamanan yang berlaku.</p>
                </div>
                <div class="privacy-note">
                    <p><strong>Penting:</strong> Kami menghimbau Anda untuk selalu memastikan ID Akun Game dan Server ID yang dimasukkan sudah benar sebelum menyelesaikan transaksi. FUNtopup tidak bertanggung jawab atas kesalahan input data yang menyebabkan produk terkirim ke akun yang salah.</p>
                </div>
            </section>

            <!-- Section: Hak Privasi -->
            <section class="privacy-section" id="hak-privasi">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h2>Hak Privasi Konsumen</h2>
                </div>
                <p>Kami ingin memastikan Anda sepenuhnya menyadari hak perlindungan data Anda. Setiap pengguna berhak atas hal-hal berikut:</p>
                <div class="rights-grid">
                    <div class="right-card">
                        <div class="right-card-title">🔍 Hak untuk Mengakses</div>
                        <div class="right-card-desc">Anda berhak meminta salinan data pribadi Anda yang kami simpan.</div>
                    </div>
                    <div class="right-card">
                        <div class="right-card-title">✏️ Hak atas Rektifikasi</div>
                        <div class="right-card-desc">Anda berhak meminta kami memperbaiki informasi yang tidak akurat atau tidak lengkap.</div>
                    </div>
                    <div class="right-card">
                        <div class="right-card-title">🗑️ Hak untuk Dihapuskan</div>
                        <div class="right-card-desc">Anda berhak meminta kami menghapus data pribadi Anda, dengan syarat tertentu.</div>
                    </div>
                    <div class="right-card">
                        <div class="right-card-title">⏸️ Hak Membatasi Pemrosesan</div>
                        <div class="right-card-desc">Anda berhak meminta kami membatasi pemrosesan data pribadi Anda, dengan syarat tertentu.</div>
                    </div>
                    <div class="right-card">
                        <div class="right-card-title">🚫 Hak untuk Keberatan</div>
                        <div class="right-card-desc">Anda berhak keberatan atas pemrosesan data pribadi Anda oleh kami, dengan syarat tertentu.</div>
                    </div>
                    <div class="right-card">
                        <div class="right-card-title">📦 Hak Portabilitas Data</div>
                        <div class="right-card-desc">Anda berhak meminta kami mentransfer data Anda ke organisasi lain atau langsung kepada Anda.</div>
                    </div>
                </div>
                <p style="margin-top: 18px;">Jika Anda mengajukan permintaan, kami memiliki waktu satu bulan untuk merespons Anda. Jika Anda ingin menggunakan salah satu hak ini, silakan hubungi kami.</p>
            </section>

            <!-- Section: Anak-Anak -->
            <section class="privacy-section" id="anak-anak">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h2>Informasi Anak-Anak</h2>
                </div>
                <p>Bagian lain dari prioritas kami adalah memberikan perlindungan bagi anak-anak saat menggunakan internet. Kami mendorong orang tua dan wali untuk mengamati, berpartisipasi dalam, dan/atau memantau serta membimbing aktivitas online anak mereka.</p>
                <p>FUNtopup tidak dengan sengaja mengumpulkan Informasi Identitas Pribadi apa pun dari anak-anak di bawah usia 13 tahun. Jika Anda menduga bahwa anak Anda memberikan informasi semacam ini di situs kami, kami sangat menganjurkan Anda untuk segera menghubungi kami dan kami akan berusaha sebaik mungkin untuk segera menghapus informasi tersebut dari catatan kami.</p>
            </section>

            <!-- Section: Perubahan -->
            <section class="privacy-section" id="perubahan">
                <div class="privacy-section-header">
                    <div class="privacy-section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h2>Perubahan Kebijakan Privasi Ini</h2>
                </div>
                <p>FUNtopup dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan apa pun dengan memposting Kebijakan Privasi baru di halaman ini. Anda disarankan untuk meninjau Kebijakan Privasi ini secara berkala untuk mengetahui perubahan apa pun.</p>
            </section>

            <!-- Footer Note -->
            <div class="privacy-footer-note">
                <p>Kebijakan Privasi ini terakhir diperbarui pada <strong>2026</strong>.</p>
                <p>© <?php echo date("Y"); ?> FUNtopup. Semua Hak Cipta Dilindungi.</p>
            </div>

        </div><!-- /.privacy-content -->
    </div><!-- /.privacy-layout -->

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
    const sections = document.querySelectorAll('.privacy-section');
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
