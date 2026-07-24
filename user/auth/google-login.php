<?php
require_once __DIR__ . '/../../includes/init.php';

if (file_exists(__DIR__ . '/../../config/google-oauth.php')) {
    require_once __DIR__ . '/../../config/google-oauth.php';
}

if (!defined('GOOGLE_CLIENT_ID') || empty(GOOGLE_CLIENT_ID) || GOOGLE_CLIENT_ID === 'ISI_CLIENT_ID_DARI_GOOGLE_CLOUD_CONSOLE') {
    header("Location: " . $base_url . "user/auth/login.php?error=" . urlencode("Konfigurasi Google Client ID belum diisi di file config/google-oauth.php."));
    exit;
}

// Generate CSRF state token khusus OAuth
$_SESSION['oauth_state'] = bin2hex(random_bytes(16));

// Hitung redirect_uri secara dinamis berbasis environment
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$redirect_uri = $scheme . '://' . $_SERVER['HTTP_HOST'] . $base_url . 'user/auth/google-callback.php';

// Parameter otorisasi Google OAuth 2.0
$params = [
    'client_id'     => GOOGLE_CLIENT_ID,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code',
    'scope'         => 'openid email profile',
    'state'         => $_SESSION['oauth_state'],
    'prompt'        => 'select_account'
];

$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

header("Location: " . $auth_url);
exit;
