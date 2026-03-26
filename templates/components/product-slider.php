<?php
/** @var array<int, array<string, mixed>> $products */
/** @var string $productCardTemplate */

if ($products === []): ?>
    <div class="tagged-products-empty-state">
        <div class="single_product">
            <div class="hiraola-product_content">
                <div class="product-desc_info">
                    <h6>No products found in this category.</h6>
                </div>
            </div>
        </div>
    </div>
<?php
    return;
endif;

foreach ($products as $product):
    $productCard = $product;
    require $productCardTemplate;
endforeach;
