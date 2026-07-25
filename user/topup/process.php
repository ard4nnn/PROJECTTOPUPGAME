<?php
/**
 * process.php — Checkout endpoint untuk FUNtopup
 *
 * Menerima POST: produk_id, id_game_user, metode_bayar_id, qty
 * Mengembalikan JSON: { success, invoice_id, snap_token, nominal_transfer, message }
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/csrf.php';

if (file_exists(__DIR__ . '/../../config/midtrans.php')) {
    require_once __DIR__ . '/../../config/midtrans.php';
}

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Pastikan response selalu JSON
header('Content-Type: application/json; charset=utf-8');

// ─── CSRF Verification ──────────────────────────────────────────
if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid. Silakan muat ulang halaman dan coba lagi.']);
    exit;
}

// ─── Cek koneksi DB ───────────────────────────────────────────────
if (!$db_connected || !$pdo) {
    echo json_encode([
        'success' => false,
        'message' => 'Layanan sedang mengalami gangguan. Silakan coba beberapa saat lagi.'
    ]);
    exit;
}

// ─── Cek autentikasi ─────────────────────────────────────────────
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
$produk_id       = isset($_POST['produk_id'])       ? (int) $_POST['produk_id']      : 0;
$metode_bayar_id = isset($_POST['metode_bayar_id']) ? (int) $_POST['metode_bayar_id'] : 0;
$id_game_user    = isset($_POST['id_game_user'])    ? trim($_POST['id_game_user'])    : '';
$qty             = isset($_POST['qty'])             ? (int) $_POST['qty']            : 1;

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
    $stmtMetode = $pdo->prepare("SELECT id, nama, kode FROM metode_bayar WHERE id = ? AND status = 'aktif'");
    $stmtMetode->execute([$metode_bayar_id]);
    $metode = $stmtMetode->fetch();

    if (!$metode) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Metode pembayaran tidak ditemukan atau tidak aktif.']);
        exit;
    }

    // ─── Validasi user_id ada di tabel users & ambil detail ────────
    $stmtUser = $pdo->prepare("SELECT id, username, email, no_hp FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $userRow = $stmtUser->fetch();

    if (!$userRow) {
        echo json_encode([
            'success' => false,
            'message' => 'Akun Anda tidak valid.'
        ]);
        exit;
    }

    // ─── Hitung harga di server ──────────────────────────────────
    $nominal_transfer = $produk['harga'] * $qty;

    // ─── Insert ke tabel transaksi status 'pending' ───────────────
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

    // ─── Panggil Midtrans Snap Create Transaction API ────────────
    $snap_token = null;
    $midtrans_error = null;

    if (defined('MIDTRANS_SERVER_KEY') && !empty(MIDTRANS_SERVER_KEY) && MIDTRANS_SERVER_KEY !== 'SB-Mid-server-YOUR_SERVER_KEY_HERE') {
        // Map kode metode_bayar ke enabled_payments Midtrans
        $enabled_payments = [];
        $kode_metode = strtoupper($metode['kode']);

        switch ($kode_metode) {
            case 'BCA':
                $enabled_payments = ['bca_va'];
                break;
            case 'DANA':
                $enabled_payments = ['dana'];
                break;
            case 'QRIS':
                $enabled_payments = ['gopay', 'other_qris', 'shopeepay'];
                break;
            case 'GOPAY':
                $enabled_payments = ['gopay'];
                break;
            case 'OVO':
                $enabled_payments = ['ovo'];
                break;
            default:
                $enabled_payments = [strtolower($metode['kode'])];
                break;
        }

        $is_production = defined('MIDTRANS_IS_PRODUCTION') && MIDTRANS_IS_PRODUCTION;
        $snap_url = $is_production 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id'     => (string) $invoice_id,
                'gross_amount' => (int) round($nominal_transfer)
            ],
            'customer_details' => [
                'first_name' => !empty($userRow['username']) ? $userRow['username'] : 'Customer',
                'email'      => !empty($userRow['email']) ? $userRow['email'] : 'customer@example.com',
                'phone'      => !empty($userRow['no_hp']) ? $userRow['no_hp'] : ''
            ],
            'item_details' => [
                [
                    'id'       => (string) $produk['id'],
                    'price'    => (int) round($produk['harga']),
                    'quantity' => $qty,
                    'name'     => mb_substr($produk['nama_produk'], 0, 50)
                ]
            ],
            'enabled_payments' => $enabled_payments
        ];

        $ch = curl_init($snap_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':')
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $responseRaw = curl_exec($ch);
        $curlErrNo   = curl_errno($ch);
        $curlError   = curl_error($ch);
        $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErrNo) {
            $midtrans_error = 'Koneksi ke gateway pembayaran gagal: ' . $curlError;
        } else {
            $resData = json_decode($responseRaw, true);
            if ($httpCode >= 200 && $httpCode < 300 && isset($resData['token'])) {
                $snap_token = $resData['token'];
            } else {
                $msg = isset($resData['error_messages'][0]) ? $resData['error_messages'][0] : 'Gagal mendapatkan token transaksi Midtrans (HTTP ' . $httpCode . ').';
                $midtrans_error = $msg;
            }
        }
    }

    echo json_encode([
        'success'          => true,
        'invoice_id'       => $invoice_id,
        'snap_token'       => $snap_token,
        'nominal_transfer' => $nominal_transfer,
        'message'          => 'Transaksi berhasil dibuat.',
        'midtrans_error'   => $midtrans_error
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Layanan sedang mengalami gangguan. Silakan coba beberapa saat lagi.'
    ]);
    exit;
}
