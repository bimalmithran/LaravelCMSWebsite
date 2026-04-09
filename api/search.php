<?php
/**
 * AJAX live-search endpoint.
 *
 * GET ?q=search_term&limit=10
 * Returns: { success: bool, data: [ { id, name, price, image, link }, ... ] }
 */

declare(strict_types=1);

header('Content-Type: application/json');

require_once dirname(__DIR__) . '/bootstrap.php';

$q     = trim((string) ($_GET['q'] ?? ''));
$limit = max(1, min(20, (int) ($_GET['limit'] ?? 10)));

if ($q === '') {
    echo json_encode(['success' => true, 'data' => []]);
    exit;
}

$results = $storefront->searchProducts($q, $limit);

// Map to a minimal shape for the autocomplete dropdown
$mapped = array_map(function (array $p) use ($storefront, $currencySymbol): array {
    $price         = (float) ($p['price'] ?? 0);
    $discountPrice = isset($p['discount_price']) && $p['discount_price'] !== null
        ? (float) $p['discount_price']
        : null;
    $displayPrice = ($discountPrice !== null && $discountPrice > 0 && $discountPrice < $price)
        ? $discountPrice
        : $price;

    $gallery = is_array($p['gallery'] ?? null) ? array_values($p['gallery']) : [];
    $rawImg  = $p['image'] ?? ($gallery[0] ?? null);
    $image   = is_string($rawImg) && $rawImg !== ''
        ? $storefront->resolveAssetUrl($rawImg)
        : 'assets/images/product/medium-size/1-1.jpg';

    return [
        'id'    => (int) ($p['id'] ?? 0),
        'name'  => (string) ($p['name'] ?? ''),
        'price' => $currencySymbol . number_format($displayPrice, 2),
        'image' => $image,
        'link'  => 'product.php?slug=' . urlencode((string) ($p['slug'] ?? '')),
    ];
}, $results);

echo json_encode(['success' => true, 'data' => $mapped]);
