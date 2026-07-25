<?php
/**
 * midtrans-notification.php — Midtrans Webhook / Notification Handler
 * 
 * Endpoint ini dipanggil langsung oleh server Midtrans (tanpa session/CSRF).
 * Wajib melakukan verifikasi SHA512 signature key sebelum memproses update status.
 */

// Pastikan header JSON
header('Content-Type: application/json; charset=utf-8');

// Load konfigurasi DB & Midtrans
require_once __DIR__ . '/../../config/db.php';
if (file_exists(__DIR__ . '/../../config/midtrans.php')) {
    require_once __DIR__ . '/../../config/midtrans.php';
}

// Cek koneksi DB
if (!$db_connected || !$pdo) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Cek apakah MIDTRANS_SERVER_KEY sudah dikonfigurasi
if (!defined('MIDTRANS_SERVER_KEY') || empty(MIDTRANS_SERVER_KEY)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server key not configured']);
    exit;
}

// Ambil raw POST data (JSON payload dari Midtrans)
$rawInput = file_get_contents('php://input');
$data     = json_decode($rawInput, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload']);
    exit;
}

// Ambil data yang dibutuhkan dari payload
$order_id           = isset($data['order_id'])           ? trim((string) $data['order_id']) : '';
$status_code        = isset($data['status_code'])        ? trim((string) $data['status_code']) : '';
$gross_amount       = isset($data['gross_amount'])       ? trim((string) $data['gross_amount']) : '';
$signature_key      = isset($data['signature_key'])      ? trim((string) $data['signature_key']) : '';
$transaction_status = isset($data['transaction_status']) ? trim((string) $data['transaction_status']) : '';
$fraud_status       = isset($data['fraud_status'])       ? trim((string) $data['fraud_status']) : '';

if (empty($order_id) || empty($status_code) || empty($gross_amount) || empty($signature_key)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required notification fields']);
    exit;
}

// ─── 1. VERIFIKASI SIGNATURE KEY (WAJIB) ─────────────────────────────────
// Format Signature: SHA512(order_id + status_code + gross_amount + ServerKey)
$expected_signature = hash('sha512', $order_id . $status_code . $gross_amount . MIDTRANS_SERVER_KEY);

if (!hash_equals($expected_signature, $signature_key)) {
    http_response_code(403);
    echo json_encode(['status' => 'forbidden', 'message' => 'Invalid signature key']);
    exit;
}

try {
    // ─── 2. CARI TRANSAKSI DI DATABASE ────────────────────────────────────
    $stmt = $pdo->prepare("SELECT id, nominal_transfer, status FROM transaksi WHERE id = ?");
    $stmt->execute([$order_id]);
    $trx = $stmt->fetch();

    if (!$trx) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Transaction not found in database']);
        exit;
    }

    // ─── 3. COCOKKAN GROSS AMOUNT (LAPISAN KEAMANAN TAMBAHAN) ─────────────
    $notif_amount = (int) round((float) $gross_amount);
    $db_amount    = (int) round((float) $trx['nominal_transfer']);

    if ($notif_amount !== $db_amount) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Gross amount mismatch anomaly']);
        exit;
    }

    // ─── 4. PEMETAAN STATUS MIDTRANS KE STATUS INTERNAL ───────────────────
    $new_status = null;

    if ($transaction_status === 'settlement') {
        $new_status = 'sukses';
    } elseif ($transaction_status === 'capture') {
        if ($fraud_status === 'accept') {
            $new_status = 'sukses';
        } else {
            $new_status = 'gagal';
        }
    } elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
        $new_status = 'gagal';
    } elseif ($transaction_status === 'pending') {
        $new_status = 'pending';
    }

    // ─── 5. UPDATE STATUS DENGAN PREPARED STATEMENT ───────────────────────
    if ($new_status !== null) {
        $updateStmt = $pdo->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
        $updateStmt->execute([$new_status, $order_id]);
    }

    // Respon HTTP 200 ke Midtrans
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Notification processed successfully']);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database query error']);
    exit;
}
