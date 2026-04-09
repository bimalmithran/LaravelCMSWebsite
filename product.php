<?php
require_once __DIR__ . '/bootstrap.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';

if ($id <= 0 && $slug === '') {
    header('Location: shop.php');
    exit;
}

$productDetailService = new \App\Services\ProductDetailService(
    $storefront,
    new \App\Services\ProductCardService($storefront, 'assets/images/product/medium-size/1-1.jpg', $currencySymbol),
    $currencySymbol
);

if ($slug !== '') {
    $product = $productDetailService->getProductData($slug, true);
} else {
    $product = $productDetailService->getProductData($id);
}

if ($product !== null) {
    $id = (int) $product['id'];
}


if ($product === null) {
    http_response_code(404);
    $pageTitle = '404 – Product Not Found || TT Devassy Jewellery';
    require_once __DIR__ . '/templates/header-inner.php';
    echo '<div class="container" style="padding:80px 0;text-align:center">
            <h3>Product not found.</h3>
            <a href="shop.php" class="hiraola-btn hiraola-btn_dark" style="margin-top:20px;display:inline-block">Back to Shop</a>
          </div>';
    require_once __DIR__ . '/templates/footer.php';
    exit;
}

$pageTitle   = htmlspecialchars($product['name']) . ' || TT Devassy Jewellery';
$pageMetaDescription = strip_tags($product['short_description']); // Set SEO description
$breadcrumb  = $product['name'];
$images      = $product['images'];
$firstImage  = $images[0] ?? 'assets/images/product/large-size/1.jpg';
$reviewCount = count($product['reviews']);

require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>Product Detail</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li class="active"><?= htmlspecialchars($product['name']) ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- Single Product Area -->
<div class="sp-area">
    <div class="container">
        <div class="sp-nav">
            <div class="row">

                <!-- Product Images -->
                <div class="col-lg-5 col-md-5">
                    <div class="sp-img_area">
                        <div class="zoompro-border">
                            <img
                                class="zoompro"
                                src="<?= htmlspecialchars($firstImage) ?>"
                                data-zoom-image="<?= htmlspecialchars($firstImage) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                            />
                        </div>
                        <?php if (count($images) > 1): ?>
                        <div id="gallery" class="sp-img_slider">
                            <?php foreach ($images as $i => $imgUrl): ?>
                            <a
                                <?= $i === 0 ? 'class="active"' : '' ?>
                                data-image="<?= htmlspecialchars($imgUrl) ?>"
                                data-zoom-image="<?= htmlspecialchars($imgUrl) ?>"
                            >
                                <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-7 col-md-7">
                    <div class="sp-content">
                        <div class="sp-heading">
                            <h5><a href="#"><?= htmlspecialchars($product['name']) ?></a></h5>
                        </div>
                        <?php if ($product['sku'] !== ''): ?>
                        <span class="reference">SKU: <?= htmlspecialchars($product['sku']) ?></span>
                        <?php endif; ?>

                        <!-- Rating -->
                        <?php $starRating = $product['rating']; require __DIR__ . '/templates/components/star-display.php'; ?>

                        <!-- Price & Meta -->
                        <div class="sp-essential_stuff">
                            <ul>
                                <li>
                                    Price:
                                    <a href="javascript:void(0)">
                                        <span><?= htmlspecialchars($product['display_price']) ?></span>
                                        <?php if ($product['old_price']): ?>
                                        <span style="text-decoration:line-through;color:#999;margin-left:8px">
                                            <?= htmlspecialchars($product['old_price']) ?>
                                        </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php if ($product['brand'] !== ''): ?>
                                <li>Brand: <a href="javascript:void(0)"><?= htmlspecialchars($product['brand']) ?></a></li>
                                <?php endif; ?>
                                <?php if ($product['category'] !== ''): ?>
                                <li>Category: <a href="shop.php"><?= htmlspecialchars($product['category']) ?></a></li>
                                <?php endif; ?>
                                <li>
                                    Availability:
                                    <a href="javascript:void(0)">
                                        <?= $product['stock'] > 0 ? 'In Stock (' . $product['stock'] . ')' : 'Out of Stock' ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Short Description -->
                        <?php if ($product['short_description'] !== ''): ?>
                        <div style="margin: 10px 0 15px; color: #555; font-size: 14px; line-height: 1.7;">
                            <?= nl2br(htmlspecialchars($product['short_description'])) ?>
                        </div>
                        <?php endif; ?>

                        <!-- Sizes -->
                        <?php if (!empty($product['sizes'])): ?>
                        <div class="product-size_box">
                            <span>Size</span>
                            <select class="myniceselect nice-select">
                                <?php foreach ($product['sizes'] as $size): ?>
                                <option value="<?= (int) $size['id'] ?>"
                                    <?= $size['stock'] === 0 ? 'disabled' : '' ?>>
                                    <?= htmlspecialchars($size['name']) ?>
                                    <?= $size['stock'] === 0 ? ' (Out of Stock)' : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- Quantity + Add to Cart -->
                        <div class="quantity">
                            <label>Quantity</label>
                            <div class="cart-plus-minus">
                                <input class="cart-plus-minus-box" value="1" type="text" min="1"
                                       max="<?= $product['stock'] ?>">
                                <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                            </div>
                        </div>
                        <div class="qty-btn_area">
                            <ul>
                                <li>
                                    <a class="qty-cart_btn btn-add-to-cart"
                                       href="javascript:void(0)"
                                       data-product-id="<?= $id ?>"
                                       data-qty-source="product-detail"
                                       <?= $product['stock'] === 0 ? 'style="opacity:.5;pointer-events:none"' : '' ?>>
                                        <?= $product['stock'] > 0 ? 'Add To Cart' : 'Out of Stock' ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="qty-wishlist_btn btn-add-to-wishlist"
                                       href="javascript:void(0)"
                                       data-product-id="<?= $id ?>"
                                       data-bs-toggle="tooltip" title="Add To Wishlist">
                                        <i class="ion-android-favorite-outline"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="qty-compare_btn" href="compare.php"
                                       data-bs-toggle="tooltip" title="Compare This Product">
                                        <i class="ion-ios-shuffle-strong"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Tags -->
                        <?php if (!empty($product['tags'])): ?>
                        <div class="hiraola-tag-line">
                            <h6>Tags:</h6>
                            <?php foreach ($product['tags'] as $i => $tag): ?>
                            <a href="shop.php?q=<?= urlencode($tag) ?>"><?= htmlspecialchars($tag) ?></a><?= $i < count($product['tags']) - 1 ? ',' : '' ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Social Share -->
                        <div class="hiraola-social_link">
                            <ul>
                                <li class="facebook">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                                       data-bs-toggle="tooltip" target="_blank" title="Share on Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </li>
                                <li class="twitter">
                                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($product['name']) ?>"
                                       data-bs-toggle="tooltip" target="_blank" title="Share on Twitter">
                                        <i class="fab fa-twitter-square"></i>
                                    </a>
                                </li>
                                <li class="instagram">
                                    <a href="https://www.instagram.com" data-bs-toggle="tooltip" target="_blank" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Single Product Area End -->

<!-- Product Tabs: Description / Specs / Reviews -->
<div class="hiraola-product-tab_area-2 sp-product-tab_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sp-product-tab_nav">
                    <div class="product-tab">
                        <ul class="nav product-menu">
                            <li><a class="active" data-bs-toggle="tab" href="#description"><span>Description</span></a></li>
                            <?php if (!empty($product['specs'])): ?>
                            <li><a data-bs-toggle="tab" href="#specification"><span>Specification</span></a></li>
                            <?php endif; ?>
                            <li>
                                <a data-bs-toggle="tab" href="#reviews">
                                    <span>Reviews (<?= $reviewCount ?>)</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content hiraola-tab_content">

                        <!-- Description Tab -->
                        <div id="description" class="tab-pane active show" role="tabpanel">
                            <div class="product-description">
                                <?php if ($product['description'] !== ''): ?>
                                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                                <?php else: ?>
                                <p>No description available.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Specification Tab -->
                        <?php if (!empty($product['specs'])): ?>
                        <div id="specification" class="tab-pane" role="tabpanel">
                            <table class="table table-bordered specification-inner_stuff">
                                <tbody>
                                    <?php foreach ($product['specs'] as $label => $value): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($label) ?></strong></td>
                                        <td><?= htmlspecialchars((string) $value) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <!-- Reviews Tab -->
                        <div id="reviews" class="tab-pane" role="tabpanel">
                            <?php if (!empty($product['reviews'])): ?>
                            <div id="review">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <?php foreach ($product['reviews'] as $review): ?>
                                        <tr>
                                            <td style="width:50%"><strong><?= htmlspecialchars($review['author']) ?></strong></td>
                                            <td class="text-right"><?= htmlspecialchars(substr($review['date'], 0, 10)) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <p><?= htmlspecialchars($review['comment']) ?></p>
                                                <?php $starRating = $review['rating']; require __DIR__ . '/templates/components/star-display.php'; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <p style="padding:15px 0;color:#555;">No approved reviews yet. Be the first!</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Tabs End -->

<!-- Write a Review Section (always visible) -->
<div class="hiraola-product-tab_area-2" style="border-top:1px solid #eee;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sp-product-tab_nav">
                    <div style="padding: 30px 0 10px;">
                        <h4 style="font-size:18px; font-weight:600; margin-bottom:20px;">Write a Review</h4>

                        <?php if ($loggedIn): ?>
                        <div id="review-alert" style="display:none;margin-bottom:14px;padding:12px 16px;border-radius:4px;font-size:14px;"></div>
                        <form class="form-horizontal" id="form-review" style="max-width:600px;">
                            <div class="form-group required second-child">
                                <div class="col-sm-12 p-0">
                                    <label class="control-label">Your Rating <span class="required">*</span></label>
                                    <div style="margin-top:6px;">
                                        <select class="star-rating" id="review-rating" style="width:200px; padding:6px 10px; border:1px solid #ddd; border-radius:3px;">
                                            <option value="1">1 – Terrible</option>
                                            <option value="2">2 – Poor</option>
                                            <option value="3">3 – Average</option>
                                            <option value="4">4 – Good</option>
                                            <option value="5">5 – Excellent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required second-child" style="margin-top:16px;">
                                <div class="col-sm-12 p-0">
                                    <label class="control-label">Your Review</label>
                                    <textarea class="review-textarea" id="review-comment" rows="4"
                                        placeholder="Share your experience with this product..."
                                        style="margin-top:6px;width:100%;padding:10px;border:1px solid #ddd;border-radius:3px;resize:vertical;"></textarea>
                                </div>
                            </div>
                            <div class="form-group last-child" style="margin-top:14px;">
                                <a href="javascript:void(0)" id="btn-submit-review"
                                   class="hiraola-btn hiraola-btn_dark">Submit Review</a>
                            </div>
                        </form>
                        <?php else: ?>
                        <p style="color:#555;">
                            <a href="login.php?next=<?= urlencode('product.php?id=' . $id) ?>">Log in</a>
                            to write a review.
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Write a Review Section End -->

<!-- Related Products -->
<?php if (!empty($product['related_products'])): ?>
<div class="hiraola-product_area hiraola-product_area-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hiraola-section_title">
                    <h4>Related Products</h4>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="hiraola-product_slider-3">
                    <?php foreach ($product['related_products'] as $productCard): ?>
                    <?php require __DIR__ . '/templates/components/product-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- Related Products End -->

<!-- Quick View Modal -->
<?php require_once __DIR__ . '/templates/components/quick-view-modal.php'; ?>

<?php require_once __DIR__ . '/templates/footer.php'; ?>

<?php if ($loggedIn): ?>
<script>
(function ($) {
    $('#btn-submit-review').on('click', function () {
        var $btn    = $(this);
        var rating  = parseInt($('#review-rating').val(), 10);
        var comment = $.trim($('#review-comment').val());

        if (!rating || rating < 1 || rating > 5) {
            showReviewAlert('Please select a rating.', false);
            return;
        }

        $btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: 'api/review.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                product_id: <?= $id ?>,
                rating:     rating,
                comment:    comment
            }),
            success: function (res) {
                if (res && res.success) {
                    showReviewAlert(res.message || 'Review submitted! It will appear after approval.', true);
                    $('#form-review')[0].reset();
                    $btn.prop('disabled', true).text('Review Submitted');
                } else {
                    showReviewAlert((res && res.message) ? res.message : 'Could not submit review.', false);
                    $btn.prop('disabled', false).text('Submit Review');
                }
            },
            error: function () {
                showReviewAlert('Could not submit review. Please try again.', false);
                $btn.prop('disabled', false).text('Submit Review');
            }
        });
    });

    function showReviewAlert(msg, success) {
        var $el = $('#review-alert');
        $el.text(msg)
           .css({
               display: 'block',
               background: success ? '#d4edda' : '#f8d7da',
               border: '1px solid ' + (success ? '#c3e6cb' : '#f5c6cb'),
               color: success ? '#155724' : '#721c24'
           });
    }
}(jQuery));
</script>
<?php endif; ?>
