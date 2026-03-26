<?php
namespace App\Services;

use App\Http\ApiClientInterface;

class StorefrontService
{
    private ApiClientInterface $api;

    public function __construct(ApiClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Ping the API to check if it's reachable.
     * @return bool True if the API is reachable, false otherwise
     */
    public function ping(): bool
    {
        $response = $this->api->get("/ping");
        return isset($response["success"]) && $response["success"] === true;
    }

    /**
     * Fetch banners by their placement identifier.
     * @param string $placement (e.g., 'homepage_hero', 'sidebar_ad')
     * @return array The API response array
     */
    public function getBanners(string $placement): array
    {
        // This will make a request to: /api/v1/banners?placement=homepage_hero
        $response = $this->api->get("/banners", ["placement" => $placement]);

        return $response;
    }

    /**
     * Fetch the first active banner for a placement.
     *
     * The backend already sorts banners by sort order, so taking the first item
     * allows admin users to control which banner is shown without additional
     * storefront rules.
     *
     * @param string $placement
     * @return array<string, mixed>|null
     */
    public function getBanner(string $placement): ?array
    {
        $banners = $this->getBanners($placement);

        if ($banners === []) {
            return null;
        }

        $banner = $banners[0] ?? null;

        return is_array($banner) ? $banner : null;
    }

    /**
     * Fetch menus from the API.
     * @return array The API response array
     */
    public function getMenus(): array
    {
        $response = $this->api->get("/menus");

        return $response;
    }

    /**
     * Fetch store highlights from the API.
     * @return array The API response array
     */
    public function getStoreHighlights(): array
    {
        $response = $this->api->get("/store-highlights");

        return $response;
    }

    /**
     * Fetch active product categories from the API.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCategories(
        ?string $tagSlug = null,
        bool $onlyWithProducts = false,
    ): array
    {
        $query = [];

        if ($tagSlug !== null && $tagSlug !== "") {
            $query["tag"] = $tagSlug;
        }

        if ($onlyWithProducts) {
            $query["has_products"] = 1;
        }

        $response = $this->api->get("/categories", $query);

        return is_array($response) ? $response : [];
    }

    /**
     * Fetch the newest active products using the paginated products API.
     *
     * @param int $perPage Number of products to request in the first batch
     * @param int $page Page number to request
     * @return array Paginated product response payload
     */
    public function getNewArrivals(int $perPage = 10, int $page = 1): array
    {
        $response = $this->getTaggedProducts("new-arrivals", null, $perPage, $page);

        return $response;
    }

    /**
     * Fetch active tagged products with optional category filtering.
     *
     * @return array<string, mixed>
     */
    public function getTaggedProducts(
        ?string $tagSlug = null,
        ?int $categoryId = null,
        int $perPage = 10,
        int $page = 1,
    ): array {
        $query = [
            "page" => max(1, $page),
            "per_page" => max(1, min(100, $perPage)),
            "sort_by" => "created_at",
            "sort_order" => "desc",
        ];

        if ($tagSlug !== null && trim($tagSlug) !== "") {
            $query["tag"] = $tagSlug;
        }

        if ($categoryId !== null && $categoryId > 0) {
            $query["category_id"] = $categoryId;
        }

        $response = $this->api->get("/products", $query);

        return is_array($response) ? $response : [];
    }

    public function resolveAssetUrl(?string $path): string
    {
        return $this->api->resolveUrl($path);
    }
}
