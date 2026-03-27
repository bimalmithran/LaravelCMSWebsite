<?php
require_once __DIR__ . '/bootstrap.php';

// Revoke token on the backend (best-effort; ignore errors)
if (!empty($_SESSION['customer_token'])) {
    $authService->logout($_SESSION['customer_token']);
}

// Destroy local session data
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
session_destroy();

header('Location: index.php');
exit;
