<?php
/** @var \App\Services\FooterService $footerService */
$footer = $footerService->getFooterData();

$logoUrl         = $footer['logo_url'];
$siteName        = htmlspecialchars($footer['site_name'], ENT_QUOTES);
$siteDescription = htmlspecialchars($footer['site_description'], ENT_QUOTES);
$address         = htmlspecialchars($footer['contact_address'], ENT_QUOTES);
$phone           = htmlspecialchars($footer['contact_phone'], ENT_QUOTES);
$email           = htmlspecialchars($footer['contact_email'], ENT_QUOTES);
$socialFacebook  = htmlspecialchars($footer['social']['facebook'], ENT_QUOTES);
$socialTwitter   = htmlspecialchars($footer['social']['twitter'], ENT_QUOTES);
$socialInstagram = htmlspecialchars($footer['social']['instagram'], ENT_QUOTES);
$productLinks    = $footer['footer_product_links'];
$policiesLinks   = $footer['footer_policies_links'] ?? [];
$bottomLinks     = $footer['footer_bottom_links'];
$copyrightYear   = date('Y');
?>

<!-- Begin Hiraola's Footer Area -->
<div class="hiraola-footer_area">
    <div class="footer-top_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-widgets_info">
                        <div class="footer-widgets_logo">
                            <a href="#">
                                <?php if ($logoUrl !== ''): ?>
                                    <img
                                        src="<?= htmlspecialchars($logoUrl, ENT_QUOTES) ?>"
                                        alt="<?= $siteName ?> Logo"
                                    />
                                <?php else: ?>
                                    <img
                                        src="assets/images/logob.svg"
                                        alt="<?= $siteName ?> Logo"
                                    />
                                <?php endif; ?>
                            </a>
                        </div>

                        <?php if ($siteDescription !== ''): ?>
                        <div class="widget-short_desc">
                            <p><?= $siteDescription ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="hiraola-social_link">
                            <ul>
                                <li class="facebook">
                                    <a
                                        href="<?= $socialFacebook !== '' ? $socialFacebook : '#' ?>"
                                        data-bs-toggle="tooltip"
                                        target="_blank"
                                        title="Facebook"
                                    >
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </li>
                                <li class="twitter">
                                    <a
                                        href="<?= $socialTwitter !== '' ? $socialTwitter : '#' ?>"
                                        data-bs-toggle="tooltip"
                                        target="_blank"
                                        title="Twitter"
                                    >
                                        <i class="fab fa-twitter-square"></i>
                                    </a>
                                </li>
                                <li class="instagram">
                                    <a
                                        href="<?= $socialInstagram !== '' ? $socialInstagram : '#' ?>"
                                        data-bs-toggle="tooltip"
                                        target="_blank"
                                        title="Instagram"
                                    >
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="footer-widgets_area">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="footer-widgets_title">
                                    <h6>Product</h6>
                                </div>
                                <div class="footer-widgets">
                                    <ul>
                                        <?php foreach ($productLinks as $link): ?>
                                        <li>
                                            <a href="<?= htmlspecialchars($link['url'] ?? '#', ENT_QUOTES) ?>">
                                                <?= htmlspecialchars($link['name'], ENT_QUOTES) ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="footer-widgets_title">
                                    <h6>Our Policies</h6>
                                </div>
                                <div class="footer-widgets">
                                    <ul>
                                        <?php foreach ($policiesLinks as $link): ?>
                                        <li>
                                            <a href="<?= htmlspecialchars($link['url'] ?? '#', ENT_QUOTES) ?>">
                                                <?= htmlspecialchars($link['name'], ENT_QUOTES) ?>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="footer-widgets_info">
                                    <div class="footer-widgets_title">
                                        <h6>About Us</h6>
                                    </div>
                                    <div class="widgets-essential_stuff">
                                        <ul>
                                            <?php if ($address !== ''): ?>
                                            <li class="hiraola-address">
                                                <i class="ion-ios-location"></i>
                                                <span>Address:</span>
                                                <?= $address ?>
                                            </li>
                                            <?php endif; ?>
                                            <?php if ($phone !== ''): ?>
                                            <li class="hiraola-phone">
                                                <i class="ion-ios-telephone"></i>
                                                <span>Call Us:</span>
                                                <a href="tel:<?= $phone ?>"><?= $phone ?></a>
                                            </li>
                                            <?php endif; ?>
                                            <?php if ($email !== ''): ?>
                                            <li class="hiraola-email">
                                                <i class="ion-android-mail"></i>
                                                <span>Email:</span>
                                                <a href="mailto:<?= $email ?>"><?= $email ?></a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-lg-6 offset-lg-3 text-center">
                    <div class="footer-widgets_area">
                        <div class="footer-widgets_title">
                            <h6>Sign Up For Newsletter</h6>
                        </div>
                        <div class="widget-short_desc">
                            <p>
                                Subscribe to our newsletters now and stay
                                up-to-date with new collections
                            </p>
                        </div>
                        <div class="newsletter-form_wrap">
                            <form
                                class="subscribe-form"
                                id="footer-newsletter-form"
                            >
                                <input
                                    class="newsletter-input"
                                    id="footer-newsletter-email"
                                    type="email"
                                    autocomplete="off"
                                    name="email"
                                    placeholder="Enter Your Email"
                                />
                                <button
                                    class="newsletter-btn"
                                    type="submit"
                                >
                                    <i class="ion-android-mail"></i>
                                </button>
                            </form>
                            <div class="mailchimp-alerts mt-3">
                                <div class="mailchimp-submitting"></div>
                                <div class="mailchimp-success"></div>
                                <div class="mailchimp-error"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom_area">
        <div class="container">
            <div class="footer-bottom_nav">
                <div class="row">
                    <?php if (!empty($bottomLinks)): ?>
                    <div class="col-lg-12">
                        <div class="footer-links">
                            <ul>
                                <?php foreach ($bottomLinks as $link): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($link['url'] ?? '#', ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($link['name'], ENT_QUOTES) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-12">
                        <div class="payment">
                            <a href="#">
                                <img
                                    src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                                    alt="<?= $siteName ?> Payment Method"
                                />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="copyright">
                            <span>
                                Copyright &copy; <?= $copyrightYear ?>
                                <a href="index.php"><?= $siteName ?></a>
                                All rights reserved.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hiraola's Footer Area End Here -->
<?php require __DIR__ . '/components/quick-view-modal.php'; ?>
</div>

<!-- JS
============================================ -->

<!-- jQuery JS -->
<script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
<script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
<!-- Modernizer JS -->
<script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>
<!-- Bootstrap JS -->
<script src="assets/js/vendor/bootstrap.bundle.min.js"></script>

<!-- Slick Slider JS -->
<script src="assets/js/plugins/slick.min.js"></script>
<!-- Countdown JS -->
<script src="assets/js/plugins/countdown.min.js"></script>
<!-- Barrating JS -->
<script src="assets/js/plugins/jquery.barrating.min.js"></script>
<!-- Counterup JS -->
<script src="assets/js/plugins/jquery.counterup.min.js"></script>
<!-- Waypoints -->
<script src="assets/js/plugins/waypoints.min.js"></script>
<!-- Nice Select JS -->
<script src="assets/js/plugins/jquery.nice-select.min.js"></script>
<!-- Sticky Sidebar JS -->
<script src="assets/js/plugins/jquery.sticky-sidebar.js"></script>
<!-- Jquery-ui JS -->
<script src="assets/js/plugins/jquery-ui.min.js"></script>
<!-- Scroll Top JS -->
<script src="assets/js/plugins/scroll-top.min.js"></script>
<!-- Theia Sticky Sidebar JS -->
<script src="assets/js/plugins/theia-sticky-sidebar.min.js"></script>
<!-- ElevateZoom JS -->
<script src="assets/js/plugins/jquery.elevateZoom-3.0.8.min.js"></script>
<!-- Timecircles JS -->
<script src="assets/js/plugins/timecircles.min.js"></script>

<!-- Quick View JS -->
<script src="assets/js/quick-view.js"></script>
<!-- Newsletter JS -->
<script src="assets/js/newsletter.js"></script>
<!-- Search Autocomplete JS -->
<script src="assets/js/search-autocomplete.js"></script>
<!-- Main JS -->
<script src="assets/js/main.js"></script>
<script src="assets/js/tagged-products-section.js"></script>
<!-- Cart / Wishlist JS -->
<script>
window.APP_CONFIG = {
    loggedIn:  <?= !empty($_SESSION['customer_token']) ? 'true' : 'false' ?>,
    loginUrl:  'login.php',
    apiOrigin: <?= json_encode(
        !empty($config['public_base_url'])
            ? rtrim($config['public_base_url'], '/')
            : rtrim(preg_replace('/\/api\/v1$/', '', ($config['api_base_url'] ?? '')), '/')
    ) ?>,
    currency:  <?= json_encode($currencySymbol ?? '₹') ?>
};
</script>
<script src="assets/js/shop.js"></script>
</body>
</html>
