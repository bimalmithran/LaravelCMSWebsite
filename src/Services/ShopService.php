<?php

namespace App\Services;

/**
 * Assembles all data required by the shop / search-results listing page.
 *
 * Single Responsibility: translate raw GET params → API calls → view-ready data.
 * Open/Closed: extend by adding params; never modify StorefrontService for shop concerns.
 * Dependency Inversion: depends on StorefrontService and ProductCardService abstractions.
 */
class ShopService
{
    private const PER_PAGE = 12;

    /** Maps URL ?sort= value → [sort_by, sort_order] for the backend API */
    private const SORT_MAP = [
        'name_asc'   => ['name',       'asc'],
        'name_desc'  => ['name',       'desc'],
        'price_asc'  => ['price',      'asc'],
        'price_desc' => ['price',      'desc'],
        'rating'     => ['rating',     'desc'],
    ];

    public function __construct(
        private StorefrontService  $storefront,
        private ProductCardService $productCard,
    ) {}

    /**
     * Build the complete view-model for the shop/search listing page.
     *
     * @param  array<string, mixed>  $params  Typically $_GET
     * @return array<string, mixed>
     */
    public function getShopData(array $params): array
    {
        $page       = max(1, (int) ($params['page'] ?? 1));
        $search     = trim((string) ($params['q'] ?? ''));
        $categoryId = isset($params['category']) && (int) $params['category'] > 0
            ? (int) $params['category']
            : null;
        $sort       = (string) ($params['sort'] ?? '');
        $minPrice   = isset($params['min_price']) && is_numeric($params['min_price'])
            ? (float) $params['min_price']
            : null;
        $maxPrice   = isset($params['max_price']) && is_numeric($params['max_price'])
            ? (float) $params['max_price']
            : null;

        [$sortBy, $sortOrder] = self::SORT_MAP[$sort] ?? ['created_at', 'desc'];

        $apiParams = array_filter([
            'page'        => $page,
            'per_page'    => self::PER_PAGE,
            'search'      => $search ?: null,
            'category_id' => $categoryId,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder,
            'min_price'   => $minPrice,
            'max_price'   => $maxPrice,
        ], fn($v) => $v !== null && $v !== '');

        $response = $this->storefront->getProducts($apiParams);
        $products = $this->productCard->mapProducts($response['data'] ?? []);

        $meta = $response['meta'] ?? [];
        $pagination = [
            'current_page' => (int) ($meta['current_page'] ?? $page),
            'last_page'    => (int) ($meta['last_page']    ?? 1),
            'total'        => (int) ($meta['total']        ?? 0),
            'per_page'     => (int) ($meta['per_page']     ?? self::PER_PAGE),
            'from'         => (int) ($meta['from']         ?? 0),
            'to'           => (int) ($meta['to']           ?? 0),
        ];

        return [
            'products'    => $products,
            'pagination'  => $pagination,
            'categories'  => $this->storefront->getCategories(null, true),
            'search'      => $search,
            'category_id' => $categoryId,
            'sort'        => $sort,
            'min_price'   => $minPrice,
            'max_price'   => $maxPrice,
        ];
    }
}
