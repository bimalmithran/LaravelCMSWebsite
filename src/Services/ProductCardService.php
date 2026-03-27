<?php
namespace App\Services;

class ProductCardService
{
    private StorefrontService $storefront;
    private string $fallbackProductImage;

    public function __construct(
        StorefrontService $storefront,
        string $fallbackProductImage = "assets/images/product/medium-size/1-1.jpg",
        private string $currencySymbol = "₹",
    ) {
        $this->storefront = $storefront;
        $this->fallbackProductImage = $fallbackProductImage;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getSliderItemsForTag(
        string $tagSlug,
        int $perPage = 10,
        int $page = 1,
        ?int $categoryId = null,
    ): array {
        $response = $this->storefront->getTaggedProducts(
            $tagSlug,
            $categoryId,
            $perPage,
            $page,
        );
        $products = $response["data"] ?? [];

        if (!is_array($products)) {
            return [];
        }

        return $this->mapProducts($products);
    }

    /**
     * @param array<int, array<string, mixed>> $products
     * @return array<int, array<string, mixed>>
     */
    public function mapProducts(array $products): array
    {
        return array_map(
            fn(array $product): array => $this->mapProduct($product),
            $products,
        );
    }

    /**
     * @param array<string, mixed> $product
     * @return array<string, mixed>
     */
    public function mapProduct(array $product): array
    {
        $name = (string) ($product["name"] ?? "Product");
        $price = (float) ($product["price"] ?? 0);
        $discountPrice = isset($product["discount_price"]) &&
            $product["discount_price"] !== null
            ? (float) $product["discount_price"]
            : null;
        $isOnSale = $discountPrice !== null &&
            $discountPrice > 0 &&
            $discountPrice < $price;
        $displayPrice = $isOnSale ? $discountPrice : $price;
        $rating = max(
            0,
            min(5, (int) round((float) ($product["rating_avg"] ?? 0))),
        );

        return [
            "id" => (int) ($product["id"] ?? 0),
            "name" => $name,
            "link" => "single-product.html",
            "primary_image" => $this->resolveImage($product),
            "secondary_image" => $this->resolveImage($product, 1),
            "badge_class" => $isOnSale ? "sticker-2" : "sticker",
            "badge_label" => $isOnSale ? "Sale" : "New",
            "display_price" => $this->formatCurrency($displayPrice),
            "old_price" => $isOnSale ? $this->formatCurrency($price) : null,
            "rating" => $rating,
        ];
    }

    /**
     * @param array<string, mixed> $product
     */
    private function resolveImage(array $product, int $galleryIndex = 0): string
    {
        $gallery = $product["gallery"] ?? [];
        $gallery = is_array($gallery) ? array_values($gallery) : [];
        $primaryImage = $product["image"] ?? ($gallery[0] ?? $this->fallbackProductImage);

        if ($galleryIndex === 0) {
            return is_string($primaryImage) && $primaryImage !== ""
                ? $this->storefront->resolveAssetUrl($primaryImage)
                : $this->fallbackProductImage;
        }

        $secondaryImage = $gallery[$galleryIndex] ?? $primaryImage ?? $this->fallbackProductImage;

        return is_string($secondaryImage) && $secondaryImage !== ""
            ? $this->storefront->resolveAssetUrl($secondaryImage)
            : $this->fallbackProductImage;
    }

    private function formatCurrency(float $amount): string
    {
        return $this->currencySymbol . number_format($amount, 2);
    }
}
