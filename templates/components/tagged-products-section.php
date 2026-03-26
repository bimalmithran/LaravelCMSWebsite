<?php
/** @var array<string, mixed> $taggedProductsSection */
/** @var array<string, mixed> $taggedProductsSectionData */
/** @var string $productSliderTemplate */

$sectionId = trim((string) ($taggedProductsSection["sectionId"] ?? ""));
$sectionTitle = trim((string) ($taggedProductsSection["title"] ?? ""));
$sectionTagSlug = trim((string) ($taggedProductsSection["tagSlug"] ?? ""));
$enableCategoryFilter = (bool) ($taggedProductsSection["enableCategoryFilter"] ?? false);
$perPage = max(1, (int) ($taggedProductsSection["perPage"] ?? 10));
$sectionWrapperClass = trim(
    (string) ($taggedProductsSection["wrapperClass"] ?? "hiraola-product-tab_area-2"),
);
$sectionSliderClass = trim(
    (string) ($taggedProductsSection["sliderClass"] ?? "hiraola-product-tab_slider-2"),
);
$categories = $taggedProductsSectionData["categories"] ?? [];
$products = $taggedProductsSectionData["products"] ?? [];
$selectedCategoryId = $taggedProductsSectionData["selected_category_id"] ?? null;
$pagination = $taggedProductsSectionData["pagination"] ?? [
    "current_page" => 1,
    "has_more" => false,
    "next_page" => null,
];
$categoryTabsTemplate =
    __DIR__ . "/tagged-products-category-tabs.php";

if ($sectionId === "") {
    return;
}
?>
<div
    class="<?= htmlspecialchars($sectionWrapperClass) ?>"
    id="<?= htmlspecialchars($sectionId) ?>"
    data-tagged-products-section
    data-endpoint="api/tagged-products-section.php"
    data-tag="<?= htmlspecialchars($sectionTagSlug) ?>"
    data-per-page="<?= htmlspecialchars((string) $perPage) ?>"
    data-enable-category-filter="<?= $enableCategoryFilter ? "1" : "0" ?>"
    data-current-page="<?= htmlspecialchars((string) ($pagination["current_page"] ?? 1)) ?>"
    data-next-page="<?= htmlspecialchars((string) ($pagination["next_page"] ?? "")) ?>"
    data-has-more="<?= !empty($pagination["has_more"]) ? "1" : "0" ?>"
    data-selected-category-id="<?= htmlspecialchars((string) ($selectedCategoryId ?? "")) ?>"
>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-tab">
                    <?php if ($sectionTitle !== ""): ?>
                        <div class="hiraola-tab_title">
                            <h4><?= htmlspecialchars($sectionTitle) ?></h4>
                        </div>
                    <?php endif; ?>
                    <?php if ($enableCategoryFilter && !empty($categories)): ?>
                        <ul class="nav product-menu" data-tagged-products-tabs>
                            <?php require $categoryTabsTemplate; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="tab-content hiraola-tab_content">
                    <div class="tab-pane active show" role="tabpanel">
                        <div
                            class="<?= htmlspecialchars($sectionSliderClass) ?>"
                            data-tagged-products-slider
                        >
                            <?php require $productSliderTemplate; ?>
                        </div>
                        <div class="hiraola-btn-ps_center">
                            <button
                                type="button"
                                class="hiraola-btn"
                                data-tagged-products-load-more
                                data-next-page="<?= htmlspecialchars((string) ($pagination["next_page"] ?? "")) ?>"
                                <?= !empty($pagination["has_more"]) ? "" : "hidden" ?>
                            >
                                Load More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
