<?php
/**
 * Template: Contact Page
 * Project: AbdurRashid Furnitures
 *
 * Contact form emails are sent to the WordPress Administration Email Address.
 * To change the recipient: WP Admin → Settings → General → Administration Email Address.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<main id="main-content">
<div class="contact-page">
<div class="container">

    <h1 class="contact-heading"><?php esc_html_e( 'Get in touch', 'hello-elementor-child' ); ?></h1>

    <div class="contact-top">

        <!-- ── Form ── -->
        <div class="contact-form-section">
            <h3><?php esc_html_e( 'Send a Message', 'hello-elementor-child' ); ?></h3>
            <p><?php esc_html_e( 'Have a question about a product, need a custom order, or want to visit our showroom? Fill in the form and we\'ll get back to you within 24 hours.', 'hello-elementor-child' ); ?></p>

            <form class="contact-form" id="arf-contact-form" novalidate>

                <div class="form-row">
                    <div class="form-field">
                        <input type="text" id="cf-name" name="cf_name" placeholder=" " required>
                        <label for="cf-name"><?php esc_html_e( 'Name', 'hello-elementor-child' ); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="email" id="cf-email" name="cf_email" placeholder=" " required>
                        <label for="cf-email"><?php esc_html_e( 'Email Address', 'hello-elementor-child' ); ?></label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <select id="cf-interest" name="cf_interest" data-empty="true">
                            <option value="" disabled selected hidden></option>
                            <option value="Living Room"><?php esc_html_e( 'Living Room', 'hello-elementor-child' ); ?></option>
                            <option value="Bedroom"><?php esc_html_e( 'Bedroom', 'hello-elementor-child' ); ?></option>
                            <option value="Dining"><?php esc_html_e( 'Dining', 'hello-elementor-child' ); ?></option>
                            <option value="Office"><?php esc_html_e( 'Office', 'hello-elementor-child' ); ?></option>
                            <option value="Custom Order"><?php esc_html_e( 'Custom Order', 'hello-elementor-child' ); ?></option>
                            <option value="Other"><?php esc_html_e( 'Other', 'hello-elementor-child' ); ?></option>
                        </select>
                        <label for="cf-interest"><?php esc_html_e( 'Interested In', 'hello-elementor-child' ); ?></label>
                    </div>
                    <div class="form-field">
                        <input type="tel" id="cf-phone" name="cf_phone" placeholder=" ">
                        <label for="cf-phone"><?php esc_html_e( 'Phone Number', 'hello-elementor-child' ); ?></label>
                    </div>
                </div>

                <div class="form-field">
                    <textarea id="cf-message" name="cf_message" rows="1" placeholder=" " required></textarea>
                    <label for="cf-message"><?php esc_html_e( 'Message', 'hello-elementor-child' ); ?></label>
                </div>

                <div class="form-submit">
                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        <?php esc_html_e( 'Submit', 'hello-elementor-child' ); ?>
                    </button>
                </div>

                <div class="form-feedback" id="form-feedback" aria-live="polite"></div>

            </form>
        </div>

        <!-- ── Contact Info ── -->
        <div class="contact-info">

            <div class="info-block">
                <h4><?php esc_html_e( 'Call Us', 'hello-elementor-child' ); ?></h4>
                <p><?php esc_html_e( 'Speak directly with our furniture consultants. Available 7 days a week during business hours.', 'hello-elementor-child' ); ?></p>
                <a href="tel:+923001234567" class="info-link">
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </span>
                    0300-1234567
                </a>
            </div>

            <div class="info-block">
                <h4><?php esc_html_e( 'Visit Our Showroom', 'hello-elementor-child' ); ?></h4>
                <p><?php esc_html_e( 'Come see our full range in person. Our consultants will help you find the perfect piece for your home.', 'hello-elementor-child' ); ?></p>
                <a href="#" class="info-link">
                    <span class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </span>
                    123 Furniture Avenue, Gulberg III, Lahore
                </a>
            </div>

            <div class="info-block">
                <h4><?php esc_html_e( 'Business Hours', 'hello-elementor-child' ); ?></h4>
                <p><?php esc_html_e( 'Our showroom is open 7 days a week. No appointment needed — drop in anytime.', 'hello-elementor-child' ); ?></p>
                <div class="info-hours">
                    <span><?php esc_html_e( 'Mon – Sat', 'hello-elementor-child' ); ?></span>
                    <span>10:00 AM – 8:00 PM</span>
                </div>
                <div class="info-hours">
                    <span><?php esc_html_e( 'Sunday', 'hello-elementor-child' ); ?></span>
                    <span>12:00 PM – 6:00 PM</span>
                </div>
            </div>

        </div>
    </div><!-- /.contact-top -->

    <hr class="contact-divider">

    <!-- ── FAQ ── -->
    <section class="faq-section" aria-label="<?php esc_attr_e( 'Frequently asked questions', 'hello-elementor-child' ); ?>">

        <div class="faq-heading-col">
            <p class="faq-label"><?php esc_html_e( 'FAQ', 'hello-elementor-child' ); ?></p>
            <h2 class="faq-title"><?php esc_html_e( 'Frequently asked questions.', 'hello-elementor-child' ); ?></h2>
        </div>

        <div class="faq-list">

            <div class="faq-item is-open">
                <button class="faq-question" aria-expanded="true">
                    <?php esc_html_e( 'Do you deliver across Pakistan?', 'hello-elementor-child' ); ?>
                    <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner"><?php esc_html_e( 'Yes, we offer nationwide delivery across Pakistan. Orders within Lahore are typically delivered in 1–2 working days. For other cities, delivery takes 3–5 working days depending on your location.', 'hello-elementor-child' ); ?></div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <?php esc_html_e( 'Can I customise the fabric or colour of a product?', 'hello-elementor-child' ); ?>
                    <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner"><?php esc_html_e( 'Absolutely! Most of our sofas, beds and upholstered chairs can be customised in your preferred fabric or colour. Contact us with your requirements and we will provide a quote. Custom orders are ready within 2–3 weeks.', 'hello-elementor-child' ); ?></div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <?php esc_html_e( 'What is your return and exchange policy?', 'hello-elementor-child' ); ?>
                    <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner"><?php esc_html_e( 'We offer a 7-day hassle-free return policy. The product must be unused, in its original packaging and in the same condition as delivered. Please contact our team within 7 days of receiving your order to initiate a return.', 'hello-elementor-child' ); ?></div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <?php esc_html_e( 'Do you provide assembly at home?', 'hello-elementor-child' ); ?>
                    <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner"><?php esc_html_e( 'Yes, free home assembly is included for all orders delivered within Lahore. For outstation orders, our team provides a detailed assembly guide and is available on call to help you through the process.', 'hello-elementor-child' ); ?></div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <?php esc_html_e( 'How do I track my order after placing it?', 'hello-elementor-child' ); ?>
                    <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner"><?php esc_html_e( 'Once your order is dispatched, you will receive an SMS and email with your order details. You can also call us directly at any time for a real-time update on your delivery status.', 'hello-elementor-child' ); ?></div>
                </div>
            </div>

        </div>
    </section>

</div>
</div>
</main>

<script>
(function () {
    'use strict';

    /* ── FAQ accordion ── */
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = btn.closest('.faq-item');
            var isOpen = item.classList.contains('is-open');
            document.querySelectorAll('.faq-item').forEach(function (i) {
                i.classList.remove('is-open');
                i.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ── Select float-label: mark non-empty when a value is chosen ── */
    document.querySelectorAll('.contact-form select').forEach(function (sel) {
        function sync() { sel.setAttribute('data-empty', sel.value === '' ? 'true' : 'false'); }
        sync();
        sel.addEventListener('change', sync);
    });

    /* ── Contact form AJAX submit ── */
    var form     = document.getElementById('arf-contact-form');
    var feedback = document.getElementById('form-feedback');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        feedback.textContent = '';
        feedback.className = 'form-feedback';

        var data = new FormData(form);
        data.append('action', 'arf_contact');
        data.append('nonce', <?php echo wp_json_encode( wp_create_nonce( 'arf_contact_nonce' ) ); ?>);

        fetch(<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            if (res.success) {
                feedback.textContent = res.data.message;
                feedback.classList.add('form-feedback--ok');
                form.reset();
                document.querySelectorAll('.contact-form select').forEach(function (s) {
                    s.setAttribute('data-empty', 'true');
                });
            } else {
                feedback.textContent = res.data.message;
                feedback.classList.add('form-feedback--err');
            }
        })
        .catch(function () {
            btn.disabled = false;
            feedback.textContent = <?php echo wp_json_encode( __( 'Something went wrong. Please try again.', 'hello-elementor-child' ) ); ?>;
            feedback.classList.add('form-feedback--err');
        });
    });
})();
</script>

<?php get_footer(); ?>
