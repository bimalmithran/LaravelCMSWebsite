<?php
require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body    = json_decode(file_get_contents('php://input'), true);
$name    = trim((string) ($body['name']    ?? ''));
$email   = trim((string) ($body['email']   ?? ''));
$subject = trim((string) ($body['subject'] ?? ''));
$message = trim((string) ($body['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name, email, and message are required.']);
    exit;
}

$result = $apiClient->post('contact', [
    'name'    => $name,
    'email'   => $email,
    'subject' => $subject ?: null,
    'message' => $message,
]);

if ($result === null) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'Could not send message. Please try again.']);
    exit;
}

echo json_encode($result);
