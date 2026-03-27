<?php
require_once __DIR__ . '/bootstrap.php';

$shopService = new \App\Services\ShopService($storefront, new \App\Services\ProductCardService($storefront, 'assets/images/product/medium-size/1-1.jpg', $currencySymbol));
$shopData    = $shopService->getShopData($_GET);
$sidebarBanner = $storefront->getBanner('shop_sidebar');

$search      = $shopData['search'];
$categoryId  = $shopData['category_id'];
$sort        = $shopData['sort'];
$products    = $shopData['products'];
$pagination  = $shopData['pagination'];
$categories  = $shopData['categories'];

// Build base query params for pagination / sidebar links (excluding page)
$queryParams = array_filter([
    'q'        => $search ?: null,
    'category' => $categoryId,
    'sort'     => $sort ?: null,
], fn($v) => $v !== null && $v !== '');

$pageTitle  = $search
    ? 'Search: ' . htmlspecialchars($search) . ' || TT Devassy Jewellery'
    : 'Shop || TT Devassy Jewellery';
$breadcrumb = $search ? 'Search results for "' . htmlspecialchars($search) . '"' : 'Shop';

require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2><?= $search ? 'Search Results' : 'Shop' ?></h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active"><?= htmlspecialchars($breadcrumb) ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="hiraola-content_wrapper">
    <div class="container">
        <div class="row">

            <!-- Sidebar (left on desktop, below on mobile) -->
            <div class="col-lg-3 order-2 order-lg-1">
                <?php
                // Variables the sidebar component expects
                $selectedCategoryId = $categoryId;
                $searchQuery        = $search;
                require __DIR__ . '/templates/components/shop-sidebar.php';
                ?>
                <?php if (!empty($sidebarBanner['image_url'])): ?>
                <div class="sidebar-banner_area">
                    <div class="banner-item img-hover_effect">
                        <a href="<?= htmlspecialchars($sidebarBanner['action_url'] ?? 'shop.php', ENT_QUOTES) ?>">
                            <img
                                src="<?= htmlspecialchars($sidebarBanner['image_url'], ENT_QUOTES) ?>"
                                alt="<?= htmlspecialchars($sidebarBanner['title'] ?? 'Shop Banner', ENT_QUOTES) ?>"
                            >
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Products grid (right/main column) -->
            <div class="col-lg-9 order-1 order-lg-2">

                <!-- Toolbar: view toggle + sort -->
                <div class="shop-toolbar">
                    <div class="product-view-mode">
                        <a class="active grid-3" data-target="gridview-3"
                           data-bs-toggle="tooltip" data-placement="top" title="Grid View">
                            <i class="fa fa-th"></i>
                        </a>
                        <a class="list" data-target="listview"
                           data-bs-toggle="tooltip" data-placement="top" title="List View">
                            <i class="fa fa-th-list"></i>
                        </a>
                    </div>
                    <div class="product-item-selection_area">
                        <div class="product-short">
                            <label class="select-label">Sort By:</label>
                            <select class="nice-select shop-sort-select">
                                <option value="" <?= $sort === '' ? 'selected' : '' ?>>Newest</option>
                                <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="name_asc"   <?= $sort === 'name_asc'   ? 'selected' : '' ?>>Name: A to Z</option>
                                <option value="name_desc"  <?= $sort === 'name_desc'  ? 'selected' : '' ?>>Name: Z to A</option>
                                <option value="rating"     <?= $sort === 'rating'     ? 'selected' : '' ?>>Rating (Highest)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php if (!empty($search) && empty($products)): ?>
                <!-- No results -->
                <div class="row">
                    <div class="col-12 text-center" style="padding: 60px 0;">
                        <h4>No products found for "<?= htmlspecialchars($search) ?>"</h4>
                        <p>Try a different search term or <a href="shop.php">browse all products</a>.</p>
                    </div>
                </div>
                <?php else: ?>

                <!-- Product Grid -->
                <div class="shop-product-wrap grid gridview-3 row">
                    <?php foreach ($products as $productCard): ?>
                    <div class="col-lg-4">
                        <?php require __DIR__ . '/templates/components/product-card.php'; ?>
                        <?php require __DIR__ . '/templates/components/product-list-item.php'; ?>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                    <div class="col-12 text-center" style="padding: 60px 0;">
                        <h4>No products found.</h4>
                        <p><a href="shop.php">Browse all products</a></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php require __DIR__ . '/templates/components/pagination.php'; ?>

                <?php endif; ?>

            </div><!-- /.col-lg-9 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.hiraola-content_wrapper -->

<!-- Quick View Modal -->
<?php require_once __DIR__ . '/templates/components/quick-view-modal.php'; ?>

<?php require_once __DIR__ . '/templates/footer.php'; ?>

<script>
// Sort select → reload page with new sort param
document.querySelector('.shop-sort-select')?.addEventListener('change', function () {
    const params = new URLSearchParams(window.location.search);
    if (this.value) {
        params.set('sort', this.value);
    } else {
        params.delete('sort');
    }
    params.delete('page');
    window.location.href = 'shop.php?' + params.toString();
});
</script>
