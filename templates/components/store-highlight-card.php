<?php
/** @var array<string, mixed> $storeHighlightCard */
?>
<div class="col-lg-3 col-md-6">
    <div class="shipping-item">
        <div class="shipping-icon">
            <img
                src="<?= htmlspecialchars(
                    $storefront->resolveAssetUrl($storeHighlightCard["icon"] ?? ""),
                ) ?>"
                alt="<?= htmlspecialchars($storeHighlightCard["title"] ?? "Store Highlight") ?>"
            />
        </div>
        <div class="shipping-content">
            <h6><?= htmlspecialchars($storeHighlightCard["title"] ?? "") ?></h6>
            <p><?= htmlspecialchars($storeHighlightCard["description"] ?? "") ?></p>
        </div>
    </div>
</div>
