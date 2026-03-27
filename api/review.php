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
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a review.']);
    exit;
}

$token     = $_SESSION['customer_token'];
$body      = json_decode(file_get_contents('php://input'), true);
$productId = (int) ($body['product_id'] ?? 0);
$rating    = (int) ($body['rating']     ?? 0);
$comment   = trim((string) ($body['comment'] ?? ''));

if ($productId <= 0 || $rating < 1 || $rating > 5) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid product and rating (1–5) are required.']);
    exit;
}

$result = $apiClient->postWithAuth(
    "products/{$productId}/reviews",
    ['rating' => $rating, 'comment' => $comment ?: null],
    $token
);

if ($result === null) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'Could not submit review. Please try again.']);
    exit;
}

echo json_encode($result);
