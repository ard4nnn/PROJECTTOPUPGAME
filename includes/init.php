<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$base_url = "/PROJECTTOPUPGAME/";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mock_user'] = true;
}

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
