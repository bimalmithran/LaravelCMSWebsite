<?php
/** @var array<string, mixed> $productCard */
?>
<div class="slide-item">
    <div class="single_product">
        <div class="product-img">
            <a href="<?= htmlspecialchars($productCard["link"]) ?>">
                <img
                    class="primary-img"
                    src="<?= htmlspecialchars($productCard["primary_image"]) ?>"
                    alt="<?= htmlspecialchars($productCard["name"]) ?>"
                />
                <img
                    class="secondary-img"
                    src="<?= htmlspecialchars($productCard["secondary_image"]) ?>"
                    alt="<?= htmlspecialchars($productCard["name"]) ?>"
                />
            </a>
            <span class="<?= htmlspecialchars($productCard["badge_class"]) ?>"><?= htmlspecialchars(
                $productCard["badge_label"],
            ) ?></span>
            <div class="add-actions">
                <ul>
                    <li>
                        <a
                            class="hiraola-add_cart btn-add-to-cart"
                            href="javascript:void(0)"
                            data-product-id="<?= (int) ($productCard['id'] ?? 0) ?>"
                            data-bs-toggle="tooltip"
                            data-placement="top"
                            title="Add To Cart"
                            ><i class="ion-bag"></i
                        ></a>
                    </li>
                    <li>
                        <a
                            class="hiraola-add_compare"
                            href="compare.html"
                            data-bs-toggle="tooltip"
                            data-placement="top"
                            title="Compare This Product"
                            ><i class="ion-ios-shuffle-strong"></i
                        ></a>
                    </li>
                    <li
                        class="quick-view-btn"
                        data-product-id="<?= htmlspecialchars((string) ($productCard['id'] ?? '')) ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModalCenter"
                    >
                        <a
                            href="javascript:void(0)"
                            data-bs-toggle="tooltip"
                            data-placement="top"
                            title="Quick View"
                            ><i class="ion-eye"></i
                        ></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="hiraola-product_content">
            <div class="product-desc_info">
                <h6>
                    <a
                        class="product-name"
                        href="<?= htmlspecialchars($productCard["link"]) ?>"
                    ><?= htmlspecialchars($productCard["name"]) ?></a>
                </h6>
                <div class="price-box">
                    <span class="new-price"><?= htmlspecialchars($productCard["display_price"]) ?></span>
                    <?php if (!empty($productCard["old_price"])): ?>
                        <span class="old-price"><?= htmlspecialchars(
                            $productCard["old_price"],
                        ) ?></span>
                    <?php endif; ?>
                </div>
                <div class="additional-add_action">
                    <ul>
                        <li>
                            <a
                                class="hiraola-add_compare btn-add-to-wishlist"
                                href="javascript:void(0)"
                                data-product-id="<?= (int) ($productCard['id'] ?? 0) ?>"
                                data-bs-toggle="tooltip"
                                data-placement="top"
                                title="Add To Wishlist"
                                ><i class="ion-android-favorite-outline"></i
                            ></a>
                        </li>
                    </ul>
                </div>
                <div class="rating-box">
                    <ul>
                        <?php foreach (range(1, 5) as $star): ?>
                            <li<?= $star > $productCard["rating"] ? ' class="silver-color"' : "" ?>>
                                <i class="fa fa-star-of-david"></i>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
