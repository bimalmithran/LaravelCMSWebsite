<?php
/** @var array<string, mixed>|null $featuredPromoBanner */

$featuredPromoBannerDesktopRaw = trim((string) ($featuredPromoBanner["image_url"] ?? ""));
$featuredPromoBannerTabletRaw  = trim((string) ($featuredPromoBanner["tablet_image_url"] ?? ""));
$featuredPromoBannerMobileRaw  = trim((string) ($featuredPromoBanner["mobile_image_url"] ?? ""));

$featuredPromoBannerDesktop = $featuredPromoBannerDesktopRaw !== "" ? $storefront->resolveAssetUrl($featuredPromoBannerDesktopRaw) : "";
$featuredPromoBannerTablet  = $featuredPromoBannerTabletRaw !== "" ? $storefront->resolveAssetUrl($featuredPromoBannerTabletRaw) : "";
$featuredPromoBannerMobile  = $featuredPromoBannerMobileRaw !== "" ? $storefront->resolveAssetUrl($featuredPromoBannerMobileRaw) : "";

$featuredPromoBannerTitle       = trim((string) ($featuredPromoBanner["title"] ?? ""));
$featuredPromoBannerSubtitle    = trim((string) ($featuredPromoBanner["subtitle"] ?? ""));
$featuredPromoBannerDescription = trim((string) ($featuredPromoBanner["description"] ?? ""));
$featuredPromoBannerPriceText   = trim((string) ($featuredPromoBanner["price_text"] ?? ""));
$featuredPromoBannerButtonText  = trim((string) ($featuredPromoBanner["button_text"] ?? ""));
$featuredPromoBannerActionUrl   = trim((string) ($featuredPromoBanner["action_url"] ?? ""));

if (
    $featuredPromoBannerDesktop === "" &&
    $featuredPromoBannerTitle === "" &&
    $featuredPromoBannerSubtitle === "" &&
    $featuredPromoBannerDescription === "" &&
    $featuredPromoBannerPriceText === ""
) {
    return;
}
?>
<?php if ($featuredPromoBannerDesktop !== ""): ?>
<style>
    .featured-promo-bg { background-image: url('<?= htmlspecialchars($featuredPromoBannerDesktop, ENT_QUOTES) ?>'); }
    <?php if ($featuredPromoBannerTablet !== ""): ?>
    @media (max-width: 991px) {
        .featured-promo-bg { background-image: url('<?= htmlspecialchars($featuredPromoBannerTablet, ENT_QUOTES) ?>'); }
    }
    <?php endif; ?>
    <?php if ($featuredPromoBannerMobile !== ""): ?>
    @media (max-width: 767px) {
        .featured-promo-bg { background-image: url('<?= htmlspecialchars($featuredPromoBannerMobile, ENT_QUOTES) ?>'); }
    }
    <?php endif; ?>
</style>
<?php endif; ?>
<div class="static-banner_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="static-banner-image featured-promo-bg">
                    <?php if ($featuredPromoBannerTitle !== "" || $featuredPromoBannerSubtitle !== "" || $featuredPromoBannerDescription !== "" || $featuredPromoBannerPriceText !== "" || ($featuredPromoBannerActionUrl !== "" && $featuredPromoBannerButtonText !== "")): ?>
                        <div class="static-banner-content">
                            <?php if ($featuredPromoBannerDescription !== ""): ?>
                                <p><?= htmlspecialchars($featuredPromoBannerDescription) ?></p>
                            <?php endif; ?>

                            <?php if ($featuredPromoBannerTitle !== ""): ?>
                                <h2><?= htmlspecialchars($featuredPromoBannerTitle) ?></h2>
                            <?php endif; ?>

                            <?php if ($featuredPromoBannerSubtitle !== ""): ?>
                                <h3><?= htmlspecialchars($featuredPromoBannerSubtitle) ?></h3>
                            <?php endif; ?>

                            <?php if ($featuredPromoBannerPriceText !== ""): ?>
                                <p class="schedule">
                                    <span><?= htmlspecialchars($featuredPromoBannerPriceText) ?></span>
                                </p>
                            <?php endif; ?>

                            <?php if ($featuredPromoBannerActionUrl !== "" && $featuredPromoBannerButtonText !== ""): ?>
                                <div class="hiraola-btn-ps_left">
                                    <a
                                        href="<?= htmlspecialchars($featuredPromoBannerActionUrl) ?>"
                                        class="hiraola-btn"
                                        ><?= htmlspecialchars($featuredPromoBannerButtonText) ?></a
                                    >
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
