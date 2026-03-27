<?php
require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim((string) ($_POST['email'] ?? ''));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid email address is required.']);
    exit;
}

$result = $apiClient->post('/newsletter/subscribe', ['email' => $email]);

if ($result === null) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'Could not complete your subscription. Please try again.']);
    exit;
}

echo json_encode($result);
