<?php
// 0. Start session early (needed for customer auth state across pages)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = "App\\";
    $base_dir = __DIR__ . "/src/";
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace("\\", "/", $relative_class) . ".php";
    if (file_exists($file)) {
        require $file;
    }
});

// 2. Load Config & Wire Dependencies
$config = require __DIR__ . "/config.php";
$apiClient = new \App\Http\LaravelApiClient(
    $config["api_base_url"],
    $config["api_key"],
);
$storefront       = new \App\Services\StorefrontService($apiClient);
$headerService    = new \App\Services\HeaderService($apiClient);
$footerService    = new \App\Services\FooterService($apiClient);
$authService      = new \App\Services\AuthService($apiClient);
$currencySymbol   = (string) ($config["currency_symbol"] ?? "₹");

// Convenience helpers available to every page
$loggedIn        = !empty($_SESSION['customer_token']);
$customerData    = $_SESSION['customer_data'] ?? null;
