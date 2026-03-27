/**
 * Live search autocomplete.
 *
 * Attaches to any <input> with [data-search-input] and renders a dropdown
 * below it.  On item click it navigates to product.php?id=X.
 * On form submit it navigates to shop.php?q=...
 */
(function ($) {
    'use strict';

    var DEBOUNCE_MS = 300;
    var MIN_CHARS   = 2;
    var ENDPOINT    = 'api/search.php';

    function buildDropdown($input) {
        var $wrap = $input.closest('form');
        var $drop = $('<ul class="search-autocomplete-dropdown"></ul>');
        $wrap.css('position', 'relative').append($drop);
        return $drop;
    }

    function renderResults($drop, results) {
        $drop.empty();
        if (!results || results.length === 0) {
            $drop.hide();
            return;
        }
        $.each(results, function (_, item) {
            var $li = $('<li></li>');
            var $a  = $('<a></a>').attr('href', item.link);
            var $img = $('<img class="search-ac-img" />').attr('src', item.image).attr('alt', item.name);
            var $info = $('<span class="search-ac-info"></span>');
            $('<span class="search-ac-name"></span>').text(item.name).appendTo($info);
            $('<span class="search-ac-price"></span>').text(item.price).appendTo($info);
            $a.append($img).append($info);
            $li.append($a);
            $drop.append($li);
        });
        $drop.show();
    }

    function init() {
        $('[data-search-input]').each(function () {
            var $input = $(this);
            var $drop  = buildDropdown($input);
            var timer  = null;

            $input.on('input', function () {
                clearTimeout(timer);
                var q = $.trim($input.val());
                if (q.length < MIN_CHARS) {
                    $drop.hide().empty();
                    return;
                }
                timer = setTimeout(function () {
                    $.get(ENDPOINT, { q: q, limit: 8 }, function (res) {
                        if (res && res.success) {
                            renderResults($drop, res.data);
                        }
                    });
                }, DEBOUNCE_MS);
            });

            // Hide dropdown when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest($input.closest('form')).length) {
                    $drop.hide();
                }
            });

            // On form submit, go to shop.php
            $input.closest('form').on('submit', function (e) {
                e.preventDefault();
                var q = $.trim($input.val());
                if (q) {
                    window.location.href = 'shop.php?q=' + encodeURIComponent(q);
                } else {
                    window.location.href = 'shop.php';
                }
            });
        });
    }

    $(document).ready(init);

}(jQuery));
