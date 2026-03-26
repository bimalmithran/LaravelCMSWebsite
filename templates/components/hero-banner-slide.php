<?php
/** @var array<string, mixed> $heroBanner */
/** @var int $heroBannerIndex */

$heroBannerImage = trim((string) ($heroBanner["image_url"] ?? ""));
$heroBannerTitle = trim((string) ($heroBanner["title"] ?? ""));
$heroBannerSubtitle = trim((string) ($heroBanner["subtitle"] ?? ""));
$heroBannerDescription = trim((string) ($heroBanner["description"] ?? ""));
$heroBannerPriceText = trim((string) ($heroBanner["price_text"] ?? ""));
$heroBannerButtonText = trim((string) ($heroBanner["button_text"] ?? ""));
$heroBannerActionUrl = trim((string) ($heroBanner["action_url"] ?? ""));

if ($heroBannerImage === "" && $heroBannerTitle === "" && $heroBannerSubtitle === "") {
    return;
}
?>
<div class="single-slide animation-style-0<?= $heroBannerIndex % 2 === 0
    ? "1"
    : "2" ?> dynamic-slide-bg"
     style="background-image: <?= $heroBannerImage !== ""
         ? "url('" . htmlspecialchars($heroBannerImage) . "')"
         : "none" ?>;">

    <div class="container">
        <?php if ($heroBannerTitle !== "" || $heroBannerSubtitle !== "" || $heroBannerDescription !== "" || $heroBannerPriceText !== ""): ?>
            <div class="slider-content">
                <?php if ($heroBannerSubtitle !== ""): ?>
                    <h5><span><?= htmlspecialchars($heroBannerSubtitle) ?></span></h5>
                <?php endif; ?>

                <?php if ($heroBannerTitle !== ""): ?>
                    <h2><?= htmlspecialchars($heroBannerTitle) ?></h2>
                <?php endif; ?>

                <?php if ($heroBannerDescription !== ""): ?>
                    <h3><?= htmlspecialchars($heroBannerDescription) ?></h3>
                <?php endif; ?>

                <?php if ($heroBannerPriceText !== ""): ?>
                    <h4><?= htmlspecialchars($heroBannerPriceText) ?></h4>
                <?php endif; ?>

                <?php if ($heroBannerActionUrl !== "" && $heroBannerButtonText !== ""): ?>
                    <div class="hiraola-btn-ps_center slide-btn">
                        <a class="hiraola-btn" href="<?= htmlspecialchars($heroBannerActionUrl) ?>">
                            <?= htmlspecialchars($heroBannerButtonText) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="slider-progress"></div>
    </div>
</div>
