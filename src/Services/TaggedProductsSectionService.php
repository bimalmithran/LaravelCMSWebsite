<?php
namespace App\Services;

class TaggedProductsSectionService
{
    private StorefrontService $storefront;
    private ProductCardService $productCardService;

    public function __construct(
        StorefrontService $storefront,
        ?ProductCardService $productCardService = null,
    ) {
        $this->storefront = $storefront;
        $this->productCardService =
            $productCardService ?? new ProductCardService($storefront);
    }

    /**
     * Build everything a tagged-products section needs for its first render.
     *
     * The component stays dumb: it receives prepared categories, prepared cards,
     * and pagination metadata instead of having to know how storefront queries
     * are composed.
     *
     * @return array<string, mixed>
     */
    public function buildSectionData(
        ?string $tagSlug,
        bool $enableCategoryFilter,
        int $perPage = 10,
        int $page = 1,
        ?int $requestedCategoryId = null,
    ): array {
        $categories = $enableCategoryFilter
            ? $this->getCategoriesForTag($tagSlug)
            : [];
        $selectedCategoryId = $enableCategoryFilter
            ? $this->resolveSelectedCategoryId($categories, $requestedCategoryId)
            : null;

        // Products and pagination always come from the same paginated endpoint
        // so the initial render and AJAX follow-up requests stay in sync.
        $productResponse = $this->storefront->getTaggedProducts(
            $tagSlug,
            $selectedCategoryId,
            $perPage,
            $page,
        );

        return [
            "categories" => $categories,
            "selected_category_id" => $selectedCategoryId,
            "products" => $this->productCardService->mapProducts(
                $this->extractProducts($productResponse),
            ),
            "pagination" => $this->extractPagination($productResponse, $page),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCategoriesForTag(?string $tagSlug): array
    {
        $categories = $this->storefront->getCategories($tagSlug, true);

        return is_array($categories) ? $categories : [];
    }

    /**
     * @param array<int, array<string, mixed>> $categories
     */
    public function resolveSelectedCategoryId(
        array $categories,
        ?int $requestedCategoryId,
    ): ?int {
        if ($categories === []) {
            return null;
        }

        // Only allow category ids that exist inside the tag-filtered category set.
        $availableCategoryIds = array_map(
            static fn(array $category): int => (int) ($category["id"] ?? 0),
            $categories,
        );

        if (
            $requestedCategoryId !== null &&
            in_array($requestedCategoryId, $availableCategoryIds, true)
        ) {
            return $requestedCategoryId;
        }

        return (int) ($categories[0]["id"] ?? 0);
    }

    /**
     * @param array<string, mixed> $response
     * @return array<int, array<string, mixed>>
     */
    private function extractProducts(array $response): array
    {
        $products = $response["data"] ?? [];

        return is_array($products) ? $products : [];
    }

    /**
     * @param array<string, mixed> $response
     * @return array<string, int|bool|null>
     */
    private function extractPagination(array $response, int $fallbackPage): array
    {
        $currentPage = max(1, (int) ($response["current_page"] ?? $fallbackPage));
        $lastPage = max(1, (int) ($response["last_page"] ?? $currentPage));
        $hasMore = $currentPage < $lastPage;

        return [
            "current_page" => $currentPage,
            "last_page" => $lastPage,
            "has_more" => $hasMore,
            "next_page" => $hasMore ? $currentPage + 1 : null,
        ];
    }
}
