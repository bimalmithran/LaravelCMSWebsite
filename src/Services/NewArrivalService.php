<?php
namespace App\Services;

class NewArrivalService
{
    private ProductCardService $productCardService;

    public function __construct(
        StorefrontService $storefront,
        string $fallbackProductImage = "assets/images/product/medium-size/1-1.jpg",
    ) {
        $this->productCardService = new ProductCardService(
            $storefront,
            $fallbackProductImage,
        );
    }

    /**
     * Fetch and normalize the first page of new arrivals for the homepage slider.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSliderItems(int $perPage = 10, int $page = 1): array
    {
        return $this->productCardService->getSliderItemsForTag(
            "new-arrivals",
            $perPage,
            $page,
        );
    }
}
