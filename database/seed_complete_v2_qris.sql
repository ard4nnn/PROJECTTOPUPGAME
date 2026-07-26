-- ============================================================
-- FUNtopup - SEED DATA LENGKAP
-- Script ini mengisi database funtopup_db dengan SEMUA data
-- game, produk, harga, dan metode pembayaran yang tampil di web.
--
-- CARA PAKAI:
--   1. Buka phpMyAdmin atau command line MySQL
--   2. Jalankan: mysql -u root funtopup_db < database/seed_complete.sql
--   3. Atau import file ini via phpMyAdmin
-- ============================================================

USE funtopup_db;

-- ────────────────────────────────────────────────────────────
-- HAPUS DATA LAMA (urutan FK-safe: transaksi → produk → games → metode)
-- ────────────────────────────────────────────────────────────
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE transaksi;
TRUNCATE TABLE produk;
TRUNCATE TABLE games;
TRUNCATE TABLE metode_bayar;
SET FOREIGN_KEY_CHECKS = 1;

-- ────────────────────────────────────────────────────────────
-- TABEL: games (4 game)
-- ────────────────────────────────────────────────────────────
INSERT INTO games (id, nama_game, slug, deskripsi, status) VALUES
(1, 'Mobile Legends',   'mobile-legends',   'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.', 'aktif'),
(2, 'Free Fire',        'free-fire',        'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',        'aktif'),
(3, 'PUBG Mobile',      'pubg-mobile',      'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',               'aktif'),
(4, 'Wuthering Waves',  'wuthering-waves',  'Top up Lunites Wuthering Waves untuk gacha karakter impianmu.',                  'aktif');

-- ────────────────────────────────────────────────────────────
-- TABEL: metode_bayar (5 metode pembayaran)
-- QRIS ditambahkan untuk persiapan integrasi Midtrans nanti
-- ────────────────────────────────────────────────────────────
INSERT INTO metode_bayar (id, nama, kode, status) VALUES
(1, 'DANA',                'DANA',    'aktif'),
(2, 'GoPay',               'GOPAY',   'aktif'),
(3, 'OVO',                 'OVO',     'aktif'),
(4, 'Transfer Bank BCA',   'BCA',     'aktif'),
(5, 'QRIS',                'QRIS',    'aktif');

-- ────────────────────────────────────────────────────────────
-- TABEL: produk — MOBILE LEGENDS (game_id = 1)
-- Memberships + Diamonds — 66 produk
-- ────────────────────────────────────────────────────────────
INSERT INTO produk (game_id, nama_produk, jumlah, harga, status) VALUES
-- Memberships ML
(1, 'Weekly Diamond Pass',     '1', 29551,  'aktif'),
(1, '2x Weekly Diamond Pass',  '1', 59102,  'aktif'),
(1, '3x Weekly Diamond Pass',  '1', 88653,  'aktif'),
(1, '4x Weekly Diamond Pass',  '1', 118204, 'aktif'),
(1, '5x Weekly Diamond Pass',  '1', 147755, 'aktif'),
(1, 'Twilight Pass',           '1', 152771, 'aktif'),

-- Diamonds ML
(1, '5 (5+0) Diamonds',          '5',    1530,   'aktif'),
(1, '10 (9+1) Diamonds',         '10',   3111,   'aktif'),
(1, '12 (11+1) Diamonds',        '12',   3567,   'aktif'),
(1, '14 (13+1) Diamonds',        '14',   4148,   'aktif'),
(1, '15 (15+0) Diamonds',        '15',   4588,   'aktif'),
(1, '17 (16+1) Diamonds',        '17',   4779,   'aktif'),
(1, '18 (17+1) Diamonds',        '18',   5185,   'aktif'),
(1, '19 (17+2) Diamonds',        '19',   5606,   'aktif'),
(1, '20 (18+2) Diamonds',        '20',   6221,   'aktif'),
(1, '22 (20+2) Diamonds',        '22',   6677,   'aktif'),
(1, '28 (25+3) Diamonds',        '28',   8153,   'aktif'),
(1, '30 (28+2) Diamonds',        '30',   8751,   'aktif'),
(1, '33 (30+3) Diamonds',        '33',   9559,   'aktif'),
(1, '36 (33+3) Diamonds',        '36',   10367,  'aktif'),
(1, '44 (40+4) Diamonds',        '44',   12228,  'aktif'),
(1, '45 (42+3) Diamonds',        '45',   13390,  'aktif'),
(1, '46 (42+4) Diamonds',        '46',   13477,  'aktif'),
(1, '50 (46+4) Diamonds',        '50',   14337,  'aktif'),
(1, '54 (49+5) Diamonds',        '54',   15339,  'aktif'),
(1, '56 (51+5) Diamonds',        '56',   15795,  'aktif'),
(1, '59 (53+6) Diamonds',        '59',   16305,  'aktif'),
(1, '60 (55+5) Diamonds',        '60',   17624,  'aktif'),
(1, '64 (58+6) Diamonds',        '64',   17834,  'aktif'),
(1, '67 (62+5) Diamonds',        '67',   18942,  'aktif'),
(1, '71 (64+7) Diamonds',        '71',   19871,  'aktif'),
(1, '74 (67+7) Diamonds',        '74',   20732,  'aktif'),
(1, '78 (70+8) Diamonds',        '78',   21911,  'aktif'),
(1, '80 (73+7) Diamonds',        '80',   22595,  'aktif'),
(1, '85 (77+8) Diamonds',        '85',   23439,  'aktif'),
(1, '88 (80+8) Diamonds',        '88',   24879,  'aktif'),
(1, '89 (81+8) Diamonds',        '89',   25056,  'aktif'),
(1, '92 (84+8) Diamonds',        '92',   25916,  'aktif'),
(1, '98 (89+9) Diamonds',        '98',   27864,  'aktif'),
(1, '100 (91+9) Diamonds',       '100',  28078,  'aktif'),
(1, '110 (100+10) Diamonds',     '110',  31098,  'aktif'),
(1, '113 (102+11) Diamonds',     '113',  31591,  'aktif'),
(1, '116 (105+11) Diamonds',     '116',  33031,  'aktif'),
(1, '129 (117+12) Diamonds',     '129',  35667,  'aktif'),
(1, '148 (134+14) Diamonds',     '148',  41463,  'aktif'),
(1, '170 (154+16) Diamonds',     '170',  46876,  'aktif'),
(1, '176 (160+16) Diamonds',     '176',  49758,  'aktif'),
(1, '184 (167+17) Diamonds',     '184',  53097,  'aktif'),
(1, '222 (200+22) Diamonds',     '222',  62193,  'aktif'),
(1, '240 (217+23) Diamonds',     '240',  66201,  'aktif'),
(1, '241 (218+23) Diamonds',     '241',  67870,  'aktif'),
(1, '277 (250+27) Diamonds',     '277',  77741,  'aktif'),
(1, '284 (257+27) Diamonds',     '284',  78429,  'aktif'),
(1, '296 (256+40) Diamonds',     '296',  81478,  'aktif'),
(1, '305 (276+29) Diamonds',     '305',  86036,  'aktif'),
(1, '370 (333+37) Diamonds',     '370',  103654, 'aktif'),
(1, '384 (346+38) Diamonds',     '384',  107801, 'aktif'),
(1, '408 (367+41) Diamonds',     '408',  112032, 'aktif'),
(1, '518 (467+51) Diamonds',     '518',  145116, 'aktif'),
(1, '568 (503+65) Diamonds',     '568',  152771, 'aktif'),
(1, '716 (637+79) Diamonds',     '716',  194233, 'aktif'),
(1, '750 (676+74) Diamonds',     '750',  203213, 'aktif'),
(1, '790 (703+87) Diamonds',     '790',  214963, 'aktif'),
(1, '875 (774+101) Diamonds',    '875',  234248, 'aktif'),
(1, '966 (836+130) Diamonds',    '966',  259133, 'aktif'),
(1, '1048 (936+112) Diamonds',   '1048', 287522, 'aktif');

-- ────────────────────────────────────────────────────────────
-- TABEL: produk — FREE FIRE (game_id = 2)
-- Memberships + Diamonds — 44 produk
-- ────────────────────────────────────────────────────────────
INSERT INTO produk (game_id, nama_produk, jumlah, harga, status) VALUES
-- Memberships FF
(2, 'Member Mingguan',    '1', 30008,  'aktif'),
(2, 'Member Bulanan',     '1', 89951,  'aktif'),

-- Diamonds FF
(2, '5 Diamonds',         '5',    974,    'aktif'),
(2, '12 Diamonds',        '12',   1948,   'aktif'),
(2, '15 Diamonds',        '15',   2922,   'aktif'),
(2, '20 Diamonds',        '20',   3895,   'aktif'),
(2, '25 Diamonds',        '25',   4869,   'aktif'),
(2, '30 Diamonds',        '30',   5843,   'aktif'),
(2, '50 Diamonds',        '50',   7787,   'aktif'),
(2, '55 Diamonds',        '55',   8761,   'aktif'),
(2, '70 Diamonds',        '70',   9733,   'aktif'),
(2, '75 Diamonds',        '75',   10707,  'aktif'),
(2, '80 Diamonds',        '80',   11681,  'aktif'),
(2, '90 Diamonds',        '90',   13628,  'aktif'),
(2, '95 Diamonds',        '95',   14602,  'aktif'),
(2, '100 Diamonds',       '100',  15575,  'aktif'),
(2, '120 Diamonds',       '120',  17520,  'aktif'),
(2, '125 Diamonds',       '125',  18493,  'aktif'),
(2, '140 Diamonds',       '140',  19465,  'aktif'),
(2, '145 Diamonds',       '145',  20439,  'aktif'),
(2, '150 Diamonds',       '150',  21412,  'aktif'),
(2, '160 Diamonds',       '160',  23360,  'aktif'),
(2, '170 Diamonds',       '170',  25307,  'aktif'),
(2, '190 Diamonds',       '190',  27252,  'aktif'),
(2, '210 Diamonds',       '210',  29198,  'aktif'),
(2, '230 Diamonds',       '230',  33092,  'aktif'),
(2, '260 Diamonds',       '260',  36984,  'aktif'),
(2, '280 Diamonds',       '280',  38929,  'aktif'),
(2, '300 Diamonds',       '300',  42824,  'aktif'),
(2, '355 Diamonds',       '355',  48661,  'aktif'),
(2, '360 Diamonds',       '360',  49635,  'aktif'),
(2, '375 Diamonds',       '375',  52556,  'aktif'),
(2, '405 Diamonds',       '405',  56447,  'aktif'),
(2, '425 Diamonds',       '425',  58394,  'aktif'),
(2, '455 Diamonds',       '455',  64236,  'aktif'),
(2, '475 Diamonds',       '475',  66180,  'aktif'),
(2, '495 Diamonds',       '495',  68125,  'aktif'),
(2, '500 Diamonds',       '500',  69099,  'aktif'),
(2, '512 Diamonds',       '512',  71046,  'aktif'),
(2, '515 Diamonds',       '515',  72020,  'aktif'),
(2, '520 Diamonds',       '520',  72994,  'aktif'),
(2, '545 Diamonds',       '545',  75912,  'aktif'),
(2, '565 Diamonds',       '565',  77858,  'aktif'),
(2, '600 Diamonds',       '600',  84674,  'aktif');

-- ────────────────────────────────────────────────────────────
-- TABEL: produk — PUBG MOBILE (game_id = 3)
-- Elite Pass + UC — 32 produk
-- ────────────────────────────────────────────────────────────
INSERT INTO produk (game_id, nama_produk, jumlah, harga, status) VALUES
-- Elite Pass PUBG
(3, 'Elite Pass',           '1',   186768,  'aktif'),
(3, 'Elite Pass Plus',      '1',   466917,  'aktif'),

-- UC PUBG
(3, '60 UC (ID)',                       '60',    16902,   'aktif'),
(3, '120 UC (ID)',                      '120',   33804,   'aktif'),
(3, '180 UC (ID)',                      '180',   50706,   'aktif'),
(3, '240 UC (ID)',                      '240',   67607,   'aktif'),
(3, '325 (300+25) UC (ID)',             '325',   84898,   'aktif'),
(3, '385 (360+25) UC (ID)',             '385',   101799,  'aktif'),
(3, '445 (420+25) UC (ID)',             '445',   118701,  'aktif'),
(3, '505 (480+25) UC (ID)',             '505',   135603,  'aktif'),
(3, '565 (540+25) UC (ID)',             '565',   152504,  'aktif'),
(3, '660 (600+60) UC (ID)',             '660',   169795,  'aktif'),
(3, '720 (660+60) UC (ID)',             '720',   186696,  'aktif'),
(3, '780 (720+60) UC (ID)',             '780',   203598,  'aktif'),
(3, '840 (780+60) UC (ID)',             '840',   220500,  'aktif'),
(3, '900 (840+60) UC (ID)',             '900',   237401,  'aktif'),
(3, '985 (900+85) UC (ID)',             '985',   254692,  'aktif'),
(3, '1105 (1020+85) UC (ID)',           '1105',  288495,  'aktif'),
(3, '1165 (1080+85) UC (ID)',           '1165',  305397,  'aktif'),
(3, '1320 (1200+120) UC (ID)',          '1320',  339589,  'aktif'),
(3, '1500 (1380+120) UC (ID)',          '1500',  390294,  'aktif'),
(3, '1800 (1500+300) UC (ID)',          '1800',  424872,  'aktif'),
(3, '1920 (1620+300) UC (ID)',          '1920',  458675,  'aktif'),
(3, '1980 (1680+300) UC (ID)',          '1980',  475577,  'aktif'),
(3, '2125 (1800+325) UC (ID)',          '2125',  509769,  'aktif'),
(3, '2460 (2100+360) UC (ID)',          '2460',  594666,  'aktif'),
(3, '2785 (2400+385) UC (ID)',          '2785',  679563,  'aktif'),
(3, '3120 (2700+420) UC (ID)',          '3120',  764460,  'aktif'),
(3, '3850 (3000+850) UC (ID)',          '3850',  849743,  'aktif'),
(3, '4030 (3180+850) UC (ID)',          '4030',  900448,  'aktif'),
(3, '4175 (3300+875) UC (ID)',          '4175',  934640,  'aktif'),
(3, '4510 (3600+910) UC (ID)',          '4510',  1019537, 'aktif');

-- ────────────────────────────────────────────────────────────
-- TABEL: produk — WUTHERING WAVES (game_id = 4)
-- Lunite Subscription + Lunites — 7 produk
-- ────────────────────────────────────────────────────────────
INSERT INTO produk (game_id, nama_produk, jumlah, harga, status) VALUES
-- Subscription
(4, 'Lunite Subscription',         '1',    77513,   'aktif'),

-- Lunites Top Up
(4, '60 Lunites',                  '60',   15267,   'aktif'),
(4, '300 + 30 Lunites',            '330',  76505,   'aktif'),
(4, '980 + 110 Lunites',           '1090', 234116,  'aktif'),
(4, '1980 + 260 Lunites',          '2240', 464857,  'aktif'),
(4, '3280 + 600 Lunites',          '3880', 767700,  'aktif'),
(4, '6480 + 1600 Lunites',         '8080', 1484052, 'aktif');

-- ────────────────────────────────────────────────────────────
-- ADMIN USER: Jadikan user 'ardannn' sebagai admin
-- Jalankan ini setelah user 'ardannn' sudah terdaftar lewat Google OAuth / register
-- ────────────────────────────────────────────────────────────
UPDATE users SET is_admin = 1 WHERE username = 'ardannn';

-- ────────────────────────────────────────────────────────────
-- SELESAI! Verifikasi:
-- ────────────────────────────────────────────────────────────
SELECT '=== SEED COMPLETE ===' AS info;
SELECT 'games' AS tabel, COUNT(*) AS jumlah FROM games
UNION ALL
SELECT 'produk', COUNT(*) FROM produk
UNION ALL
SELECT 'metode_bayar', COUNT(*) FROM metode_bayar;
