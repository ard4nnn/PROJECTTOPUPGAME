<?php
/**
 * process.php — Checkout endpoint untuk FUNtopup
 *
 * Menerima POST: produk_id, id_game_user, metode_bayar_id, qty
 * Mengembalikan JSON: { success, invoice_id, message }
 *
 * Prinsip desain: coba tulis ke MySQL, kalau DB tidak connect → kembalikan
 * flag offline agar JS bisa fallback ke localStorage (mode demo).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Pastikan response selalu JSON
header('Content-Type: application/json; charset=utf-8');

// ─── Cek koneksi DB ───────────────────────────────────────────────
if (!$db_connected || !$pdo) {
    // DB offline — beri tahu JS agar fallback ke demo/localStorage
    echo json_encode([
        'success'    => false,
        'db_offline' => true,
        'message'    => 'Database tidak tersedia. Menggunakan mode demo.'
    ]);
    exit;
}

// ─── Cek autentikasi ─────────────────────────────────────────────
// user_id di tabel transaksi adalah NOT NULL + FK ke users.id,
// sehingga guest checkout tidak diperbolehkan oleh skema DB.
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Silakan login terlebih dahulu untuk melakukan pembelian.'
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// ─── Ambil & validasi input POST ─────────────────────────────────
$produk_id      = isset($_POST['produk_id'])      ? (int) $_POST['produk_id']      : 0;
$metode_bayar_id = isset($_POST['metode_bayar_id']) ? (int) $_POST['metode_bayar_id'] : 0;
$id_game_user   = isset($_POST['id_game_user'])   ? trim($_POST['id_game_user'])    : '';
$qty            = isset($_POST['qty'])            ? (int) $_POST['qty']            : 1;

if ($produk_id <= 0 || $metode_bayar_id <= 0 || empty($id_game_user)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Data pembelian tidak lengkap.']);
    exit;
}

if ($qty < 1 || $qty > 99) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Jumlah pembelian tidak valid.']);
    exit;
}

try {
    // ─── Validasi produk (aktif, harga dari server) ───────────────
    $stmtProd = $pdo->prepare("SELECT id, harga, nama_produk FROM produk WHERE id = ? AND status = 'aktif'");
    $stmtProd->execute([$produk_id]);
    $produk = $stmtProd->fetch();

    if (!$produk) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan atau tidak aktif.']);
        exit;
    }

    // ─── Validasi metode pembayaran (aktif) ───────────────────────
    $stmtMetode = $pdo->prepare("SELECT id FROM metode_bayar WHERE id = ? AND status = 'aktif'");
    $stmtMetode->execute([$metode_bayar_id]);
    $metode = $stmtMetode->fetch();

    if (!$metode) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Metode pembayaran tidak ditemukan atau tidak aktif.']);
        exit;
    }

    // ─── Validasi user_id ada di tabel users ─────────────────────
    // (mempertahankan integritas FK — penting untuk akun demo user_id=999)
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $userRow = $stmtUser->fetch();

    if (!$userRow) {
        // Bisa terjadi di mode demo (user_id=999 tidak ada di DB)
        // Perlakukan seperti DB offline → fallback ke demo localStorage
        echo json_encode([
            'success'    => false,
            'db_offline' => true,
            'message'    => 'Akun demo tidak bisa menyimpan transaksi ke database. Menggunakan mode demo.'
        ]);
        exit;
    }

    // ─── Hitung harga di server (JANGAN percaya harga dari client) ─
    $nominal_transfer = $produk['harga'] * $qty;

    // ─── Insert ke tabel transaksi ────────────────────────────────
    $stmtInsert = $pdo->prepare("
        INSERT INTO transaksi (user_id, produk_id, metode_bayar_id, nominal_transfer, status, id_game_user)
        VALUES (?, ?, ?, ?, 'pending', ?)
    ");
    $stmtInsert->execute([
        $user_id,
        $produk_id,
        $metode_bayar_id,
        $nominal_transfer,
        $id_game_user
    ]);

    $invoice_id = (int) $pdo->lastInsertId();

    // ─── Sukses — kembalikan invoice ID asli ─────────────────────
    echo json_encode([
        'success'    => true,
        'invoice_id' => $invoice_id,
        'message'    => 'Transaksi berhasil dibuat.'
    ]);
    exit;

} catch (PDOException $e) {
    // Error DB saat proses — fallback ke demo
    echo json_encode([
        'success'    => false,
        'db_offline' => true,
        'message'    => 'Kesalahan database saat memproses transaksi. Menggunakan mode demo.'
    ]);
    exit;
}
