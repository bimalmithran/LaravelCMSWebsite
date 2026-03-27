<?php
require_once __DIR__ . '/bootstrap.php';

// Require authentication
if (!$loggedIn) {
    $_SESSION['auth_redirect'] = 'wishlist.php';
    header('Location: login.php');
    exit;
}

$token        = $_SESSION['customer_token'];
$wishlistData = $wishlistService->getWishlist($token);
$items        = $wishlistData['items'] ?? [];

$pageTitle = 'Wishlist || TT Devassy Jewellery';
require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Wishlist</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active">Wishlist</li>
            </ul>
        </div>
    </div>
</div>

<!-- Wishlist Area -->
<div class="hiraola-wishlist_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (empty($items)): ?>
                <div class="text-center" style="padding: 60px 0;">
                    <i class="ion-android-favorite-outline" style="font-size:60px;color:#ccc;"></i>
                    <h4 style="margin-top:20px;color:#555;">Your wishlist is empty</h4>
                    <a href="shop.php" class="hiraola-btn hiraola-btn_dark" style="margin-top:20px;display:inline-block;">Browse Products</a>
                </div>
                <?php else: ?>
                <div id="wishlist-alert"></div>
                <div class="table-content table-responsive">
                    <table class="table" id="wishlist-table">
                        <thead>
                            <tr>
                                <th class="hiraola-product_remove">remove</th>
                                <th class="hiraola-product-thumbnail">images</th>
                                <th class="cart-product-name">Product</th>
                                <th class="hiraola-product-price">Unit Price</th>
                                <th class="hiraola-product-stock-status">Stock Status</th>
                                <th class="hiraola-cart_btn">add to cart</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item):
                            $product   = is_array($item['product']) ? $item['product'] : (method_exists($item['product'] ?? null, 'toArray') ? $item['product']->toArray() : []);
                            $productId = (int) ($item['product_id'] ?? 0);
                            $name      = htmlspecialchars($product['name'] ?? 'Product', ENT_QUOTES);
                            $price     = (float) ($product['discount_price'] ?: ($product['price'] ?? 0));
                            $stock     = (int) ($product['stock'] ?? 0);
                            $slug      = $product['slug'] ?? '';
                            $imgPath   = $product['image'] ?? '';
                            $imgUrl    = $imgPath !== '' ? $apiClient->resolveUrl($imgPath) : 'assets/images/product/small-size/placeholder.jpg';
                        ?>
                        <tr data-product-id="<?= $productId ?>">
                            <td class="hiraola-product_remove">
                                <a href="javascript:void(0)" class="btn-remove-wishlist-item" data-product-id="<?= $productId ?>">
                                    <i class="fa fa-trash" title="Remove"></i>
                                </a>
                            </td>
                            <td class="hiraola-product-thumbnail">
                                <a href="product.php?id=<?= $productId ?>">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES) ?>" alt="<?= $name ?>" style="width:80px;height:80px;object-fit:cover;">
                                </a>
                            </td>
                            <td class="hiraola-product-name">
                                <a href="product.php?id=<?= $productId ?>"><?= $name ?></a>
                            </td>
                            <td class="hiraola-product-price">
                                <span class="amount"><?= $currencySymbol ?><?= number_format($price, 2) ?></span>
                            </td>
                            <td class="hiraola-product-stock-status">
                                <?php if ($stock > 0): ?>
                                <span class="in-stock">in stock</span>
                                <?php else: ?>
                                <span class="out-stock">out of stock</span>
                                <?php endif; ?>
                            </td>
                            <td class="hiraola-cart_btn">
                                <?php if ($stock > 0): ?>
                                <a href="javascript:void(0)"
                                   class="hiraola-btn hiraola-btn_dark hiraola-btn_sm btn-add-to-cart"
                                   data-product-id="<?= $productId ?>">
                                    Add to Cart
                                </a>
                                <?php else: ?>
                                <span style="color:#aaa;">Out of stock</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist Area End -->

<?php require_once __DIR__ . '/templates/footer.php'; ?>
