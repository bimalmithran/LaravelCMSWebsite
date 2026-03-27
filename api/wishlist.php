<?php
/**
 * AJAX endpoint for wishlist operations.
 * All operations require an authenticated session (customer_token).
 *
 * GET              → return current wishlist
 * POST action=add  → add item (product_id)
 * POST action=remove → remove item (product_id)
 */

require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

if (empty($_SESSION['customer_token'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$token = $_SESSION['customer_token'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $wishlistService->getWishlist($token);
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $body['action'] ?? '';

    switch ($action) {
        case 'add':
            $productId = (int) ($body['product_id'] ?? 0);
            if ($productId <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Invalid product']);
                exit;
            }
            $res = $wishlistService->addItem($token, $productId);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to add to wishlist']);
            break;

        case 'remove':
            $productId = (int) ($body['product_id'] ?? 0);
            if ($productId <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Invalid product']);
                exit;
            }
            $res = $wishlistService->removeItem($token, $productId);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to remove from wishlist']);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
