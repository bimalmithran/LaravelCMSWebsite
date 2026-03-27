<?php
require_once __DIR__ . '/bootstrap.php';

// Require authentication
if (!$loggedIn) {
    $_SESSION['auth_redirect'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

$token    = $_SESSION['customer_token'];
$cartData = $cartService->getCart($token);
$items    = $cartData['items'] ?? [];
$cart     = $cartData['cart']  ?? [];

// Redirect to cart if empty
if (empty($items)) {
    header('Location: cart.php');
    exit;
}

// Pre-fill from session customer data
$customer = $customerData ?? [];
$firstName = htmlspecialchars($customer['first_name'] ?? '', ENT_QUOTES);
$lastName  = htmlspecialchars($customer['last_name']  ?? '', ENT_QUOTES);
$email     = htmlspecialchars($customer['email']      ?? '', ENT_QUOTES);
$phone     = htmlspecialchars($customer['phone']      ?? '', ENT_QUOTES);
$address   = htmlspecialchars($customer['billing_address'] ?? '', ENT_QUOTES);
$city      = htmlspecialchars($customer['city']       ?? '', ENT_QUOTES);
$state     = htmlspecialchars($customer['state']      ?? '', ENT_QUOTES);
$postal    = htmlspecialchars($customer['postal_code'] ?? '', ENT_QUOTES);
$country   = htmlspecialchars($customer['country']    ?? '', ENT_QUOTES);

$pageTitle = 'Checkout || TT Devassy Jewellery';
require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Checkout</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li class="active">Checkout</li>
            </ul>
        </div>
    </div>
</div>

<!-- Checkout Area -->
<div class="checkout-area">
    <div class="container">
        <div id="checkout-alert"></div>
        <div class="row">

            <!-- Billing Details -->
            <div class="col-lg-6 col-12">
                <form id="checkout-form">
                    <div class="checkbox-form">
                        <h3>Billing Details</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>First Name <span class="required">*</span></label>
                                    <input type="text" id="co-first-name" value="<?= $firstName ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Last Name <span class="required">*</span></label>
                                    <input type="text" id="co-last-name" value="<?= $lastName ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Email Address <span class="required">*</span></label>
                                    <input type="email" id="co-email" value="<?= $email ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Phone <span class="required">*</span></label>
                                    <input type="text" id="co-phone" value="<?= $phone ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkout-form-list">
                                    <label>Address <span class="required">*</span></label>
                                    <input type="text" id="co-address" placeholder="Street address" value="<?= $address ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Town / City</label>
                                    <input type="text" id="co-city" value="<?= $city ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>State / County</label>
                                    <input type="text" id="co-state" value="<?= $state ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Postcode / Zip</label>
                                    <input type="text" id="co-postal" value="<?= $postal ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-list">
                                    <label>Country</label>
                                    <input type="text" id="co-country" value="<?= $country ?>">
                                </div>
                            </div>
                        </div>

                        <div class="order-notes" style="margin-top: 10px;">
                            <div class="checkout-form-list checkout-form-list-2">
                                <label>Order Notes</label>
                                <textarea id="co-notes" cols="30" rows="5"
                                    placeholder="Notes about your order, e.g. special requests."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-6 col-12">
                <div class="your-order">
                    <h3>Your Order</h3>
                    <div class="your-order-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="cart-product-name">Product</th>
                                    <th class="cart-product-total">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item):
                                    $product   = $item['product'] ?? [];
                                    $name      = htmlspecialchars($product['name'] ?? 'Product', ENT_QUOTES);
                                    $qty       = (int) ($item['quantity'] ?? 1);
                                    $lineTotal = (float) ($item['line_total'] ?? 0);
                                ?>
                                <tr class="cart_item">
                                    <td class="cart-product-name">
                                        <?= $name ?>
                                        <strong class="product-quantity"> &times; <?= $qty ?></strong>
                                    </td>
                                    <td class="cart-product-total">
                                        <span class="amount"><?= $currencySymbol ?><?= number_format($lineTotal, 2) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td><span class="amount"><?= $currencySymbol ?><?= number_format((float)($cart['subtotal'] ?? 0), 2) ?></span></td>
                                </tr>
                                <?php if (!empty($cart['discount']) && $cart['discount'] > 0): ?>
                                <tr>
                                    <th>Discount</th>
                                    <td><span class="amount">-<?= $currencySymbol ?><?= number_format((float)$cart['discount'], 2) ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <tr class="order-total">
                                    <th>Order Total</th>
                                    <td><strong><span class="amount"><?= $currencySymbol ?><?= number_format((float)($cart['total'] ?? 0), 2) ?></span></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="payment-method" style="margin-top: 20px;">
                        <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin-bottom: 15px;">
                            <p style="margin: 0; font-size: 14px; color: #555;">
                                <strong>Booking Order</strong><br>
                                Place your order now. Our team will contact you to confirm your purchase and arrange payment.
                            </p>
                        </div>
                        <a href="javascript:void(0)"
                           id="btn-place-order"
                           class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth"
                           style="display:block;text-align:center;">
                            Place Order
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Checkout Area End -->

<?php require_once __DIR__ . '/templates/footer.php'; ?>

<script>
(function ($) {
    var currency = window.APP_CONFIG ? window.APP_CONFIG.currency : '<?= addslashes($currencySymbol) ?>';

    $('#btn-place-order').on('click', function () {
        var $btn = $(this);

        var firstName = $.trim($('#co-first-name').val());
        var lastName  = $.trim($('#co-last-name').val());
        var email     = $.trim($('#co-email').val());
        var phone     = $.trim($('#co-phone').val());
        var address   = $.trim($('#co-address').val());
        var city      = $.trim($('#co-city').val());
        var state     = $.trim($('#co-state').val());
        var postal    = $.trim($('#co-postal').val());
        var country   = $.trim($('#co-country').val());
        var notes     = $.trim($('#co-notes').val());

        if (!firstName || !lastName || !email || !address) {
            showAlert('Please fill in all required fields (First Name, Last Name, Email, Address).', 'danger');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showAlert('Please enter a valid email address.', 'danger');
            return;
        }

        var billingParts = [address];
        if (city)    billingParts.push(city);
        if (state)   billingParts.push(state);
        if (postal)  billingParts.push(postal);
        if (country) billingParts.push(country);
        var billingAddress = billingParts.join(', ');

        $btn.prop('disabled', true).text('Placing Order...');
        clearAlert();

        $.ajax({
            url: 'api/order.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                customer_name:    firstName + ' ' + lastName,
                customer_email:   email,
                customer_phone:   phone,
                billing_address:  billingAddress,
                shipping_address: billingAddress,
                notes:            notes
            }),
            success: function (res) {
                if (res && res.success) {
                    var orderId = res.data && res.data.id ? res.data.id : '';
                    var msg = 'Your order has been placed successfully!' +
                              (orderId ? ' Order #' + orderId + '.' : '') +
                              ' Our team will contact you shortly.';
                    showAlert(msg, 'success');
                    $btn.prop('disabled', true).text('Order Placed');
                    // Clear cart display
                    setTimeout(function () {
                        window.location.href = 'my-account.php';
                    }, 3000);
                } else {
                    var errMsg = (res && res.message) ? res.message : 'Could not place order. Please try again.';
                    showAlert(errMsg, 'danger');
                    $btn.prop('disabled', false).text('Place Order');
                }
            },
            error: function () {
                showAlert('Could not place order. Please try again.', 'danger');
                $btn.prop('disabled', false).text('Place Order');
            }
        });
    });

    function showAlert(msg, type) {
        $('#checkout-alert').html(
            '<div class="alert alert-' + type + '" style="padding:12px 16px;border-radius:4px;margin-bottom:20px;' +
            'background:' + (type === 'success' ? '#d4edda' : '#f8d7da') + ';' +
            'border:1px solid ' + (type === 'success' ? '#c3e6cb' : '#f5c6cb') + ';' +
            'color:' + (type === 'success' ? '#155724' : '#721c24') + ';">' +
            $('<div>').text(msg).html() + '</div>'
        );
        $('html, body').animate({ scrollTop: 0 }, 400);
    }

    function clearAlert() {
        $('#checkout-alert').empty();
    }
}(jQuery));
</script>
