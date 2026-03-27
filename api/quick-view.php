<?php
require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$quickViewService = new \App\Services\QuickViewService($storefront, $currencySymbol);
$viewModel = $quickViewService->getProductViewModel((int) $id);

if ($viewModel === null) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

echo json_encode(['success' => true, 'data' => $viewModel]);
