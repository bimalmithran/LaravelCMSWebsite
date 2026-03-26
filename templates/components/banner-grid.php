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
                $bannerGridImageUrl  = trim((string) ($bannerGridItem["image_url"] ?? ""));
                $bannerGridActionUrl = trim((string) ($bannerGridItem["action_url"] ?? ""));
                $bannerGridTitle     = trim((string) ($bannerGridItem["title"] ?? "Hiraola's Banner"));
                if ($bannerGridImageUrl === "") {
                    continue;
                }
                ?>
                <div class="<?= htmlspecialchars($bannerGridColClass) ?>">
                    <div class="banner-item img-hover_effect">
                        <a href="<?= $bannerGridActionUrl !== "" ? htmlspecialchars($bannerGridActionUrl) : "#" ?>">
                            <img
                                class="img-full"
                                src="<?= htmlspecialchars($bannerGridImageUrl) ?>"
                                alt="<?= htmlspecialchars($bannerGridTitle) ?>"
                            />
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
