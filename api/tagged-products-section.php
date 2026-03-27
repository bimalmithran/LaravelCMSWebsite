<?php
require_once dirname(__DIR__) . "/bootstrap.php";

$productCardService = new \App\Services\ProductCardService($storefront, currencySymbol: $currencySymbol);
$taggedProductsSectionService = new \App\Services\TaggedProductsSectionService(
    $storefront,
    $productCardService,
);
$tagSlug = trim((string) ($_GET["tag"] ?? ""));
$includeCategories = filter_var(
    $_GET["include_categories"] ?? false,
    FILTER_VALIDATE_BOOLEAN,
);
$sectionId = trim((string) ($_GET["section_id"] ?? "tagged-products-section"));
$perPage = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$requestedCategoryId = filter_input(
    INPUT_GET,
    "category_id",
    FILTER_VALIDATE_INT,
);

$sectionData = $taggedProductsSectionService->buildSectionData(
    $tagSlug !== "" ? $tagSlug : null,
    $includeCategories,
    $perPage !== false && $perPage !== null ? (int) $perPage : 10,
    $page !== false && $page !== null ? (int) $page : 1,
    $requestedCategoryId !== false && $requestedCategoryId !== null
        ? (int) $requestedCategoryId
        : null,
);
$categories = $sectionData["categories"];
$selectedCategoryId = $sectionData["selected_category_id"];
$products = $sectionData["products"];
$pagination = $sectionData["pagination"];
$productCardTemplate = dirname(__DIR__) . "/templates/components/product-card.php";
$productSliderTemplate =
    dirname(__DIR__) . "/templates/components/product-slider.php";
$categoryTabsTemplate =
    dirname(__DIR__) . "/templates/components/tagged-products-category-tabs.php";

ob_start();
require $productSliderTemplate;
$productsHtml = ob_get_clean();

$categoriesHtml = "";
if ($includeCategories) {
    ob_start();
    require $categoryTabsTemplate;
    $categoriesHtml = ob_get_clean();
}

header("Content-Type: application/json");

echo json_encode([
    "success" => true,
    "data" => [
        "categories_html" => $categoriesHtml,
        "products_html" => $productsHtml,
        "selected_category_id" => $selectedCategoryId,
        "current_page" => $pagination["current_page"],
        "has_more" => $pagination["has_more"],
        "next_page" => $pagination["next_page"],
    ],
]);
