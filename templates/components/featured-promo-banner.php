<?php
/** @var array<string, mixed>|null $featuredPromoBanner */

$featuredPromoBannerImage = trim((string) ($featuredPromoBanner["image_url"] ?? ""));
$featuredPromoBannerTitle = trim((string) ($featuredPromoBanner["title"] ?? ""));
$featuredPromoBannerSubtitle = trim((string) ($featuredPromoBanner["subtitle"] ?? ""));
$featuredPromoBannerDescription = trim(
    (string) ($featuredPromoBanner["description"] ?? ""),
);
$featuredPromoBannerPriceText = trim(
    (string) ($featuredPromoBanner["price_text"] ?? ""),
);
$featuredPromoBannerButtonText = trim(
    (string) ($featuredPromoBanner["button_text"] ?? ""),
);
$featuredPromoBannerActionUrl = trim((string) ($featuredPromoBanner["action_url"] ?? ""));

if (
    $featuredPromoBannerImage === "" &&
    $featuredPromoBannerTitle === "" &&
    $featuredPromoBannerSubtitle === "" &&
    $featuredPromoBannerDescription === "" &&
    $featuredPromoBannerPriceText === ""
) {
    return;
}
?>
<div class="static-banner_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div
                    class="static-banner-image"
                    style="background-image: <?= $featuredPromoBannerImage !== ""
                        ? "url('" . htmlspecialchars($featuredPromoBannerImage) . "')"
                        : "none" ?>;"
                >
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
