<?php
require_once __DIR__ . "/bootstrap.php";

$pageTitle  = "Contact Us || TT Devassy Jewellery";
$breadcrumb = "Contact Us";

// Pre-fill from session if logged in
$defaultName  = $customerData ? htmlspecialchars(trim(($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''))) : '';
$defaultEmail = $customerData ? htmlspecialchars($customerData['email'] ?? '') : '';

$footerData = $footerService->getFooterData();
$contactAddress = $footerData['contact_address'];
$contactPhone   = $footerData['contact_phone'];
$contactEmail   = $footerData['contact_email'];

require_once __DIR__ . "/templates/header-inner.php";
?>

<!-- Contact Main Page Area -->
<div class="contact-main-page">

    <!-- Google Map / Store Location Banner -->
    <div class="container" style="margin-bottom: 0; padding-top: 0;">
        <div style="width:100%; height: 350px; background: #f0ede8; display: flex; align-items: center; justify-content: center; border-radius: 4px; overflow: hidden;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3921.8!2d76.0666!3d10.6376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba7f3b3b5cf3a3b%3A0x1234567890abcdef!2sKunnamkulam%2C%20Kerala!5e0!3m2!1sen!2sin!4v1234567890"
                width="100%"
                height="350"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <div class="container">
        <div class="row" style="padding: 50px 0 60px;">

            <!-- Contact Info -->
            <div class="col-lg-5 offset-lg-1 col-md-12 order-1 order-lg-2" style="margin-bottom: 40px;">
                <div class="contact-page-side-content">
                    <h3 class="contact-page-title">Contact Us</h3>
                    <p class="contact-page-message">
                        We'd love to hear from you! Whether you have a question about a product, need help with
                        your order, or want to discuss a custom design, our team is here to help.
                    </p>

                    <?php if ($contactAddress !== ''): ?>
                    <div class="single-contact-block">
                        <h4><i class="fa fa-fax"></i> Address</h4>
                        <p><?= htmlspecialchars($contactAddress) ?></p>
                    </div>
                    <?php else: ?>
                    <div class="single-contact-block">
                        <h4><i class="fa fa-fax"></i> Address</h4>
                        <p>TT Devassy Jewellery, Main Road, Kunnamkulam, Kerala – 680503</p>
                    </div>
                    <?php endif; ?>

                    <?php if ($contactPhone !== ''): ?>
                    <div class="single-contact-block">
                        <h4><i class="fa fa-phone"></i> Phone</h4>
                        <p><a href="tel:<?= htmlspecialchars($contactPhone) ?>"><?= htmlspecialchars($contactPhone) ?></a></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($contactEmail !== ''): ?>
                    <div class="single-contact-block last-child">
                        <h4><i class="fa fa-envelope-o"></i> Email</h4>
                        <p><a href="mailto:<?= htmlspecialchars($contactEmail) ?>"><?= htmlspecialchars($contactEmail) ?></a></p>
                    </div>
                    <?php else: ?>
                    <div class="single-contact-block last-child">
                        <h4><i class="fa fa-envelope-o"></i> Email</h4>
                        <p>info@ttdevassyjewellery.com</p>
                    </div>
                    <?php endif; ?>

                    <!-- Store Hours -->
                    <div class="single-contact-block last-child" style="margin-top: 20px;">
                        <h4><i class="ion-ios-clock-outline"></i> Store Hours</h4>
                        <p>Monday – Saturday: 9:30 AM – 7:30 PM</p>
                        <p>Sunday: 10:00 AM – 5:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-6 col-md-12 order-2 order-lg-1">
                <div class="contact-form-content">
                    <h3 class="contact-page-title">Send Us a Message</h3>

                    <!-- Alert box -->
                    <div id="contact-alert" style="display:none; margin-bottom: 15px; padding: 12px 16px; border-radius: 4px; font-size: 14px;"></div>

                    <div class="contact-form">
                        <form id="contact-form" novalidate>
                            <div class="form-group">
                                <label>Your Name <span class="required">*</span></label>
                                <input type="text" id="con-name" name="con_name"
                                       value="<?= $defaultName ?>" placeholder="Enter your full name" required>
                            </div>
                            <div class="form-group">
                                <label>Your Email <span class="required">*</span></label>
                                <input type="email" id="con-email" name="con_email"
                                       value="<?= $defaultEmail ?>" placeholder="Enter your email address" required>
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" id="con-subject" name="con_subject"
                                       placeholder="e.g. Enquiry about gold necklace">
                            </div>
                            <div class="form-group form-group-2">
                                <label>Your Message <span class="required">*</span></label>
                                <textarea id="con-message" name="con_message" rows="5"
                                          placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="btn-contact-submit"
                                        class="hiraola-contact-form_btn">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Contact Main Page Area End Here -->

<?php require_once __DIR__ . "/templates/footer.php"; ?>

<script>
(function ($) {
    'use strict';

    function showAlert(msg, success) {
        var $el = $('#contact-alert');
        $el.text(msg)
           .css({
               display: 'block',
               background: success ? '#d4edda' : '#f8d7da',
               border:     '1px solid ' + (success ? '#c3e6cb' : '#f5c6cb'),
               color:      success ? '#155724' : '#721c24',
           });
        if (success) {
            $('html, body').animate({ scrollTop: $el.offset().top - 80 }, 400);
        }
    }

    $('#contact-form').on('submit', function (e) {
        e.preventDefault();

        var name    = $.trim($('#con-name').val());
        var email   = $.trim($('#con-email').val());
        var subject = $.trim($('#con-subject').val());
        var message = $.trim($('#con-message').val());

        if (name === '') {
            showAlert('Please enter your name.', false);
            $('#con-name').focus();
            return;
        }
        if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showAlert('Please enter a valid email address.', false);
            $('#con-email').focus();
            return;
        }
        if (message === '') {
            showAlert('Please enter your message.', false);
            $('#con-message').focus();
            return;
        }

        var $btn = $('#btn-contact-submit');
        $btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url:         'api/contact.php',
            type:        'POST',
            contentType: 'application/json',
            data:        JSON.stringify({ name: name, email: email, subject: subject, message: message }),
            success: function (res) {
                if (res && res.success) {
                    showAlert(res.message || 'Message sent! We will get back to you shortly.', true);
                    $('#contact-form')[0].reset();
                    $btn.prop('disabled', true).text('Message Sent');
                } else {
                    showAlert((res && res.message) || 'Could not send message. Please try again.', false);
                    $btn.prop('disabled', false).text('Send Message');
                }
            },
            error: function () {
                showAlert('Something went wrong. Please try again later.', false);
                $btn.prop('disabled', false).text('Send Message');
            }
        });
    });
}(jQuery));
</script>
