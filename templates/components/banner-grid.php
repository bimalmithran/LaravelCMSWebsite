<?php
/**
 * Reusable banner grid section component.
 *
 * Required variables (set by the caller before require):
 *   @var array[]  $bannerGridBanners      Banners fetched from the API for this placement.
 *   @var string   $bannerGridSectionClass CSS class for the outer section div (e.g. "hiraola-banner_area-2").
 *   @var string   $bannerGridColClass     Bootstrap column class for each item (e.g. "col-lg-6" or "col-lg-4").
 */

if (empty($bannerGridBanners)) {
    return;
}
?>
<div class="<?= htmlspecialchars($bannerGridSectionClass) ?>">
    <div class="container">
        <div class="row">
            <?php foreach ($bannerGridBanners as $bannerGridItem): ?>
                <?php
                $bannerGridDesktopUrl = trim((string) ($bannerGridItem["image_url"] ?? ""));
                $bannerGridTabletUrl  = trim((string) ($bannerGridItem["tablet_image_url"] ?? ""));
                $bannerGridMobileUrl  = trim((string) ($bannerGridItem["mobile_image_url"] ?? ""));

                if ($bannerGridDesktopUrl === "") {
                    continue;
                }

                $bannerGridDesktopUrl = $storefront->resolveAssetUrl($bannerGridDesktopUrl);
                $bannerGridTabletUrl  = $bannerGridTabletUrl !== "" ? $storefront->resolveAssetUrl($bannerGridTabletUrl) : "";
                $bannerGridMobileUrl  = $bannerGridMobileUrl !== "" ? $storefront->resolveAssetUrl($bannerGridMobileUrl) : "";

                $bannerGridActionUrl = trim((string) ($bannerGridItem["action_url"] ?? ""));
                $bannerGridTitle     = trim((string) ($bannerGridItem["title"] ?? "Hiraola's Banner"));
                ?>
                <div class="<?= htmlspecialchars($bannerGridColClass) ?>">
                    <div class="banner-item img-hover_effect">
                        <a href="<?= $bannerGridActionUrl !== "" ? htmlspecialchars($bannerGridActionUrl) : "#" ?>">
                            <picture>
                                <?php if ($bannerGridMobileUrl !== ""): ?>
                                    <source media="(max-width: 767px)" srcset="<?= htmlspecialchars($bannerGridMobileUrl) ?>">
                                <?php endif; ?>
                                <?php if ($bannerGridTabletUrl !== ""): ?>
                                    <source media="(max-width: 1199px)" srcset="<?= htmlspecialchars($bannerGridTabletUrl) ?>">
                                <?php endif; ?>
                                <img
                                    class="img-full"
                                    src="<?= htmlspecialchars($bannerGridDesktopUrl) ?>"
                                    alt="<?= htmlspecialchars($bannerGridTitle) ?>"
                                />
                            </picture>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
