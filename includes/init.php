<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Auto-calculate base_url from filesystem path relative to document root.
// Produces "/PROJECTTOPUPGAME/" in XAMPP subfolder, or "/" at root domain — no manual edit needed.
$project_root_fs = str_replace('\\', '/', dirname(__DIR__));
$document_root   = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$base_url        = str_replace($document_root, '', $project_root_fs);
$base_url        = '/' . trim($base_url, '/') . '/';

// Load CSRF protection helper (must be after session_start)
require_once __DIR__ . '/csrf.php';

// Language Logic
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'id';
    $_SESSION['lang'] = $lang;
}
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// Load translation
$translations = include __DIR__ . '/../lang/' . $current_lang . '.php';

if (!function_exists('__')) {
    function __($key) {
        global $translations;
        return isset($translations[$key]) ? $translations[$key] : $key;
    }
}
?>
