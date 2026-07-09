<?php
/**
 * Template: Footer
 * Description: Global site footer
 * Project: AbdurRashid Furnitures
 */
if (!defined('ABSPATH')) exit;
?>

<!-- ====== GLOBAL FOOTER ====== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Col 1: Information -->
            <div class="footer-col">
                <h4><?php esc_html_e('Information', 'hello-elementor-child'); ?></h4>
                <a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About Us', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(arf_shop_url()); ?>"><?php esc_html_e('Product Catalogue', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/payments-shipping/')); ?>"><?php esc_html_e('Payments &amp; Shipping', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/terms-of-use/')); ?>"><?php esc_html_e('Terms of Use', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'hello-elementor-child'); ?></a>
            </div>

            <!-- Col 2: User Area -->
            <div class="footer-col">
                <h4><?php esc_html_e('User Area', 'hello-elementor-child'); ?></h4>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Customer Service', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/contact/#faq')); ?>"><?php esc_html_e('FAQs', 'hello-elementor-child'); ?></a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact Us', 'hello-elementor-child'); ?></a>
            </div>

            <!-- Col 3: Contact Info -->
            <div class="footer-col footer-contact">
                <h4><?php esc_html_e('Contact Info', 'hello-elementor-child'); ?></h4>
                <p>+92-300-1234567</p>
                <p><a href="mailto:orders@abdulrasheedfurnitures.com">orders@abdulrasheedfurnitures.com</a></p>
                <p style="margin-top:8px;font-size:.9rem;color:#4A5E58;">123 Furniture Avenue, Gulberg III<br>Lahore, Punjab 54000</p>
                <div class="footer-certified-label"><?php esc_html_e('Certified by', 'hello-elementor-child'); ?></div>
                <div class="footer-certified-badges">
                    <span>ISO<br>9001</span>
                    <span>Quality<br>Assured</span>
                    <span>PFC<br>Certified</span>
                </div>
            </div>

            <!-- Col 4: Social + Newsletter -->
            <div class="footer-col footer-social">
                <h4><?php esc_html_e('Follow Us', 'hello-elementor-child'); ?></h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
                    <a href="#" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg></a>
                    <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
                    <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.43zM9.75 15.02V8.48l5.75 3.27-5.75 3.27z"/></svg></a>
                    <a href="#" aria-label="Pinterest"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0a12 12 0 00-4.37 23.17c-.1-.94-.2-2.4.04-3.44l1.4-5.96s-.36-.72-.36-1.78c0-1.66.97-2.9 2.17-2.9 1.02 0 1.52.77 1.52 1.7 0 1.03-.66 2.58-1 4.01-.28 1.2.6 2.17 1.78 2.17 2.14 0 3.78-2.26 3.78-5.52 0-2.89-2.08-4.9-5.04-4.9-3.43 0-5.45 2.58-5.45 5.24 0 1.04.4 2.15.9 2.75a.36.36 0 01.08.35l-.34 1.36c-.05.22-.18.27-.4.16-1.5-.7-2.43-2.9-2.43-4.67 0-3.8 2.76-7.3 7.96-7.3 4.18 0 7.42 2.98 7.42 6.96 0 4.15-2.62 7.5-6.25 7.5-1.22 0-2.37-.64-2.76-1.38l-.75 2.86c-.27 1.04-1 2.35-1.5 3.15A12 12 0 1012 0z"/></svg></a>
                    <a href="#" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .55.04.81.11v-3.5a6.37 6.37 0 00-.81-.05A6.34 6.34 0 003.15 15a6.34 6.34 0 0010.86 4.48V12.5a8.27 8.27 0 005.58 2.17V11.2a4.83 4.83 0 01-3.59-1.53V6.69h3.59z"/></svg></a>
                    <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 2a2 2 0 110 4 2 2 0 010-4z"/></svg></a>
                </div>
                <div class="footer-newsletter">
                    <h4><?php esc_html_e('Join Our Mailing List', 'hello-elementor-child'); ?></h4>
                    <p><?php esc_html_e('Subscribe to our newsletter to get the latest news and product updates directly to your email.', 'hello-elementor-child'); ?></p>
                    <label for="footer-email"><?php esc_html_e('Email', 'hello-elementor-child'); ?></label>
                    <div class="newsletter-input">
                        <input type="email" id="footer-email" placeholder="<?php esc_attr_e('Enter your mail address', 'hello-elementor-child'); ?>">
                        <button type="button" aria-label="<?php esc_attr_e('Subscribe', 'hello-elementor-child'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html(date('Y')); ?> <?php esc_html_e('AbdurRashid Furnitures', 'hello-elementor-child'); ?></p>
            <div class="payment-icons">
                <span>AMEX</span>
                <span>MASTERCARD</span>
                <span>VISA</span>
                <span>JAZZCASH</span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
