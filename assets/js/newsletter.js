(function ($) {
    'use strict';

    var FORM_SELECTOR      = '#footer-newsletter-form';
    var EMAIL_SELECTOR     = '#footer-newsletter-email';
    var SUBMITTING_CLASS   = '.mailchimp-submitting';
    var SUCCESS_CLASS      = '.mailchimp-success';
    var ERROR_CLASS        = '.mailchimp-error';
    var API_ENDPOINT       = 'api/newsletter-subscribe.php';

    function clearMessages($form) {
        $form.find(SUBMITTING_CLASS).text('');
        $form.find(SUCCESS_CLASS).text('');
        $form.find(ERROR_CLASS).text('');
    }

    function showSubmitting($form) {
        clearMessages($form);
        $form.find(SUBMITTING_CLASS).text('Subscribing...');
    }

    function showSuccess($form, message) {
        clearMessages($form);
        $form.find(SUCCESS_CLASS).text(message);
        $form.find(EMAIL_SELECTOR).val('');
    }

    function showError($form, message) {
        clearMessages($form);
        $form.find(ERROR_CLASS).text(message);
    }

    $(document).on('submit', FORM_SELECTOR, function (e) {
        e.preventDefault();

        var $form  = $(this);
        var $email = $form.find(EMAIL_SELECTOR);
        var email  = $.trim($email.val());

        if (email === '') {
            showError($form, 'Please enter your email address.');
            return;
        }

        showSubmitting($form);

        $.post(API_ENDPOINT, { email: email })
            .done(function (response) {
                if (response && response.success) {
                    showSuccess($form, response.message || 'Thank you for subscribing!');
                } else {
                    showError($form, (response && response.message) || 'Subscription failed. Please try again.');
                }
            })
            .fail(function (xhr) {
                var response = xhr.responseJSON;
                showError($form, (response && response.message) || 'Something went wrong. Please try again.');
            });
    });
}(jQuery));
