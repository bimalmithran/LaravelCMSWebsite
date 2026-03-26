<?php

$menus = $storefront->getMenus();

$menuService = new \App\Services\MenuService($menus);
$menuUI = $menuService->buildMenuView();
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title><?= htmlspecialchars(
            $pageTitle ?? "Home || TT Devassy Jewellery",
        ) ?></title>
        <meta name="robots" content="noindex, follow" />
        <meta name="description" content="" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Favicon -->
        <link
            rel="shortcut icon"
            type="image/x-icon"
            href="assets/images/favicon.ico"
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
        <!-- <link rel="stylesheet" href="assets/css/style.min.css"> -->
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
                                            src="assets/images/logob.svg"
                                            alt="TT Devassy Jewellery"
                                        />
                                    </a>
                                </div>
                            </div>
                            <div
                                class="col-lg-7 d-none d-lg-block position-static"
                            >
                                <div class="main-menu_area">
                                    <nav>
                                    <?= $menuUI ?>
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
                                <form action="#" class="hm-searchbox">
                                    <input
                                        type="text"
                                        placeholder="Search for item..."
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
                                <li class="minicart-product">
                                    <a
                                        class="product-item_remove"
                                        href="javascript:void(0)"
                                        ><i class="ion-android-close"></i
                                    ></a>
                                    <div class="product-item_img">
                                        <img
                                            src="assets/images/product/small-size/2-1.jpg"
                                            alt="Hiraola's Product Image"
                                        />
                                    </div>
                                    <div class="product-item_content">
                                        <a
                                            class="product-item_title"
                                            href="shop-left-sidebar.php"
                                            >Pendant, Made of White Pl...</a
                                        >
                                        <span class="product-item_quantity"
                                            >1 x $120.80</span
                                        >
                                    </div>
                                </li>
                                <li class="minicart-product">
                                    <a
                                        class="product-item_remove"
                                        href="javascript:void(0)"
                                        ><i class="ion-android-close"></i
                                    ></a>
                                    <div class="product-item_img">
                                        <img
                                            src="assets/images/product/small-size/2-2.jpg"
                                            alt="Hiraola's Product Image"
                                        />
                                    </div>
                                    <div class="product-item_content">
                                        <a
                                            class="product-item_title"
                                            href="shop-left-sidebar.php"
                                            >Pendant, Made of White Pl...</a
                                        >
                                        <span class="product-item_quantity"
                                            >1 x $120.80</span
                                        >
                                    </div>
                                </li>
                                <li class="minicart-product">
                                    <a
                                        class="product-item_remove"
                                        href="javascript:void(0)"
                                        ><i class="ion-android-close"></i
                                    ></a>
                                    <div class="product-item_img">
                                        <img
                                            src="assets/images/product/small-size/2-3.jpg"
                                            alt="Hiraola's Product Image"
                                        />
                                    </div>
                                    <div class="product-item_content">
                                        <a
                                            class="product-item_title"
                                            href="shop-left-sidebar.php"
                                            >Pendant, Made of White Pl...</a
                                        >
                                        <span class="product-item_quantity"
                                            >1 x $120.80</span
                                        >
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="minicart-item_total">
                            <span>Subtotal</span>
                            <span class="ammount">$360.00</span>
                        </div>
                        <div class="minicart-btn_area">
                            <a
                                href="cart.php"
                                class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth"
                                >Minicart</a
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
                                <form action="#" class="hm-searchbox">
                                    <input
                                        type="text"
                                        placeholder="Search for item..."
                                    />
                                    <button class="search_btn" type="submit">
                                        <i class="ion-ios-search-strong"></i>
                                    </button>
                                </form>
                            </div>
                            <nav class="offcanvas-navigation">
                                <ul class="mobile-menu">
                                    <li class="menu-item-has-children active">
                                        <a href="#"
                                            ><span class="mm-text"
                                                >Home</span
                                            ></a
                                        >
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="index.php">
                                                    <span class="mm-text"
                                                        >Home One</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php">
                                                    <span class="mm-text"
                                                        >Home Two</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php">
                                                    <span class="mm-text"
                                                        >Home Three</span
                                                    >
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">
                                            <span class="mm-text">Shop</span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="menu-item-has-children">
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Grid View</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="shop-3-column.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Column
                                                                Three</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="shop-4-column.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Column
                                                                Four</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="shop-left-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Left
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="shop-right-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Right
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children">
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Shop List</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="shop-list-fullwidth.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Full
                                                                Width</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="shop-list-left-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Left
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="shop-list-right-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Right
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children">
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Single Product
                                                        Style</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="single-product-gallery-left.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Gallery
                                                                Left</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-gallery-right.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Gallery
                                                                Right</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-tab-style-left.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Tab Style
                                                                Left</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-tab-style-right.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Tab Style
                                                                Right</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-sticky-left.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Sticky
                                                                Left</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-sticky-right.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Sticky
                                                                Right</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children">
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Single Product
                                                        Type</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="single-product.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single
                                                                Product</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-sale.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single Product
                                                                Sale</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-group.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single Product
                                                                Group</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-variable.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single Product
                                                                Variable</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-affiliate.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single Product
                                                                Affiliate</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="single-product-slider.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Single Product
                                                                Slider</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">
                                            <span class="mm-text">Blog</span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li
                                                class="menu-item-has-children has-children"
                                            >
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Grid View</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="blog-2-column.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Column
                                                                Two</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-3-column.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Column
                                                                Three</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-left-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Left
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-right-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Right
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li
                                                class="menu-item-has-children has-children"
                                            >
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >List View</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="blog-list-fullwidth.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >List
                                                                Fullwidth</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-list-left-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >List Left
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-list-right-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >List Right
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li
                                                class="menu-item-has-children has-children"
                                            >
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Blog Details</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="blog-details-left-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Left
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-details-right-sidebar.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Right
                                                                Sidebar</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li
                                                class="menu-item-has-children has-children"
                                            >
                                                <a href="#">
                                                    <span class="mm-text"
                                                        >Blog Format</span
                                                    >
                                                </a>
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a
                                                            href="blog-gallery-format.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Gallery
                                                                Format</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-audio-format.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Audio
                                                                Format</span
                                                            >
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a
                                                            href="blog-video-format.php"
                                                        >
                                                            <span
                                                                class="mm-text"
                                                                >Video
                                                                Format</span
                                                            >
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">
                                            <span class="mm-text">Pages</span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="my-account.php">
                                                    <span class="mm-text"
                                                        >My Account</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="login-register.php">
                                                    <span class="mm-text"
                                                        >Login | Register</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="wishlist.php">
                                                    <span class="mm-text"
                                                        >Wishlist</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="cart.php">
                                                    <span class="mm-text"
                                                        >Cart</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="checkout.php">
                                                    <span class="mm-text"
                                                        >Checkout</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="compare.php">
                                                    <span class="mm-text"
                                                        >Compare</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="faq.php">
                                                    <span class="mm-text"
                                                        >FAQ</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="404.php">
                                                    <span class="mm-text"
                                                        >Error 404</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="coming-soon_page.php">
                                                    <span class="mm-text"
                                                        >Comming Soon</span
                                                    >
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                            <nav class="offcanvas-navigation user-setting_area">
                                <ul class="mobile-menu">
                                    <li class="menu-item-has-children active">
                                        <a href="#">
                                            <span class="mm-text"
                                                >User Setting
                                            </span>
                                        </a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="my-account.php">
                                                    <span class="mm-text"
                                                        >My Account</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="login-register.php">
                                                    <span class="mm-text"
                                                        >Login | Register</span
                                                    >
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#"
                                            ><span class="mm-text"
                                                >Currency</span
                                            ></a
                                        >
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >EUR €</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >USD $</span
                                                    >
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#"
                                            ><span class="mm-text"
                                                >Language</span
                                            ></a
                                        >
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >English</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >Français</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >Romanian</span
                                                    >
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <span class="mm-text"
                                                        >Japanese</span
                                                    >
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Hiraola's Header Main Area Two End Here -->
