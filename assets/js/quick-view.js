(function ($) {
    'use strict';

    var MODAL_SELECTOR = '#exampleModalCenter';
    var API_ENDPOINT   = 'api/quick-view.php';

    // ── Slider helpers ────────────────────────────────────────────────────────

    function destroySlider($el) {
        if ($el.length && $el.hasClass('slick-initialized')) {
            $el.slick('unslick');
        }
    }

    function rebuildSliders(images) {
        var $modal      = $(MODAL_SELECTOR);
        var $mainSlider = $modal.find('.sp-img_slider-2');
        var $navSlider  = $modal.find('.sp-img_slider-nav');
        var fallback    = 'assets/images/product/medium-size/1-1.jpg';

        destroySlider($mainSlider);
        destroySlider($navSlider);

        $mainSlider.empty();
        $navSlider.empty();

        var srcs = (images && images.length) ? images : [fallback];

        srcs.forEach(function (src) {
            $mainSlider.append(
                $('<div>').addClass('single-slide').append(
                    $('<img>').attr({ src: src, alt: 'Product Image' })
                )
            );
            $navSlider.append(
                $('<div>').addClass('single-slide').append(
                    $('<img>').attr({ src: src, alt: 'Product Thumbnail' })
                )
            );
        });

        if (srcs.length > 1) {
            $mainSlider.slick({
                slidesToShow: 1,
                arrows:       false,
                fade:         true,
                draggable:    false,
                swipe:        false,
                asNavFor:     MODAL_SELECTOR + ' .sp-img_slider-nav',
            });
            $navSlider.slick({
                slidesToShow:  4,
                focusOnSelect: true,
                asNavFor:      MODAL_SELECTOR + ' .sp-img_slider-2',
                responsive: [
                    { breakpoint: 1201, settings: { slidesToShow: 2 } },
                    { breakpoint: 768,  settings: { slidesToShow: 3 } },
                    { breakpoint: 577,  settings: { slidesToShow: 3 } },
                    { breakpoint: 481,  settings: { slidesToShow: 2 } },
                    { breakpoint: 321,  settings: { slidesToShow: 2 } },
                ],
            });
        }
    }

    // ── DOM population ────────────────────────────────────────────────────────

    function populateModal(product) {
        var $modal = $(MODAL_SELECTOR);

        // Name
        $modal.find('#qv-name').text(product.name || '');

        // Rating stars
        var $ratingList = $modal.find('#qv-rating').empty();
        for (var i = 1; i <= 5; i++) {
            $ratingList.append(
                $('<li>')
                    .toggleClass('silver-color', i > product.rating)
                    .append($('<i>').addClass('fa fa-star-of-david'))
            );
        }

        // Price
        $modal.find('#qv-price-new').text(product.display_price || '');
        var $oldPrice = $modal.find('#qv-price-old');
        if (product.old_price) {
            $oldPrice.text(product.old_price).show();
        } else {
            $oldPrice.hide();
        }

        // Meta
        $modal.find('#qv-brand').text(product.brand || '—');
        $modal.find('#qv-sku').text(product.sku || '—');
        $modal.find('#qv-stock').text(product.stock > 0 ? 'In Stock' : 'Out of Stock');
        $modal.find('#qv-description').text(product.short_description || '');

        // Tags
        var $tags = $modal.find('#qv-tags').empty();
        if (product.tags && product.tags.length) {
            product.tags.forEach(function (tag, idx) {
                $tags.append($('<a>').attr('href', 'javascript:void(0)').text(tag));
                if (idx < product.tags.length - 1) {
                    $tags.append(document.createTextNode(', '));
                }
            });
        }

        // Images
        rebuildSliders(product.images || []);
    }

    // ── Loading state ─────────────────────────────────────────────────────────

    function showLoading() {
        var $modal = $(MODAL_SELECTOR);
        $modal.find('#qv-loading').show();
        $modal.find('#qv-content').hide();
    }

    function showContent() {
        var $modal = $(MODAL_SELECTOR);
        $modal.find('#qv-loading').hide();
        $modal.find('#qv-content').show();
    }

    // ── Fetch ─────────────────────────────────────────────────────────────────

    function loadProduct(productId) {
        showLoading();

        $.getJSON(API_ENDPOINT, { id: productId })
            .done(function (response) {
                if (response.success && response.data) {
                    populateModal(response.data);
                    showContent();
                } else {
                    $(MODAL_SELECTOR).modal('hide');
                }
            })
            .fail(function () {
                $(MODAL_SELECTOR).modal('hide');
            });
    }

    // ── Bootstrap modal event ─────────────────────────────────────────────────

    $(function () {
        // `show.bs.modal` fires before the modal becomes visible.
        // `event.relatedTarget` is the element that triggered the modal open.
        // The data-bs-toggle is on the <li.quick-view-btn>, so relatedTarget
        // is the <li>. We read data-product-id directly from it.
        $(document).on('show.bs.modal', MODAL_SELECTOR, function (event) {
            var $trigger  = $(event.relatedTarget);
            var productId = $trigger.data('product-id')
                || $trigger.closest('.quick-view-btn').data('product-id');

            if (!productId) {
                return;
            }

            loadProduct(parseInt(productId, 10));
        });
    });
})(jQuery);
