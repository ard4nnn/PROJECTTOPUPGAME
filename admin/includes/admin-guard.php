<?php
// admin-guard.php — Secures administrative pages
require_once __DIR__ . '/../../includes/init.php';

if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: ' . $base_url . 'admin/auth/login.php');
    exit;
}
?>
