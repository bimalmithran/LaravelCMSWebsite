<?php
/** @var \App\Services\StorefrontService  $storefront */
/** @var \App\Services\HeaderService      $headerService */
/** @var string|null                      $pageTitle */

$headerData      = $headerService->getHeaderData();
$menus           = $storefront->getMenus('header');
$menuService     = new \App\Services\MenuService($menus);
$headerCategories = $storefront->getCategories(null, false);

$logoUrl         = $headerData['logo_url'];
$faviconUrl      = $headerData['favicon_url'];
$siteName        = htmlspecialchars($headerData['site_name'], ENT_QUOTES);
$metaDescription = $pageMetaDescription ?? htmlspecialchars($headerData['meta_description'], ENT_QUOTES);
$defaultTitle    = $siteName !== '' ? $siteName : 'TT Devassy Jewellery';
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= htmlspecialchars($pageTitle ?? $defaultTitle) ?></title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="<?= $metaDescription ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon"
          href="<?= $faviconUrl !== '' ? htmlspecialchars($faviconUrl, ENT_QUOTES) : 'assets/images/favicon.ico' ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-stars.css">
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/timecircles.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/quick-view.css">
    <style>
        /* Search autocomplete dropdown */
        .search-autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            z-index: 9999;
            list-style: none;
            margin: 0;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.12);
            display: none;
            max-height: 360px;
            overflow-y: auto;
        }
        .search-autocomplete-dropdown li a {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        .search-autocomplete-dropdown li a:hover { background: #f8f8f8; }
        .search-ac-img { width: 44px; height: 44px; object-fit: cover; margin-right: 10px; flex-shrink: 0; }
        .search-ac-info { display: flex; flex-direction: column; }
        .search-ac-name { font-size: 13px; font-weight: 600; }
        .search-ac-price { font-size: 12px; color: #888; }
    </style>
</head>

<body class="template-color-1">

<div class="main-wrapper">

    <!-- Header -->
    <header class="header-main_area">

        <!-- Top bar: phone + account/language -->
        <div class="header-top_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ht-left_area">
                            <div class="header-shipping_area">
                                <ul>
                                    <li>
                                        <span>Telephone Enquiry:</span>
                                        <a href="tel:+91-0000000000"><?= $headerData['contact_phone'] ?? '' ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="ht-right_area">
                            <div class="ht-menu">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)">Currency <i class="fa fa-chevron-down"></i></a>
                                        <ul class="ht-dropdown ht-currency">
                                            <li class="active"><a href="javascript:void(0)">₹ Indian Rupee</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Language <i class="fa fa-chevron-down"></i></a>
                                        <ul class="ht-dropdown">
                                            <li class="active">
                                                <a href="javascript:void(0)">
                                                    <img src="assets/images/menu/icon/1.jpg" alt="English"> English
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php if (!empty($_SESSION['customer_token']) && !empty($_SESSION['customer_data'])): ?>
                                        <?php $__customer = $_SESSION['customer_data']; ?>
                                        <a href="my-account.php">
                                            <?= htmlspecialchars((string)($__customer['first_name'] ?? 'My Account'), ENT_QUOTES) ?>
                                            <i class="fa fa-chevron-down"></i>
                                        </a>
                                        <ul class="ht-dropdown ht-my_account">
                                            <li><a href="my-account.php">My Account</a></li>
                                            <li><a href="my-account.php?tab=orders">My Orders</a></li>
                                            <li class="active"><a href="logout.php">Sign Out</a></li>
                                        </ul>
                                        <?php else: ?>
                                        <a href="login.php">My Account <i class="fa fa-chevron-down"></i></a>
                                        <ul class="ht-dropdown ht-my_account">
                                            <li><a href="register.php">Register</a></li>
                                            <li class="active"><a href="login.php">Login</a></li>
                                        </ul>
                                        <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle: Logo + Search box (desktop only) -->
        <div class="header-middle_area d-none d-lg-block">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="header-logo">
                            <a href="index.php">
                                <img
                                    src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                                    alt="<?= $siteName ?>"
                                />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="hm-form_area">
                            <form action="shop.php" method="get" class="hm-searchbox">
                                <?php if (!empty($headerCategories)): ?>
                                <select class="nice-select select-search-category" name="category">
                                    <option value="">All</option>
                                    <?php foreach ($headerCategories as $cat): ?>
                                    <option value="<?= (int) $cat['id'] ?>">
                                        <?= htmlspecialchars((string) ($cat['name'] ?? '')) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php endif; ?>
                                <input
                                    type="text"
                                    name="q"
                                    placeholder="Search for jewellery..."
                                    autocomplete="off"
                                    data-search-input
                                    value="<?= htmlspecialchars((string) ($_GET['q'] ?? ''), ENT_QUOTES) ?>"
                                />
                                <button class="li-btn" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: sticky nav bar -->
        <div class="header-bottom_area header-sticky stick">
            <div class="container">
                <div class="row">
                    <!-- Mobile logo -->
                    <div class="col-md-4 col-sm-4 d-lg-none d-block" style="background-color:#FFFFFF">
                        <div class="header-logo">
                            <a href="index.php">
                                <img
                                    src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                                    alt="<?= $siteName ?>"
                                />
                            </a>
                        </div>
                    </div>
                    <!-- Desktop nav -->
                    <div class="col-lg-9 d-none d-lg-block position-static">
                        <div class="main-menu_area">
                            <nav>
                                <?= $menuService->buildMenuView() ?>
                            </nav>
                        </div>
                    </div>
                    <!-- Right icons -->
                    <div class="col-lg-3 col-md-8 col-sm-8">
                        <div class="header-right_area">
                            <ul>
                                <li>
                                    <a href="wishlist.php" class="wishlist-btn">
                                        <i class="ion-android-favorite-outline"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn color--white d-lg-none d-block">
                                        <i class="ion-navicon"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#miniCart" class="minicart-btn toolbar-btn">
                                        <i class="ion-bag"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini Cart offcanvas -->
        <div class="offcanvas-minicart_wrapper" id="miniCart">
            <div class="offcanvas-menu-inner">
                <a href="#" class="btn-close"><i class="ion-android-close"></i></a>
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
                    <a href="cart.php" class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth">View Cart</a>
                </div>
                <div class="minicart-btn_area">
                    <a href="checkout.php" class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth">Checkout</a>
                </div>
            </div>
        </div>

        <!-- Mobile menu offcanvas -->
        <div class="mobile-menu_wrapper" id="mobileMenu">
            <div class="offcanvas-menu-inner">
                <div class="container">
                    <a href="#" class="btn-close"><i class="ion-android-close"></i></a>
                    <!-- Mobile search -->
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
                    <!-- Mobile nav -->
                    <nav class="offcanvas-navigation">
                        <?= $menuService->buildMobileMenuView() ?>
                    </nav>
                </div>
            </div>
        </div>

    </header>
    <!-- Header End -->
