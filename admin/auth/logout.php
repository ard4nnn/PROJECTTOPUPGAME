<?php
require_once __DIR__ . '/../../includes/init.php';

// Unset admin session keys
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

header('Location: ' . $base_url . 'admin/auth/login.php');
exit;
?>
