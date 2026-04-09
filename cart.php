<?php
require_once __DIR__ . '/bootstrap.php';

// Require authentication
if (!$loggedIn) {
    $_SESSION['auth_redirect'] = 'cart.php';
    header('Location: login.php');
    exit;
}

$token    = $_SESSION['customer_token'];
$cartData = $cartService->getCart($token);
$items    = $cartData['items'] ?? [];
$cart     = $cartData['cart']  ?? [];

$pageTitle = 'Cart || TT Devassy Jewellery';
require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Cart</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active">Cart</li>
            </ul>
        </div>
    </div>
</div>

<!-- Cart Area -->
<div class="hiraola-cart-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (empty($items)): ?>
                <div class="text-center" style="padding: 60px 0;">
                    <i class="ion-bag" style="font-size:60px;color:#ccc;"></i>
                    <h4 style="margin-top:20px;color:#555;">Your cart is empty</h4>
                    <a href="shop.php" class="hiraola-btn hiraola-btn_dark" style="margin-top:20px;display:inline-block;">Continue Shopping</a>
                </div>
                <?php else: ?>
                <div id="cart-alert"></div>
                <div class="table-content table-responsive" id="cart-table-wrap">
                    <table class="table" id="cart-table">
                        <thead>
                            <tr>
                                <th class="hiraola-product-remove">remove</th>
                                <th class="hiraola-product-thumbnail">images</th>
                                <th class="cart-product-name">Product</th>
                                <th class="hiraola-product-price">Unit Price</th>
                                <th class="hiraola-product-quantity">Quantity</th>
                                <th class="hiraola-product-subtotal">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item):
                            $product   = $item['product'] ?? [];
                            $productId = (int) ($item['product_id'] ?? 0);
                            $qty       = (int) ($item['quantity'] ?? 1);
                            $price     = (float) ($item['price'] ?? 0);
                            $lineTotal = (float) ($item['line_total'] ?? ($price * $qty));
                            $name      = htmlspecialchars($product['name'] ?? 'Product', ENT_QUOTES);
                            $slug      = $product['slug'] ?? '';
                            $imgPath   = $product['image'] ?? '';
                            $imgUrl    = $imgPath !== '' ? $apiClient->resolveUrl($imgPath) : 'assets/images/product/small-size/placeholder.jpg';
                        ?>
                        <tr data-product-id="<?= $productId ?>">
                            <td class="hiraola-product-remove">
                                <a href="javascript:void(0)" class="btn-remove-cart-item" data-product-id="<?= $productId ?>">
                                    <i class="fa fa-trash" title="Remove"></i>
                                </a>
                            </td>
                            <td class="hiraola-product-thumbnail">
                                <a href="product.php?slug=<?= urlencode($slug) ?>">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES) ?>" alt="<?= $name ?>" style="width:80px;height:80px;object-fit:cover;">
                                </a>
                            </td>
                            <td class="hiraola-product-name">
                                <a href="product.php?slug=<?= urlencode($slug) ?>"><?= $name ?></a>
                            </td>
                            <td class="hiraola-product-price">
                                <span class="amount"><?= $currencySymbol ?><?= number_format($price, 2) ?></span>
                            </td>
                            <td class="quantity">
                                <label>Quantity</label>
                                <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box cart-qty-input"
                                           value="<?= $qty ?>"
                                           type="text"
                                           min="1"
                                           data-product-id="<?= $productId ?>">
                                    <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                    <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                </div>
                            </td>
                            <td class="product-subtotal">
                                <span class="amount line-total"><?= $currencySymbol ?><?= number_format($lineTotal, 2) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row" style="margin-top:20px;">
                    <div class="col-md-5 ml-auto" id="cart-totals-wrap">
                        <div class="cart-page-total">
                            <h2>Cart totals</h2>
                            <ul>
                                <li>Subtotal <span id="cart-subtotal"><?= $currencySymbol ?><?= number_format((float)($cart['subtotal'] ?? 0), 2) ?></span></li>
                                <?php if (!empty($cart['discount']) && $cart['discount'] > 0): ?>
                                <li>Discount <span>-<?= $currencySymbol ?><?= number_format((float)$cart['discount'], 2) ?></span></li>
                                <?php endif; ?>
                                <li>Total <span id="cart-total"><?= $currencySymbol ?><?= number_format((float)($cart['total'] ?? 0), 2) ?></span></li>
                            </ul>
                            <a href="checkout.php" class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth" style="display:block;text-align:center;margin-top:15px;">Proceed to Checkout</a>
                        </div>
                    </div>
                    <div class="col-md-7" style="display:flex;align-items:flex-end;margin-bottom:20px;">
                        <a href="shop.php" class="hiraola-btn hiraola-btn_dark" style="margin-right:10px;">Continue Shopping</a>
                        <a href="javascript:void(0)" id="btn-clear-cart" class="hiraola-btn" style="background:#e74c3c;color:#fff;border-color:#e74c3c;">Clear Cart</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Cart Area End -->

<?php require_once __DIR__ . '/templates/footer.php'; ?>
