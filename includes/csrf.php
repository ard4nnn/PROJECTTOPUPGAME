<?php
/**
 * CSRF Protection Helper -- FUNtopup
 *
 * Generates a per-session CSRF token and provides helpers
 * to embed it in forms and verify it on POST requests.
 *
 * Usage:
 *   - In forms:   echo csrf_field();
 *   - In POST handlers: if (!csrf_verify()) { ... reject ... }
 */

// Generate token once per session (session must already be started)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Return a hidden input field containing the CSRF token.
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

/**
 * Verify that the submitted CSRF token matches the session token.
 * Works for both regular form POST and fetch() POST with csrf_token in body.
 */
function csrf_verify() {
    return isset($_POST['csrf_token'])
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
?>
