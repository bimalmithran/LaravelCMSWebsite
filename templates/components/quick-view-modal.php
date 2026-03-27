<?php /* Quick View Modal — content is populated dynamically via quick-view.js */ ?>
<!-- Begin Hiraola's Quick View Modal -->
<div class="modal fade modal-wrapper" id="exampleModalCenter">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button
                    type="button"
                    class="close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <!-- Loading state -->
                <div id="qv-loading" class="text-center py-5" style="display: none;">
                    <p>Loading...</p>
                </div>

                <!-- Product content (hidden until data is loaded) -->
                <div id="qv-content" class="modal-inner-area sp-area row" style="display: none;">
                    <div class="col-lg-5 col-md-5">
                        <div class="sp-img_area">
                            <div class="sp-img_slider-2 slick-img-slider hiraola-slick-slider arrow-type-two">
                                <!-- Slides injected by JS -->
                            </div>
                            <div class="sp-img_slider-nav slick-slider-nav hiraola-slick-slider arrow-type-two">
                                <!-- Nav slides injected by JS -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 col-md-6">
                        <div class="sp-content">
                            <div class="sp-heading">
                                <h5><a id="qv-name" href="#"></a></h5>
                            </div>
                            <div class="rating-box">
                                <ul id="qv-rating">
                                    <!-- Stars injected by JS -->
                                </ul>
                            </div>
                            <div class="price-box">
                                <span id="qv-price-new" class="new-price"></span>
                                <span id="qv-price-old" class="old-price" style="display: none;"></span>
                            </div>
                            <div class="list-item last-child">
                                <ul>
                                    <li>Brand<span id="qv-brand"></span></li>
                                    <li>Product Code: <span id="qv-sku"></span></li>
                                    <li>Availability: <span id="qv-stock"></span></li>
                                </ul>
                            </div>
                            <div id="qv-description" style="margin: 15px 0; font-size: 14px; line-height: 1.6;"></div>
                            <div class="quantity">
                                <label>Quantity</label>
                                <div class="cart-plus-minus">
                                    <input
                                        class="cart-plus-minus-box"
                                        value="1"
                                        type="text"
                                    />
                                    <div class="dec qtybutton">
                                        <i class="fa fa-angle-down"></i>
                                    </div>
                                    <div class="inc qtybutton">
                                        <i class="fa fa-angle-up"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="hiraola-group_btn">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" class="add-to_cart btn-add-to-cart" data-product-id="" data-qv-btn="cart">Add To Cart</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="btn-add-to-wishlist" data-product-id="" data-qv-btn="wishlist">
                                            <i class="ion-android-favorite-outline"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="compare.html">
                                            <i class="ion-ios-shuffle-strong"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="hiraola-tag-line">
                                <h6>Tags:</h6>
                                <span id="qv-tags"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Hiraola's Quick View Modal End Here -->
