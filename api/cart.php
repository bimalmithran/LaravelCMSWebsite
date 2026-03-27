<?php
/**
 * AJAX endpoint for cart operations.
 * All operations require an authenticated session (customer_token).
 *
 * GET              → return current cart
 * POST action=add  → add item (product_id, quantity)
 * POST action=update → update quantity (product_id, quantity)
 * POST action=remove → remove item (product_id)
 * POST action=clear  → clear cart
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
    $data = $cartService->getCart($token);
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $body['action'] ?? ($_POST['action'] ?? '');

    switch ($action) {
        case 'add':
            $productId = (int) ($body['product_id'] ?? 0);
            $quantity  = (int) ($body['quantity']   ?? 1);
            if ($productId <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Invalid product']);
                exit;
            }
            $res = $cartService->addItem($token, $productId, $quantity);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to add item']);
            break;

        case 'update':
            $productId = (int) ($body['product_id'] ?? 0);
            $quantity  = (int) ($body['quantity']   ?? 1);
            if ($productId <= 0 || $quantity < 1) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }
            $res = $cartService->updateItem($token, $productId, $quantity);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to update cart']);
            break;

        case 'remove':
            $productId = (int) ($body['product_id'] ?? 0);
            if ($productId <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Invalid product']);
                exit;
            }
            $res = $cartService->removeItem($token, $productId);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to remove item']);
            break;

        case 'clear':
            $res = $cartService->clearCart($token);
            echo json_encode($res ?? ['success' => false, 'message' => 'Failed to clear cart']);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
