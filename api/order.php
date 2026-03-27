<?php
require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['customer_token'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

$token = $_SESSION['customer_token'];
$body  = json_decode(file_get_contents('php://input'), true);

if (!is_array($body)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid request body']);
    exit;
}

$customerName  = trim((string) ($body['customer_name']    ?? ''));
$customerEmail = trim((string) ($body['customer_email']   ?? ''));
$customerPhone = trim((string) ($body['customer_phone']   ?? ''));
$billingAddr   = trim((string) ($body['billing_address']  ?? ''));
$shippingAddr  = trim((string) ($body['shipping_address'] ?? ''));
$notes         = trim((string) ($body['notes']            ?? ''));

if ($customerName === '' || $customerEmail === '' || $billingAddr === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name, email, and address are required']);
    exit;
}

$payload = array_filter([
    'customer_name'    => $customerName,
    'customer_email'   => $customerEmail,
    'customer_phone'   => $customerPhone ?: null,
    'billing_address'  => $billingAddr,
    'shipping_address' => $shippingAddr !== '' ? $shippingAddr : $billingAddr,
    'payment_method'   => null,
    'notes'            => $notes ?: null,
], fn($v) => $v !== null);

$result = $apiClient->postWithAuth('orders', $payload, $token);

if ($result === null) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'Could not create order. Please try again.']);
    exit;
}

echo json_encode($result);
