<?php
require_once __DIR__ . "/bootstrap.php";

// CMS-driven homepage content.
$heroBanners = $storefront->getBanners("homepage_hero");
$featuredPromoBanner = $storefront->getBanner("homepage_featured_banner");
$dualBanners = $storefront->getBanners("homepage_dual_banners");
$tripleBanners = $storefront->getBanners("homepage_triple_banners");
$storeHighlights = array_slice($storefront->getStoreHighlights(), 0, 4);

// Shared card + tagged-listing services used by multiple homepage sections.
$productCardService = new \App\Services\ProductCardService($storefront, currencySymbol: $currencySymbol);
$taggedProductsSectionService = new \App\Services\TaggedProductsSectionService(
    $storefront,
    $productCardService,
);

// New Arrival section config.
$newArrivalSection = [
    "sectionId" => "new-arrivals",
    "title" => "New Arrival",
    "tagSlug" => "new-arrivals",
    "enableCategoryFilter" => false,
    "perPage" => 10,
];

// New Arrival section data.
$newArrivalSectionData = $taggedProductsSectionService->buildSectionData(
    $newArrivalSection["tagSlug"],
    $newArrivalSection["enableCategoryFilter"],
    $newArrivalSection["perPage"],
);

// New Products section config.
$newProductsSection = [
    "sectionId" => "new-products",
    "title" => "New Products",
    "tagSlug" => "new-products",
    "enableCategoryFilter" => true,
    "perPage" => 10,
];

// New Products section data.
$newProductsSectionData = $taggedProductsSectionService->buildSectionData(
    $newProductsSection["tagSlug"],
    $newProductsSection["enableCategoryFilter"],
    $newProductsSection["perPage"],
);

// Homepage all-products section config.
$allProductsSection = [
    "sectionId" => "all-products-home",
    "title" => "",
    "tagSlug" => "",
    "enableCategoryFilter" => true,
    "perPage" => 10,
    "wrapperClass" => "hiraola-product-tab_area-3",
    "sliderClass" => "hiraola-product-tab_slider-3",
];

// Homepage all-products section data.
$allProductsSectionData = $taggedProductsSectionService->buildSectionData(
    null,
    $allProductsSection["enableCategoryFilter"],
    $allProductsSection["perPage"],
);

// Trending Products section config.
$trendingProductsSection = [
    "sectionId" => "trending-products",
    "title" => "Trending Products",
    "tagSlug" => "trending-products",
    "enableCategoryFilter" => true,
    "perPage" => 10,
    "wrapperClass" => "hiraola-product-tab_area-4",
    "sliderClass" => "hiraola-product-tab_slider-2",
];

// Trending Products section data.
$trendingProductsSectionData = $taggedProductsSectionService->buildSectionData(
    $trendingProductsSection["tagSlug"],
    $trendingProductsSection["enableCategoryFilter"],
    $trendingProductsSection["perPage"],
);

// Shared partials used across the homepage.
$heroBannerSlideTemplate =
    __DIR__ . "/templates/components/hero-banner-slide.php";
$featuredPromoBannerTemplate =
    __DIR__ . "/templates/components/featured-promo-banner.php";
$storeHighlightCardTemplate =
    __DIR__ . "/templates/components/store-highlight-card.php";
$productCardTemplate = __DIR__ . "/templates/components/product-card.php";
$productSliderTemplate = __DIR__ . "/templates/components/product-slider.php";
$taggedProductsSectionTemplate =
    __DIR__ . "/templates/components/tagged-products-section.php";
$bannerGridTemplate = __DIR__ . "/templates/components/banner-grid.php";

$pageTitle = "Home || TT Devassy Jewellery";
$breadcrumb = "Home";

require_once __DIR__ . "/templates/header-home.php";
?>

<div class="hiraola-slider_area-2">
    <div class="main-slider">
        <?php foreach ($heroBanners as $index => $banner): ?>
            <?php $heroBanner = $banner; ?>
            <?php $heroBannerIndex = $index; ?>
            <?php require $heroBannerSlideTemplate; ?>
        <?php endforeach; ?>
    </div>
</div>
<script>
(function () {
    function applyResponsiveSlideBg() {
        var w = window.innerWidth;
        document.querySelectorAll('.dynamic-slide-bg[data-bg-desktop]').forEach(function (el) {
            var desktop = el.dataset.bgDesktop;
            var tablet  = el.dataset.bgTablet  || '';
            var mobile  = el.dataset.bgMobile  || '';
            var chosen  = desktop;
            if (w <= 767 && mobile)  chosen = mobile;
            else if (w <= 1199 && tablet) chosen = tablet;
            el.style.backgroundImage = "url('" + chosen + "')";
        });
    }
    applyResponsiveSlideBg();
    window.addEventListener('resize', applyResponsiveSlideBg);
})();
</script>

<!-- Begin Hiraola's Shipping Area Two -->
<?php if (!empty($storeHighlights)): ?>
    <div class="hiraola-shipping_area hiraola-shipping_area-2">
        <div class="container">
            <div class="shipping-nav">
                <div class="row">
                    <?php foreach ($storeHighlights as $highlight): ?>
                        <?php $storeHighlightCard = $highlight; ?>
                        <?php require $storeHighlightCardTemplate; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Hiraola's Shipping Area Two End Here -->

<?php
// Reuse the generic tagged-products section for the New Arrival tag.
$taggedProductsSection = $newArrivalSection;
$taggedProductsSectionData = $newArrivalSectionData;
require $taggedProductsSectionTemplate;
?>

<?php require $featuredPromoBannerTemplate; ?>

<?php
// Reuse the same component again for the category-filtered New Products tag.
$taggedProductsSection = $newProductsSection;
$taggedProductsSectionData = $newProductsSectionData;
require $taggedProductsSectionTemplate;
?>

<?php
$bannerGridBanners = $dualBanners;
$bannerGridSectionClass = "hiraola-banner_area-2";
$bannerGridColClass = "col-lg-6";
require $bannerGridTemplate;
?>

<?php
// Reuse the tagged-products component for the homepage all-products section.
$taggedProductsSection = $allProductsSection;
$taggedProductsSectionData = $allProductsSectionData;
require $taggedProductsSectionTemplate;
?>

<?php
$bannerGridBanners = $tripleBanners;
$bannerGridSectionClass = "hiraola-banner_area-3";
$bannerGridColClass = "col-lg-4";
require $bannerGridTemplate;
?>


<?php
// Reuse the same component for the category-filtered Trending Products tag.
$taggedProductsSection = $trendingProductsSection;
$taggedProductsSectionData = $trendingProductsSectionData;
require $taggedProductsSectionTemplate;
?>



<?php require_once __DIR__ . "/templates/footer.php"; ?>
