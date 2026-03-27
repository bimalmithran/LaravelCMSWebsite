<?php
/**
 * List-view product card (used alongside product-card.php in the shop grid).
 *
 * CSS toggles .slide-item / .list-slide_item visibility based on grid/list mode.
 *
 * @var array<string, mixed> $productCard
 */
?>
<div class="list-slide_item">
    <div class="single_product">
        <div class="product-img">
            <a href="<?= htmlspecialchars($productCard['link']) ?>">
                <img
                    class="primary-img"
                    src="<?= htmlspecialchars($productCard['primary_image']) ?>"
                    alt="<?= htmlspecialchars($productCard['name']) ?>"
                />
                <img
                    class="secondary-img"
                    src="<?= htmlspecialchars($productCard['secondary_image']) ?>"
                    alt="<?= htmlspecialchars($productCard['name']) ?>"
                />
            </a>
        </div>
        <div class="hiraola-product_content">
            <div class="product-desc_info">
                <h6>
                    <a class="product-name" href="<?= htmlspecialchars($productCard['link']) ?>">
                        <?= htmlspecialchars($productCard['name']) ?>
                    </a>
                </h6>
                <div class="rating-box">
                    <ul>
                        <?php foreach (range(1, 5) as $star): ?>
                        <li<?= $star > $productCard['rating'] ? ' class="silver-color"' : '' ?>>
                            <i class="fa fa-star-of-david"></i>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="price-box">
                    <span class="new-price"><?= htmlspecialchars($productCard['display_price']) ?></span>
                    <?php if (!empty($productCard['old_price'])): ?>
                    <span class="old-price"><?= htmlspecialchars($productCard['old_price']) ?></span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($productCard['short_description'])): ?>
                <p class="product-short-desc"><?= htmlspecialchars($productCard['short_description']) ?></p>
                <?php endif; ?>
            </div>
            <div class="add-actions">
                <ul>
                    <li>
                        <a class="hiraola-add_cart" href="cart.php"
                           data-bs-toggle="tooltip" data-placement="top" title="Add To Cart">
                            Add To Cart
                        </a>
                    </li>
                    <li>
                        <a class="hiraola-add_compare" href="compare.php"
                           data-bs-toggle="tooltip" data-placement="top" title="Compare This Product">
                            <i class="ion-ios-shuffle-strong"></i>
                        </a>
                    </li>
                    <li
                        class="quick-view-btn"
                        data-product-id="<?= htmlspecialchars((string) ($productCard['id'] ?? '')) ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModalCenter"
                    >
                        <a href="javascript:void(0)" data-bs-toggle="tooltip" data-placement="top" title="Quick View">
                            <i class="ion-eye"></i>
                        </a>
                    </li>
                    <li>
                        <a class="hiraola-add_compare" href="wishlist.php"
                           data-bs-toggle="tooltip" data-placement="top" title="Add To Wishlist">
                            <i class="ion-android-favorite-outline"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
