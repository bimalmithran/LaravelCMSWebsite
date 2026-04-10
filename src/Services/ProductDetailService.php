<?php

namespace App\Services;

/**
 * Assembles the complete view-model for the single product detail page.
 *
 * Single Responsibility: fetch + format product data for the detail view only.
 * Dependency Inversion: depends on StorefrontService and ProductCardService abstractions.
 */
class ProductDetailService
{
    private const FALLBACK_IMAGE = 'assets/images/product/large-size/1.jpg';

    public function __construct(
        private StorefrontService  $storefront,
        private ProductCardService $productCard,
        private string             $currencySymbol = '₹',
    ) {}

    /**
     * Build the full view-model for a product detail page.
     *
     * Returns null when the product doesn't exist or is inactive.
     *
     * @param int|string $identifier
     * @return array<string, mixed>|null
     */
    public function getProductData($identifier, bool $isSlug = false): ?array
    {
        $product = $isSlug
            ? $this->storefront->getProductBySlug((string) $identifier)
            : $this->storefront->getProduct((int) $identifier);
            
        if ($product === null) {
            return null;
        }

        $price         = (float) ($product['price'] ?? 0);
        $discountPrice = isset($product['discount_price']) && $product['discount_price'] !== null
            ? (float) $product['discount_price']
            : null;
        $isOnSale     = $discountPrice !== null && $discountPrice > 0 && $discountPrice < $price;
        $displayPrice = $isOnSale ? $discountPrice : $price;

        return [
            'id'                => (int) ($product['id'] ?? 0),
            'name'              => (string) ($product['name'] ?? ''),
            'slug'              => (string) ($product['slug'] ?? ''),
            'sku'               => (string) ($product['sku'] ?? ''),
            'short_description' => (string) ($product['short_description'] ?? ''),
            'description'       => (string) ($product['description'] ?? ''),
            'stock'             => (int) ($product['stock'] ?? 0),
            'rating'            => max(0, min(5, (int) round((float) ($product['rating_avg'] ?? 0)))),
            'rating_count'      => (int) ($product['rating_count'] ?? 0),
            'display_price'     => $this->currencySymbol . number_format($displayPrice, 2),
            'raw_price'         => $displayPrice,
            'old_price'         => $isOnSale ? $this->currencySymbol . number_format($price, 2) : null,
            'images'            => $this->resolveImages($product),
            'sizes'             => $this->extractSizes($product),
            'tags'              => $this->extractTags($product),
            'brand'             => (string) ($product['brand']['name'] ?? ''),
            'category'          => (string) ($product['category']['name'] ?? ''),
            'specs'             => $this->extractSpecs($product),
            'reviews'           => $this->extractReviews($product),
            'related_products'  => $this->productCard->mapProducts(
                $this->storefront->getRelatedProducts((int) ($product['id'] ?? 0), 6)
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $product
     * @return string[]
     */
    private function resolveImages(array $product): array
    {
        $gallery      = is_array($product['gallery'] ?? null) ? array_values($product['gallery']) : [];
        $primaryImage = $product['image'] ?? ($gallery[0] ?? null);
        $allImages    = $primaryImage !== null
            ? array_values(array_unique(array_merge([$primaryImage], $gallery)))
            : $gallery;

        $resolved = array_values(array_map(
            fn(string $img): string => $this->storefront->resolveAssetUrl($img),
            array_filter($allImages, fn($img): bool => is_string($img) && $img !== ''),
        ));

        return $resolved ?: [self::FALLBACK_IMAGE];
    }

    /**
     * @param  array<string, mixed>  $product
     * @return array<int, array<string, mixed>>
     */
    private function extractSizes(array $product): array
    {
        return array_map(fn(array $s): array => [
            'id'    => (int) ($s['id'] ?? 0),
            'name'  => (string) ($s['name'] ?? ''),
            'stock' => (int) ($s['pivot']['stock'] ?? $s['stock'] ?? 0),
        ], $product['sizes'] ?? []);
    }

    /**
     * @param  array<string, mixed>  $product
     * @return string[]
     */
    private function extractTags(array $product): array
    {
        return array_values(array_filter(
            array_map(fn($t): string => (string) ($t['name'] ?? ''), $product['tags'] ?? []),
        ));
    }

    /**
     * Extract specs from whichever spec type the product has.
     *
     * @param  array<string, mixed>  $product
     * @return array<string, string>
     */
    private function extractSpecs(array $product): array
    {
        if (!empty($product['jewelry_spec'])) {
            $s = $product['jewelry_spec'];
            return array_filter([
                'Metal Type' => (string) ($s['metal_type'] ?? ''),
                'Purity'     => (string) ($s['purity'] ?? ''),
                'Weight'     => isset($s['gross_weight']) ? $s['gross_weight'] . 'g' : '',
                'HUID'       => (string) ($s['huid'] ?? ''),
            ], fn(string $v): bool => $v !== '');
        }

        if (!empty($product['watch_spec'])) {
            $s = $product['watch_spec'];
            return array_filter([
                'Movement'         => (string) ($s['movement'] ?? ''),
                'Dial Color'       => (string) ($s['dial_color'] ?? ''),
                'Strap'            => (string) ($s['strap_material'] ?? ''),
                'Water Resistance' => isset($s['water_resistance']) ? $s['water_resistance'] . 'm' : '',
            ], fn(string $v): bool => $v !== '');
        }

        if (!empty($product['diamond_spec'])) {
            $s = $product['diamond_spec'];
            return array_filter([
                'Clarity' => (string) ($s['clarity'] ?? ''),
                'Color'   => (string) ($s['color'] ?? ''),
                'Cut'     => (string) ($s['cut'] ?? ''),
                'Setting' => (string) ($s['setting'] ?? ''),
                'Count'   => isset($s['diamond_count']) ? (string) $s['diamond_count'] : '',
            ], fn(string $v): bool => $v !== '');
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $product
     * @return array<int, array<string, mixed>>
     */
    private function extractReviews(array $product): array
    {
        $approved = array_filter(
            $product['reviews'] ?? [],
            fn($r): bool => is_array($r) && (bool) ($r['is_approved'] ?? false),
        );

        return array_values(array_map(fn(array $r): array => [
            'author'  => (string) ($r['customer']['first_name'] ?? $r['user']['name'] ?? 'Customer'),
            'comment' => (string) ($r['comment'] ?? ''),
            'rating'  => max(0, min(5, (int) ($r['rating'] ?? 0))),
            'date'    => (string) ($r['created_at'] ?? ''),
        ], $approved));
    }
}
