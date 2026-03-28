<?php
require_once __DIR__ . "/bootstrap.php";

$pageTitle  = "About Us || TT Devassy Jewellery";
$breadcrumb = "About Us";

// Load settings from API (same endpoint used by footer)
$settings = $storefront->getSettings();

$aboutIntro      = (string) ($settings['about_intro']          ?? 'Nestled in the heart of Kunnamkulam, TT Devassy Jewellery has been a trusted name in fine jewellery for generations.');
$aboutStory      = (string) ($settings['about_story']          ?? 'TT Devassy Jewellery was founded with a simple belief — that every person deserves jewellery that tells their story.');
$yearsLegacy     = (string) ($settings['about_years_legacy']   ?? '50');
$uniqueDesigns   = (string) ($settings['about_unique_designs'] ?? '1200');
$masterArtisans  = (string) ($settings['about_master_artisans'] ?? '25');
$happyCustomers  = (string) ($settings['about_happy_customers'] ?? '10000');
$aboutImage      = $storefront->resolveAssetUrl($settings['about_image'] ?? null) ?: 'assets/images/about-us/1.jpg';

$siteName = (string) ($settings['site_name'] ?? 'TT Devassy Jewellery');

require_once __DIR__ . "/templates/header-inner.php";
?>

<!-- Begin About Us Area -->
<div class="about-us-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-7 d-flex align-items-center">
                <div class="overview-content">
                    <h2>Welcome To <span><?= htmlspecialchars($siteName) ?></span></h2>
                    <p class="short_desc"><?= nl2br(htmlspecialchars($aboutIntro)) ?></p>
                    <div class="hiraola-about-us_btn-area" style="margin-top:20px;">
                        <a class="about-us_btn" href="shop.php">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-5">
                <div class="overview-img text-center img-hover_effect">
                    <a href="#">
                        <img class="img-full" src="<?= htmlspecialchars($aboutImage) ?>" alt="<?= htmlspecialchars($siteName) ?>">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Us Area End Here -->

<!-- Begin Project Countdown Area -->
<div class="project-count-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-count text-center">
                    <div class="count-icon">
                        <span class="ion-ios-briefcase-outline"></span>
                    </div>
                    <div class="count-title">
                        <h2 class="count"><?= htmlspecialchars($yearsLegacy) ?></h2>
                        <span>Years of Legacy</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-count text-center">
                    <div class="count-icon">
                        <span class="ion-ios-wineglass-outline"></span>
                    </div>
                    <div class="count-title">
                        <h2 class="count"><?= htmlspecialchars($uniqueDesigns) ?></h2>
                        <span>Unique Designs</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-count text-center">
                    <div class="count-icon">
                        <span class="ion-ios-lightbulb-outline"></span>
                    </div>
                    <div class="count-title">
                        <h2 class="count"><?= htmlspecialchars($masterArtisans) ?></h2>
                        <span>Master Artisans</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-count text-center">
                    <div class="count-icon">
                        <span class="ion-happy-outline"></span>
                    </div>
                    <div class="count-title">
                        <h2 class="count"><?= htmlspecialchars($happyCustomers) ?></h2>
                        <span>Happy Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Project Countdown Area End Here -->

<!-- Begin Why Choose Us Area -->
<div class="hiraola-product_area" style="padding: 50px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section_title-2">
                    <h4>Why Choose Us</h4>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 30px;">
            <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                <div style="text-align:center; padding: 30px 20px; border: 1px solid #eee; border-radius: 4px;">
                    <div style="font-size: 40px; color: #333; margin-bottom: 15px;">
                        <i class="ion-ios-star-outline"></i>
                    </div>
                    <h5 style="margin-bottom: 10px; font-size: 16px; font-weight: 600;">Hallmarked Gold</h5>
                    <p style="color: #666; font-size: 14px; line-height: 1.7;">
                        All our gold jewellery carries BIS hallmarking, guaranteeing purity and authenticity you can trust.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                <div style="text-align:center; padding: 30px 20px; border: 1px solid #eee; border-radius: 4px;">
                    <div style="font-size: 40px; color: #333; margin-bottom: 15px;">
                        <i class="ion-ios-loop-strong"></i>
                    </div>
                    <h5 style="margin-bottom: 10px; font-size: 16px; font-weight: 600;">Exchange & Buy-Back</h5>
                    <p style="color: #666; font-size: 14px; line-height: 1.7;">
                        We offer fair exchange and buy-back on all our jewellery so your investment retains its value.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                <div style="text-align:center; padding: 30px 20px; border: 1px solid #eee; border-radius: 4px;">
                    <div style="font-size: 40px; color: #333; margin-bottom: 15px;">
                        <i class="ion-ios-people-outline"></i>
                    </div>
                    <h5 style="margin-bottom: 10px; font-size: 16px; font-weight: 600;">Personalised Service</h5>
                    <p style="color: #666; font-size: 14px; line-height: 1.7;">
                        Our knowledgeable staff are dedicated to helping you find the perfect piece for every occasion.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Why Choose Us Area End Here -->

<!-- Begin Our Story Area -->
<div style="background: #f8f8f8; padding: 60px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" style="margin-bottom: 30px;">
                <div class="overview-img img-hover_effect">
                    <a href="#">
                        <img class="img-full" src="<?= htmlspecialchars($aboutImage) ?>" alt="Our Showroom">
                    </a>
                </div>
            </div>
            <div class="col-lg-6" style="padding-left: 40px;">
                <div class="overview-content">
                    <h2>Our <span>Story</span></h2>
                    <div style="color: #555; font-size: 14px; line-height: 1.8; margin-top: 15px;">
                        <?= nl2br(htmlspecialchars($aboutStory)) ?>
                    </div>
                    <div class="hiraola-about-us_btn-area" style="margin-top: 25px;">
                        <a class="about-us_btn" href="contact.php">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Our Story Area End Here -->

<?php require_once __DIR__ . "/templates/footer.php"; ?>
