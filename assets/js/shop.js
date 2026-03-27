/**
 * shop.js — Cart & Wishlist AJAX handlers
 *
 * Requires:
 *   window.APP_CONFIG = { loggedIn: bool, loginUrl: string, apiOrigin: string, currency: string }
 * Set by footer.php before this script loads.
 */
(function ($) {
    'use strict';

    var CFG = window.APP_CONFIG || {};
    var loggedIn = !!CFG.loggedIn;
    var loginUrl = CFG.loginUrl || 'login.php';
    var apiOrigin = CFG.apiOrigin || '';
    var currency = CFG.currency || '₹';

    /* ── helpers ────────────────────────────────────────────── */

    function resolveImageUrl(path) {
        if (!path) return 'assets/images/product/small-size/placeholder.jpg';
        if (/^https?:\/\//i.test(path) || path.indexOf('//') === 0 || path.indexOf('assets/') === 0) {
            return path;
        }
        return apiOrigin + '/' + path.replace(/^\/+/, '');
    }

    function showToast(msg, type) {
        var color = type === 'success' ? '#28a745' : '#dc3545';
        var $t = $('<div></div>').text(msg).css({
            position: 'fixed', bottom: '30px', right: '30px',
            background: color, color: '#fff', padding: '12px 20px',
            borderRadius: '4px', zIndex: 99999, fontSize: '14px',
            boxShadow: '0 4px 12px rgba(0,0,0,.2)', maxWidth: '320px'
        }).appendTo('body');
        setTimeout(function () { $t.fadeOut(400, function () { $t.remove(); }); }, 3000);
    }

    function requireLogin() {
        var next = encodeURIComponent(window.location.pathname.replace(/.*\//, '') + window.location.search);
        window.location.href = loginUrl + '?next=' + next;
    }

    /* ── minicart ────────────────────────────────────────────── */

    function renderMiniCart(data) {
        var items = (data && data.items) ? data.items : [];
        var cart  = (data && data.cart)  ? data.cart  : {};
        var $list = $('.minicart-list');
        var $amt  = $('.minicart-item_total .ammount');
        $list.empty();
        if (items.length === 0) {
            $list.append('<li style="padding:15px;text-align:center;color:#888;">Your cart is empty</li>');
            $amt.text('');
            return;
        }
        $.each(items, function (_, item) {
            var product   = item.product || {};
            var name      = product.name || 'Product';
            var imgUrl    = resolveImageUrl(product.image);
            var qty       = item.quantity || 1;
            var price     = parseFloat(item.price || 0).toFixed(2);
            var productId = item.product_id;
            var $li = $(
                '<li class="minicart-product">' +
                    '<a class="product-item_remove minicart-remove-btn" href="javascript:void(0)" data-product-id="' + productId + '">' +
                        '<i class="ion-android-close"></i>' +
                    '</a>' +
                    '<div class="product-item_img">' +
                        '<img src="' + imgUrl + '" alt="' + $('<div>').text(name).html() + '">' +
                    '</div>' +
                    '<div class="product-item_content">' +
                        '<a class="product-item_title" href="product.php?id=' + productId + '">' + $('<div>').text(name).html() + '</a>' +
                        '<span class="product-item_quantity">' + qty + ' x ' + currency + price + '</span>' +
                    '</div>' +
                '</li>'
            );
            $list.append($li);
        });
        var total = parseFloat(cart.total || cart.subtotal || 0).toFixed(2);
        $amt.text(currency + total);
    }

    function loadMiniCart() {
        if (!loggedIn) return;
        $.get('api/cart.php', function (res) {
            if (res && res.success) renderMiniCart(res.data);
        }).fail(function () { /* silently ignore */ });
    }

    /* ── add to cart ─────────────────────────────────────────── */

    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();
        if (!loggedIn) { requireLogin(); return; }

        var $btn      = $(this);
        var productId = parseInt($btn.data('product-id'), 10);
        if (!productId) return;

        var quantity = 1;
        if ($btn.data('qty-source') === 'product-detail') {
            var raw = $('.cart-plus-minus-box').first().val();
            quantity = parseInt(raw, 10) || 1;
        }

        $btn.prop('disabled', true);
        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'add', product_id: productId, quantity: quantity }),
            success: function (res) {
                if (res && res.success) {
                    showToast('Added to cart!', 'success');
                    renderMiniCart(res.data);
                } else {
                    showToast((res && res.message) ? res.message : 'Could not add to cart.', 'error');
                }
            },
            error: function () { showToast('Could not add to cart.', 'error'); },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

    /* ── add to wishlist ─────────────────────────────────────── */

    $(document).on('click', '.btn-add-to-wishlist', function (e) {
        e.preventDefault();
        if (!loggedIn) { requireLogin(); return; }

        var $btn      = $(this);
        var productId = parseInt($btn.data('product-id'), 10);
        if (!productId) return;

        $btn.prop('disabled', true);
        $.ajax({
            url: 'api/wishlist.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'add', product_id: productId }),
            success: function (res) {
                if (res && res.success) {
                    showToast('Added to wishlist!', 'success');
                    wishlistedIds[productId] = true;
                    // Mark ALL wishlist buttons for this product on the page
                    $('.btn-add-to-wishlist[data-product-id="' + productId + '"] i')
                        .removeClass('ion-android-favorite-outline')
                        .addClass('ion-android-favorite');
                } else {
                    showToast((res && res.message) ? res.message : 'Could not add to wishlist.', 'error');
                }
            },
            error: function () { showToast('Could not add to wishlist.', 'error'); },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

    /* ── remove from minicart ────────────────────────────────── */

    $(document).on('click', '.minicart-remove-btn', function (e) {
        e.preventDefault();
        if (!loggedIn) return;

        var productId = parseInt($(this).data('product-id'), 10);
        if (!productId) return;

        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'remove', product_id: productId }),
            success: function (res) {
                if (res && res.success) renderMiniCart(res.data);
            }
        });
    });

    /* ── cart page: remove item ──────────────────────────────── */

    $(document).on('click', '.btn-remove-cart-item', function (e) {
        e.preventDefault();
        var $btn      = $(this);
        var productId = parseInt($btn.data('product-id'), 10);
        var $row      = $btn.closest('tr');

        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'remove', product_id: productId }),
            success: function (res) {
                if (res && res.success) {
                    $row.fadeOut(300, function () { $row.remove(); });
                    updateCartTotals(res.data);
                    renderMiniCart(res.data);
                    showToast('Item removed.', 'success');
                } else {
                    showToast('Could not remove item.', 'error');
                }
            },
            error: function () { showToast('Could not remove item.', 'error'); }
        });
    });

    /* ── cart page: quantity +/- ─────────────────────────────── */

    $(document).on('click', '.hiraola-cart-area .inc.qtybutton', function () {
        var $input    = $(this).siblings('.cart-qty-input');
        var productId = parseInt($input.data('product-id'), 10);
        var newQty    = Math.max(1, (parseInt($input.val(), 10) || 1) + 1);
        $input.val(newQty);
        updateCartItemQty(productId, newQty, $input.closest('tr'));
    });

    $(document).on('click', '.hiraola-cart-area .dec.qtybutton', function () {
        var $input    = $(this).siblings('.cart-qty-input');
        var productId = parseInt($input.data('product-id'), 10);
        var current   = parseInt($input.val(), 10) || 1;
        if (current <= 1) return;
        var newQty = current - 1;
        $input.val(newQty);
        updateCartItemQty(productId, newQty, $input.closest('tr'));
    });

    function updateCartItemQty(productId, qty, $row) {
        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'update', product_id: productId, quantity: qty }),
            success: function (res) {
                if (res && res.success) {
                    var items = (res.data && res.data.items) ? res.data.items : [];
                    $.each(items, function (_, item) {
                        if (item.product_id === productId) {
                            $row.find('.line-total').text(currency + parseFloat(item.line_total).toFixed(2));
                        }
                    });
                    updateCartTotals(res.data);
                    renderMiniCart(res.data);
                }
            }
        });
    }

    function updateCartTotals(data) {
        var cart = (data && data.cart) ? data.cart : {};
        var subtotal = parseFloat(cart.subtotal || 0).toFixed(2);
        var total    = parseFloat(cart.total    || 0).toFixed(2);
        $('#cart-subtotal').text(currency + subtotal);
        $('#cart-total').text(currency + total);
    }

    /* ── cart page: clear cart ───────────────────────────────── */

    $(document).on('click', '#btn-clear-cart', function () {
        if (!confirm('Are you sure you want to clear your cart?')) return;
        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'clear' }),
            success: function (res) {
                if (res && res.success) {
                    location.reload();
                }
            }
        });
    });

    /* ── wishlist page: remove item ──────────────────────────── */

    $(document).on('click', '.btn-remove-wishlist-item', function (e) {
        e.preventDefault();
        var $btn      = $(this);
        var productId = parseInt($btn.data('product-id'), 10);
        var $row      = $btn.closest('tr');

        $.ajax({
            url: 'api/wishlist.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'remove', product_id: productId }),
            success: function (res) {
                if (res && res.success) {
                    $row.fadeOut(300, function () { $row.remove(); });
                    showToast('Removed from wishlist.', 'success');
                } else {
                    showToast('Could not remove item.', 'error');
                }
            },
            error: function () { showToast('Could not remove item.', 'error'); }
        });
    });

    /* ── wishlist page: add to cart ──────────────────────────── */
    // Handled by the global .btn-add-to-cart listener above.

    /* ── wishlist state on page load ─────────────────────────── */

    var wishlistedIds = {};

    function loadWishlistState() {
        if (!loggedIn) return;
        $.get('api/wishlist.php', function (res) {
            if (!res || !res.success || !res.data) return;
            var items = res.data.items || [];
            wishlistedIds = {};
            $.each(items, function (_, item) {
                wishlistedIds[item.product_id] = true;
            });
            applyWishlistState();
        }).fail(function () { /* silently ignore */ });
    }

    function applyWishlistState() {
        $('.btn-add-to-wishlist').each(function () {
            var id = parseInt($(this).data('product-id'), 10);
            if (id && wishlistedIds[id]) {
                $(this).find('i')
                    .removeClass('ion-android-favorite-outline')
                    .addClass('ion-android-favorite');
            }
        });
    }

    // Expose so tagged-products-section.js can call it after AJAX loads
    window.applyWishlistState = applyWishlistState;

    /* ── init ────────────────────────────────────────────────── */

    $(function () {
        loadMiniCart();
        loadWishlistState();
    });

}(jQuery));
