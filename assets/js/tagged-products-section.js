(function ($) {
    'use strict';

    var sliderSettingsByClass = {
        'hiraola-product-tab_slider-2': {
            infinite: true,
            arrows: true,
            dots: false,
            speed: 2000,
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow:
                '<button class="slick-prev"><i class="ion-ios-arrow-back"></i></button>',
            nextArrow:
                '<button class="slick-next"><i class="ion-ios-arrow-forward"></i></button>',
            responsive: [
                {
                    breakpoint: 1501,
                    settings: {
                        slidesToShow: 4,
                    },
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    },
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                    },
                },
            ],
        },
        'hiraola-product-tab_slider-3': {
            infinite: true,
            arrows: true,
            dots: false,
            speed: 2000,
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow:
                '<button class="slick-prev"><i class="ion-ios-arrow-back"></i></button>',
            nextArrow:
                '<button class="slick-next"><i class="ion-ios-arrow-forward"></i></button>',
            responsive: [
                {
                    breakpoint: 1501,
                    settings: {
                        slidesToShow: 4,
                    },
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    },
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                    },
                },
            ],
        },
    };

    function resolveSliderSettings($slider) {
        var sliderClass = Object.keys(sliderSettingsByClass).find(function (className) {
            return $slider.hasClass(className);
        });

        return sliderClass
            ? sliderSettingsByClass[sliderClass]
            : sliderSettingsByClass['hiraola-product-tab_slider-2'];
    }

    function initializeSlider($slider) {
        if (!$slider.length) {
            return;
        }

        if (
            $slider.find('.tagged-products-empty-state').length ||
            !$slider.find('.slide-item').length
        ) {
            return;
        }

        $slider.slick(resolveSliderSettings($slider));
    }

    function destroySlider($slider) {
        if ($slider.length && $slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }
    }

    function setLoadingState($section, isLoading) {
        $section.toggleClass('is-loading', isLoading);
        $section
            .find('[data-tagged-products-tab], [data-tagged-products-load-more]')
            .attr('aria-disabled', isLoading ? 'true' : 'false');
    }

    function updatePaginationState($section, payload) {
        var hasMore = !!payload.has_more;
        var nextPage = payload.next_page || '';
        var currentPage = payload.current_page || 1;
        var $loadMoreButton = $section.find('[data-tagged-products-load-more]');

        $section.attr('data-current-page', String(currentPage));
        $section.attr('data-next-page', String(nextPage));
        $section.attr('data-has-more', hasMore ? '1' : '0');

        if (!$loadMoreButton.length) {
            return;
        }

        $loadMoreButton.prop('hidden', !hasMore);
        $loadMoreButton.attr('data-next-page', String(nextPage));
    }

    function updateCategories($section, categoriesHtml) {
        var $tabsContainer = $section.find('[data-tagged-products-tabs]');

        if (!$tabsContainer.length || !categoriesHtml) {
            return;
        }

        $tabsContainer.html(categoriesHtml);
    }

    function replaceProducts($section, productsHtml, payload) {
        var $slider = $section.find('[data-tagged-products-slider]');

        destroySlider($slider);
        $slider.html(productsHtml || '');
        updatePaginationState($section, payload);
        initializeSlider($slider);
    }

    function appendProducts($section, productsHtml, payload) {
        var $slider = $section.find('[data-tagged-products-slider]');
        var $incomingItems = $(productsHtml || '');

        if (
            !$incomingItems.length ||
            $incomingItems.filter('.tagged-products-empty-state').length
        ) {
            updatePaginationState($section, payload);
            return;
        }

        destroySlider($slider);

        if ($slider.find('.tagged-products-empty-state').length) {
            $slider.empty();
        }

        $slider.append($incomingItems);
        updatePaginationState($section, payload);
        initializeSlider($slider);
    }

    function buildQuery($section, overrides) {
        var params = new URLSearchParams();
        var sectionId = $section.attr('id');
        var tag = $section.data('tag');
        var perPage = $section.data('per-page');
        var enableCategoryFilter =
            String($section.data('enable-category-filter')) === '1';
        var selectedCategoryId = $section.attr('data-selected-category-id');

        params.set('section_id', String(sectionId || ''));
        if (tag) {
            params.set('tag', String(tag));
        }
        params.set('per_page', String(perPage || 10));
        params.set(
            'include_categories',
            enableCategoryFilter ? '1' : '0'
        );

        if (selectedCategoryId) {
            params.set('category_id', selectedCategoryId);
        }

        Object.keys(overrides).forEach(function (key) {
            var value = overrides[key];

            if (value === null || value === undefined || value === '') {
                params.delete(key);
                return;
            }

            params.set(key, String(value));
        });

        return params.toString();
    }

    function fetchSection($section, overrides, mode) {
        var endpoint = $section.data('endpoint');

        if (!endpoint) {
            return;
        }

        setLoadingState($section, true);

        window
            .fetch(endpoint + '?' + buildQuery($section, overrides), {
                headers: {
                    Accept: 'application/json',
                },
            })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Failed to load tagged products');
                }

                return response.json();
            })
            .then(function (payload) {
                if (!payload.success || !payload.data) {
                    throw new Error('Invalid tagged products response');
                }

                if (mode === 'append') {
                    appendProducts($section, payload.data.products_html, payload.data);
                } else {
                    updateCategories($section, payload.data.categories_html);
                    replaceProducts($section, payload.data.products_html, payload.data);
                }

                if (typeof window.applyWishlistState === 'function') {
                    window.applyWishlistState();
                }

                $section.attr(
                    'data-selected-category-id',
                    String(payload.data.selected_category_id || '')
                );
            })
            .catch(function (error) {
                console.error(error);
            })
            .finally(function () {
                setLoadingState($section, false);
            });
    }

    $(function () {
        $('[data-tagged-products-section]').each(function () {
            var $section = $(this);
            var $slider = $section.find('[data-tagged-products-slider]');

            destroySlider($slider);
            initializeSlider($slider);

            $section.on('click', '[data-tagged-products-tab]', function (event) {
                var $tab = $(this);
                var categoryId = $tab.data('category-id');

                event.preventDefault();

                if (
                    $tab.attr('aria-disabled') === 'true' ||
                    String($section.data('enable-category-filter')) !== '1'
                ) {
                    return;
                }

                $section.attr('data-selected-category-id', String(categoryId || ''));
                fetchSection(
                    $section,
                    {
                        category_id: categoryId || '',
                        page: 1,
                    },
                    'replace'
                );
            });

            $section.on(
                'click',
                '[data-tagged-products-load-more]',
                function (event) {
                    var $button = $(this);
                    var nextPage = $section.attr('data-next-page');

                    event.preventDefault();

                    if (
                        $button.attr('aria-disabled') === 'true' ||
                        !nextPage
                    ) {
                        return;
                    }

                    fetchSection(
                        $section,
                        {
                            page: nextPage,
                        },
                        'append'
                    );
                }
            );
        });
    });
})(jQuery);
