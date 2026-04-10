<?php
/** @var array<string, mixed> $heroBanner */
/** @var int $heroBannerIndex */

$heroBannerDesktopRaw = trim((string) ($heroBanner["image_url"] ?? ""));
$heroBannerTabletRaw  = trim((string) ($heroBanner["tablet_image_url"] ?? ""));
$heroBannerMobileRaw  = trim((string) ($heroBanner["mobile_image_url"] ?? ""));

// A hero slide without a background image is blank — skip it entirely.
if ($heroBannerDesktopRaw === "") {
    return;
}

$heroBannerDesktop = $storefront->resolveAssetUrl($heroBannerDesktopRaw);
$heroBannerTablet  = $heroBannerTabletRaw !== "" ? $storefront->resolveAssetUrl($heroBannerTabletRaw) : "";
$heroBannerMobile  = $heroBannerMobileRaw !== "" ? $storefront->resolveAssetUrl($heroBannerMobileRaw) : "";

$heroBannerTitle       = trim((string) ($heroBanner["title"] ?? ""));
$heroBannerSubtitle    = trim((string) ($heroBanner["subtitle"] ?? ""));
$heroBannerDescription = trim((string) ($heroBanner["description"] ?? ""));
$heroBannerPriceText   = trim((string) ($heroBanner["price_text"] ?? ""));
$heroBannerButtonText  = trim((string) ($heroBanner["button_text"] ?? ""));
$heroBannerActionUrl   = trim((string) ($heroBanner["action_url"] ?? ""));
?>
<div class="single-slide animation-style-0<?= $heroBannerIndex % 2 === 0 ? "1" : "2" ?> dynamic-slide-bg"
     style="background-image: url('<?= htmlspecialchars($heroBannerDesktop, ENT_QUOTES) ?>');"
     data-bg-desktop="<?= htmlspecialchars($heroBannerDesktop, ENT_QUOTES) ?>"
     <?php if ($heroBannerTablet !== ""): ?>data-bg-tablet="<?= htmlspecialchars($heroBannerTablet, ENT_QUOTES) ?>"<?php endif; ?>
     <?php if ($heroBannerMobile !== ""): ?>data-bg-mobile="<?= htmlspecialchars($heroBannerMobile, ENT_QUOTES) ?>"<?php endif; ?>
>
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
