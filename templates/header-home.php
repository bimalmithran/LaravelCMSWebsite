<?php
/** @var \App\Services\StorefrontService $storefront */
/** @var \App\Services\HeaderService $headerService */

$headerData = $headerService->getHeaderData();
$menus      = $storefront->getMenus('header');
$menuService = new \App\Services\MenuService($menus);

$logoUrl         = $headerData['logo_url'];
$faviconUrl      = $headerData['favicon_url'];
$siteName        = htmlspecialchars($headerData['site_name'], ENT_QUOTES);
$metaDescription = htmlspecialchars($headerData['meta_description'], ENT_QUOTES);
$defaultTitle    = $siteName !== '' ? "Home || {$siteName}" : 'Home';
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title><?= htmlspecialchars($pageTitle ?? $defaultTitle) ?></title>
        <meta name="robots" content="noindex, follow" />
        <meta name="description" content="<?= $metaDescription ?>" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Favicon -->
        <link
            rel="shortcut icon"
            type="image/x-icon"
            href="<?= $faviconUrl !== '' ? htmlspecialchars($faviconUrl, ENT_QUOTES) : 'assets/images/favicon.ico' ?>"
        />

        <!-- CSS
	============================================ -->

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <!-- Fontawesome -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css" />
        <!-- Fontawesome Star -->
        <link rel="stylesheet" href="assets/css/fontawesome-stars.css" />
        <!-- Ion Icon -->
        <link rel="stylesheet" href="assets/css/ionicons.min.css" />
        <!-- Slick CSS -->
        <link rel="stylesheet" href="assets/css/slick.min.css" />
        <!-- Animation -->
        <link rel="stylesheet" href="assets/css/animate.min.css" />
        <!-- jQuery Ui -->
        <link rel="stylesheet" href="assets/css/jquery-ui.min.css" />
        <!-- Nice Select -->
        <link rel="stylesheet" href="assets/css/nice-select.min.css" />
        <!-- Timecircles -->
        <link rel="stylesheet" href="assets/css/timecircles.min.css" />

        <!-- Main Style CSS -->
        <link rel="stylesheet" href="assets/css/style.css" />
        <!-- Quick View Overrides -->
        <link rel="stylesheet" href="assets/css/quick-view.css" />
    </head>

    <body class="template-color-2">
        <div class="main-wrapper">
            <!-- Begin Hiraola's Header Main Area Two -->
            <header class="header-main_area header-main_area-2">
                <div
                    class="header-bottom_area header-bottom_area-2 header-sticky stick"
                >
                    <div class="container-fliud">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-4">
                                <div class="header-logo">
                                    <a href="index.php">
                                        <img
                                            src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                                            alt="<?= $siteName ?>"
                                        />
                                    </a>
                                </div>
                            </div>
                            <div
                                class="col-lg-7 d-none d-lg-block position-static"
                            >
                                <div class="main-menu_area">
                                    <nav>
                                        <?= $menuService->buildMenuView() ?>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-8 col-sm-8">
                                <div class="header-right_area">
                                    <ul>
                                        <li>
                                            <a
                                                href="wishlist.php"
                                                class="wishlist-btn"
                                            >
                                                <i
                                                    class="ion-android-favorite-outline"
                                                ></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="#searchBar"
                                                class="search-btn toolbar-btn"
                                            >
                                                <i
                                                    class="ion-ios-search-strong"
                                                ></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="#mobileMenu"
                                                class="mobile-menu_btn toolbar-btn color--white d-lg-none d-block"
                                            >
                                                <i class="ion-navicon"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="#miniCart"
                                                class="minicart-btn toolbar-btn"
                                            >
                                                <i class="ion-bag"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="offcanvas-search_wrapper" id="searchBar">
                    <div class="offcanvas-menu-inner">
                        <div class="container">
                            <a href="#" class="btn-close"
                                ><i class="ion-android-close"></i
                            ></a>
                            <!-- Begin Offcanvas Search Area -->
                            <div class="offcanvas-search">
                                <form action="shop.php" method="get" class="hm-searchbox">
                                    <input
                                        type="text"
                                        name="q"
                                        placeholder="Search for item..."
                                        data-search-input
                                        autocomplete="off"
                                    />
                                    <button class="search_btn" type="submit">
                                        <i class="ion-ios-search-strong"></i>
                                    </button>
                                </form>
                            </div>
                            <!-- Offcanvas Search Area End Here -->
                        </div>
                    </div>
                </div>
                <div class="offcanvas-minicart_wrapper" id="miniCart">
                    <div class="offcanvas-menu-inner">
                        <a href="#" class="btn-close"
                            ><i class="ion-android-close"></i
                        ></a>
                        <div class="minicart-content">
                            <div class="minicart-heading">
                                <h4>Shopping Cart</h4>
                            </div>
                            <ul class="minicart-list">
                                <!-- Cart items are populated dynamically by cart JS -->
                            </ul>
                        </div>
                        <div class="minicart-item_total">
                            <span>Subtotal</span>
                            <span class="ammount"></span>
                        </div>
                        <div class="minicart-btn_area">
                            <a
                                href="cart.php"
                                class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth"
                                >View Cart</a
                            >
                        </div>
                        <div class="minicart-btn_area">
                            <a
                                href="checkout.php"
                                class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth"
                                >Checkout</a
                            >
                        </div>
                    </div>
                </div>
                <div class="mobile-menu_wrapper" id="mobileMenu">
                    <div class="offcanvas-menu-inner">
                        <div class="container">
                            <a href="#" class="btn-close"
                                ><i class="ion-android-close"></i
                            ></a>
                            <div class="offcanvas-inner_search">
                                <form action="shop.php" method="get" class="hm-searchbox">
                                    <input
                                        type="text"
                                        name="q"
                                        placeholder="Search for item..."
                                        data-search-input
                                        autocomplete="off"
                                    />
                                    <button class="search_btn" type="submit">
                                        <i class="ion-ios-search-strong"></i>
                                    </button>
                                </form>
                            </div>
                            <nav class="offcanvas-navigation">
                                <?= $menuService->buildMobileMenuView() ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Hiraola's Header Main Area Two End Here -->
