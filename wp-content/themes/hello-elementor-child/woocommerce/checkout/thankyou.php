<?php
/**
 * Order received (thank-you) page
 * Overrides WooCommerce default — adds branded COD instructions block.
 */
defined( 'ABSPATH' ) || exit;

$order = ! empty( $order ) ? $order : wc_get_order( absint( get_query_var( 'order-received' ) ) );
?>
<div class="woocommerce-order">

<?php if ( $order instanceof WC_Order ) : ?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
            <?php esc_html_e( 'Unfortunately your order cannot be processed. Please try again.', 'woocommerce' ); ?>
        </p>
        <p class="woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
                <?php esc_html_e( 'Pay', 'woocommerce' ); ?>
            </a>
        </p>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
            <?php echo wp_kses_post( apply_filters( 'woocommerce_thankyou_order_received_text',
                esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ) ); ?>
        </p>

        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
            <li class="woocommerce-order-overview__order order">
                <?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
                <strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
            </li>
            <li class="woocommerce-order-overview__date date">
                <?php esc_html_e( 'Date:', 'woocommerce' ); ?>
                <strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
            </li>
            <li class="woocommerce-order-overview__email email">
                <?php esc_html_e( 'Email:', 'woocommerce' ); ?>
                <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
            </li>
            <li class="woocommerce-order-overview__total total">
                <?php esc_html_e( 'Total:', 'woocommerce' ); ?>
                <strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
            </li>
            <?php if ( $order->get_payment_method_title() ) : ?>
            <li class="woocommerce-order-overview__payment-method method">
                <?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
                <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
            </li>
            <?php endif; ?>
        </ul>

        <?php if ( $order->get_payment_method() === 'cod' ) : ?>
        <div class="arf-cod-notice">
            <div class="arf-cod-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div>
                <h3><?php esc_html_e( 'Cash on Delivery — What Happens Next?', 'hello-elementor-child' ); ?></h3>
                <ol style="padding-left:18px;margin:8px 0 12px;line-height:1.8;font-size:.9rem;color:var(--color-text-secondary);">
                    <li><?php esc_html_e( 'Our team will confirm your order within 24 hours.', 'hello-elementor-child' ); ?></li>
                    <li><?php esc_html_e( 'We will call you before dispatch to confirm the delivery slot.', 'hello-elementor-child' ); ?></li>
                    <li><?php esc_html_e( 'Please have the exact amount ready when our delivery team arrives.', 'hello-elementor-child' ); ?></li>
                </ol>
                <p>
                    <?php printf(
                        esc_html__( 'Estimated delivery: %s', 'hello-elementor-child' ),
                        '<strong>' . esc_html__( '3–4 working days', 'hello-elementor-child' ) . '</strong>'
                    ); ?>
                </p>
                <p style="margin-top:8px;font-size:.85rem;">
                    <?php esc_html_e( 'Questions? Call us:', 'hello-elementor-child' ); ?>
                    <a href="tel:+923001234567">+92-300-1234567</a>
                    <?php esc_html_e( 'or email', 'hello-elementor-child' ); ?>
                    <a href="mailto:orders@abdulrasheedfurnitures.com">orders@abdulrasheedfurnitures.com</a>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

        <?php wc_get_template( 'order/order-details.php', [
            'order_id'       => $order->get_id(),
            'show_downloads' => $order->has_downloadable_item() && $order->is_download_permitted(),
        ] ); ?>

        <div class="arf-thankyou-actions">
            <a href="<?php echo esc_url( arf_shop_url() ); ?>" class="arf-btn-shop">
                <?php esc_html_e( 'Continue Shopping', 'hello-elementor-child' ); ?>
            </a>
            <?php if ( is_user_logged_in() ) : ?>
            <a href="<?php echo esc_url( arf_account_url() ); ?>" class="arf-btn-account">
                <?php esc_html_e( 'View My Orders', 'hello-elementor-child' ); ?>
            </a>
            <?php endif; ?>
        </div>

    <?php endif; ?>

<?php endif; ?>

</div>
