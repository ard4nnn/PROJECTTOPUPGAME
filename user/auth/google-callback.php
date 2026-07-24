<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../config/db.php';

if (file_exists(__DIR__ . '/../../config/google-oauth.php')) {
    require_once __DIR__ . '/../../config/google-oauth.php';
}

// 1. Verifikasi CSRF State
$saved_state    = isset($_SESSION['oauth_state']) ? $_SESSION['oauth_state'] : '';
$returned_state = isset($_GET['state']) ? $_GET['state'] : '';
unset($_SESSION['oauth_state']); // Hapus state setelah dibaca

if (empty($saved_state) || empty($returned_state) || !hash_equals($saved_state, $returned_state)) {
    header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Sesi OAuth tidak valid atau telah kedaluwarsa. Silakan coba lagi."));
    exit;
}

// 2. Cek pembatalan otorisasi oleh user
if (isset($_GET['error'])) {
    header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Login Google dibatalkan."));
    exit;
}

if (!isset($_GET['code']) || empty($_GET['code'])) {
    header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Kode otorisasi dari Google tidak ditemukan."));
    exit;
}

$code = $_GET['code'];

// Hitung redirect_uri secara dinamis berbasis environment
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$redirect_uri = $scheme . '://' . $_SERVER['HTTP_HOST'] . $base_url . 'user/auth/google-callback.php';

// 3. Tukar authorization code dengan access token via cURL
try {
    $token_url = 'https://oauth2.googleapis.com/token';
    $post_fields = [
        'client_id'     => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'code'          => $code,
        'redirect_uri'  => $redirect_uri,
        'grant_type'    => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    $response = curl_exec($ch);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($response === false || !empty($curl_err)) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Gagal terhubung ke layanan otentikasi Google."));
        exit;
    }

    $token_data = json_decode($response, true);
    if (!isset($token_data['access_token'])) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Gagal mendapatkan token akses dari Google."));
        exit;
    }

    $access_token = $token_data['access_token'];

    // 4. Ambil profil user via Google REST API endpoint
    $userinfo_url = 'https://www.googleapis.com/oauth2/v3/userinfo';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $userinfo_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token
    ]);

    $userinfo_response = curl_exec($ch);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($userinfo_response === false || !empty($curl_err)) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Gagal mengambil informasi akun dari Google."));
        exit;
    }

    $userinfo = json_decode($userinfo_response, true);
    if (!isset($userinfo['sub']) || !isset($userinfo['email'])) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Informasi profil Google tidak lengkap."));
        exit;
    }

    // 5. Validasi email_verified === true
    $email_verified = isset($userinfo['email_verified']) ? $userinfo['email_verified'] : false;
    if ($email_verified !== true && $email_verified !== 'true' && $email_verified !== 1) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Email Google Anda belum terverifikasi. Silakan verifikasi email Anda di Google terlebih dahulu."));
        exit;
    }

    $google_id = $userinfo['sub'];
    $email     = filter_var($userinfo['email'], FILTER_SANITIZE_EMAIL);

    if (!$db_connected || !$pdo) {
        header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Gagal terhubung ke database server."));
        exit;
    }

    // 6. Cari user di database
    // A. Cari berdasarkan google_id
    $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
    $stmt->execute([$google_id]);
    $user = $stmt->fetch();

    if (!$user) {
        // B. Jika tidak ditemukan via google_id, cari berdasarkan email (Auto-link akun manual)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_email_user = $stmt->fetch();

        if ($existing_email_user) {
            // Auto-link: update google_id di baris user tersebut
            $update_stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
            $update_stmt->execute([$google_id, $existing_email_user['id']]);

            $user = $existing_email_user;
            $user['google_id'] = $google_id;
        }
    }

    if (!$user) {
        // C. Jika belum terdaftar sama sekali, buat akun baru
        // Generate username unik dari prefix email
        $email_parts = explode('@', $email);
        $base_username = preg_replace('/[^a-zA-Z0-9_]/', '', $email_parts[0]);
        if (empty($base_username)) {
            $base_username = 'user';
        }

        $username = $base_username;
        while (true) {
            $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $check_stmt->execute([$username]);
            if (!$check_stmt->fetch()) {
                break;
            }
            $username = $base_username . rand(100, 999);
        }

        // Hash password acak yang tidak pernah diketahui siapapun
        $random_password = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);
        $no_hp = ''; // no_hp NOT NULL tanpa default -> isi string kosong ''

        $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, no_hp, google_id) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->execute([$username, $email, $random_password, $no_hp, $google_id]);

        $new_id = $pdo->lastInsertId();
        $user = [
            'id'       => $new_id,
            'username' => $username,
            'email'    => $email,
            'saldo'    => 0.00
        ];
    }

    // 7. Buat session login
    $_SESSION['login_attempts'] = 0;
    $_SESSION['user_id']        = $user['id'];
    $_SESSION['username']       = $user['username'];
    $_SESSION['saldo']          = isset($user['saldo']) ? $user['saldo'] : 0.00;

    header("Location: " . $base_url);
    exit;

} catch (\Exception $e) {
    header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Terjadi kesalahan sistem saat verifikasi Google Login."));
    exit;
}
