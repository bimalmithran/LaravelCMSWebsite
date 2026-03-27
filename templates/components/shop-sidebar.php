<?php
/**
 * Shop sidebar component – price filter + category list.
 *
 * @var array<int, array<string, mixed>> $categories
 * @var int|null                         $selectedCategoryId
 * @var float|null                       $minPrice
 * @var float|null                       $maxPrice
 * @var string                           $searchQuery
 * @var array<string, mixed>             $queryParams  – base params to keep in filter links
 */
?>
<div class="hiraola-sidebar-catagories_area">

    <!-- Price Filter -->
    <div class="hiraola-sidebar_categories">
        <div class="hiraola-categories_title">
            <h5>Price</h5>
        </div>
        <div class="price-filter">
            <div id="slider-range"></div>
            <div class="price-slider-amount">
                <div class="label-input">
                    <label>Price:</label>
                    <input type="text" id="amount" name="price" placeholder="Select range" readonly />
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <?php if (!empty($categories)): ?>
    <div class="category-module hiraola-sidebar_categories">
        <div class="category-module_heading">
            <h5>Categories</h5>
        </div>
        <div class="module-body">
            <ul class="module-list_item">
                <li>
                    <?php
                    $allParams = $queryParams;
                    unset($allParams['category'], $allParams['page']);
                    $allHref = 'shop.php' . ($allParams ? '?' . http_build_query($allParams) : '');
                    ?>
                    <a href="<?= htmlspecialchars($allHref) ?>"
                        <?= $selectedCategoryId === null ? 'style="font-weight:bold;color:#333"' : '' ?>>
                        All Categories
                    </a>
                </li>
                <?php foreach ($categories as $cat): ?>
                <?php
                $catParams = array_merge($queryParams, ['category' => $cat['id']]);
                unset($catParams['page']);
                $catHref   = 'shop.php?' . http_build_query($catParams);
                $isActive  = $selectedCategoryId === (int) $cat['id'];
                ?>
                <li>
                    <a href="<?= htmlspecialchars($catHref) ?>"
                        <?= $isActive ? 'style="font-weight:bold;color:#333"' : '' ?>>
                        <?= htmlspecialchars((string) ($cat['name'] ?? '')) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

</div>
