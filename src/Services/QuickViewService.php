<?php
namespace App\Services;

/**
 * Transforms a raw product API response into a view model
 * suitable for the quick view modal.
 *
 * Single Responsibility: formatting only — no HTTP, no templating.
 */
class QuickViewService
{
    public function __construct(
        private StorefrontService $storefront,
        private string $currencySymbol = "₹",
    ) {}

    /**
     * Fetch and format a product view model by ID.
     *
     * @return array<string, mixed>|null
     */
    public function getProductViewModel(int $id): ?array
    {
        $product = $this->storefront->getProduct($id);

        if ($product === null) {
            return null;
        }

        return $this->formatProduct($product);
    }

    /**
     * @param array<string, mixed> $product
     * @return array<string, mixed>
     */
    private function formatProduct(array $product): array
    {
        $price         = (float) ($product['price'] ?? 0);
        $discountPrice = isset($product['discount_price']) && $product['discount_price'] !== null
            ? (float) $product['discount_price']
            : null;
        $isOnSale     = $discountPrice !== null && $discountPrice > 0 && $discountPrice < $price;
        $displayPrice = $isOnSale ? $discountPrice : $price;

        $gallery      = is_array($product['gallery'] ?? null) ? array_values($product['gallery']) : [];
        $primaryImage = $product['image'] ?? ($gallery[0] ?? null);
        $allImages    = $primaryImage !== null
            ? array_values(array_unique(array_merge([$primaryImage], $gallery)))
            : $gallery;

        $resolvedImages = array_values(array_map(
            fn(string $img): string => $this->storefront->resolveAssetUrl($img),
            array_filter($allImages, fn($img): bool => is_string($img) && $img !== ''),
        ));

        $tags   = array_values(array_filter(
            array_map(fn($t): string => (string) ($t['name'] ?? ''), $product['tags'] ?? []),
        ));
        $rating = max(0, min(5, (int) round((float) ($product['rating_avg'] ?? 0))));

        return [
            'id'                => (int) ($product['id'] ?? 0),
            'name'              => (string) ($product['name'] ?? ''),
            'short_description' => (string) ($product['short_description'] ?? ''),
            'sku'               => (string) ($product['sku'] ?? ''),
            'stock'             => (int) ($product['stock'] ?? 0),
            'display_price'     => $this->currencySymbol . number_format($displayPrice, 2),
            'old_price'         => $isOnSale ? $this->currencySymbol . number_format($price, 2) : null,
            'rating'            => $rating,
            'images'            => $resolvedImages,
            'tags'              => $tags,
            'brand'             => (string) ($product['brand']['name'] ?? ''),
        ];
    }
}
