<?php
/**
 * functions.php — AbdurRashid Furnitures Child Theme
 * All functions prefixed with arf_
 */
if (!defined('ABSPATH')) exit;

// ── 1. ENQUEUE STYLES & SCRIPTS ──────────────────────────────

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        ['parent-style'],
        '1.0.0'
    );

    $css_file    = get_stylesheet_directory() . '/assets/css/main.css';
    $css_version = file_exists($css_file) ? filemtime($css_file) : '1.0.0';
    wp_enqueue_style(
        'arf-main-css',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        $css_version
    );

    $js_file    = get_stylesheet_directory() . '/assets/js/main.js';
    $js_version = file_exists($js_file) ? filemtime($js_file) : '1.0.0';
    wp_enqueue_script(
        'arf-main-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        $js_version,
        true
    );
});

// Remove jQuery on non-WooCommerce pages
add_action('wp_enqueue_scripts', function () {
    if (!is_admin() && !is_woocommerce() && !is_cart() && !is_checkout()) {
        wp_deregister_script('jquery');
    }
}, 100);

// Defer our main JS
add_filter('script_loader_tag', function ($tag, $handle) {
    if ('arf-main-js' === $handle) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    return $tag;
}, 10, 2);

// ── 2. THEME SETUP ───────────────────────────────────────────

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // High-resolution card thumbnail: 600×800 (3:4 portrait)
    add_image_size('arf-card', 600, 800, true);
});

// Set WooCommerce thumbnail dimensions to match our card ratio
add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
    return ['width' => 600, 'height' => 800, 'crop' => 1];
});

// Inline critical CSS override — neutralise Hello Elementor parent width constraints
add_action('wp_head', function () {
    echo '<style>
    .elementor-section-boxed > .elementor-container,
    .e-con-boxed > .e-con-inner { max-width: var(--container-max, 1200px) !important; }
    body.elementor-page .site-content, body.elementor-page .content-area { max-width: 100% !important; padding: 0 !important; }
    #page, #wrapper, .site-wrapper { overflow-x: hidden !important; }
    </style>' . "\n";
}, 1);

// ── 3. WOOCOMMERCE CUSTOMIZATIONS ────────────────────────────

// Remove default WC styles we replace with our own
add_filter('woocommerce_enqueue_styles', function ($styles) {
    // Keep WC cart fragments; remove layout styles we override
    unset($styles['woocommerce-layout']);
    unset($styles['woocommerce-smallscreen']);
    return $styles;
});

// ── 4. HELPER FUNCTIONS ──────────────────────────────────────

/**
 * Return a WooCommerce product category URL by slug.
 * Falls back to the shop page if the category doesn't exist yet.
 */
function arf_product_cat_url($slug) {
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term && !is_wp_error($term)) {
        return get_term_link($term);
    }
    return function_exists('wc_get_page_id')
        ? get_permalink(wc_get_page_id('shop'))
        : home_url('/shop/');
}

/**
 * Return the site logo image URL.
 */
function arf_logo_url() {
    return content_url('/uploads/2026/07/WhatsApp-Image-2026-07-08-at-12.23.52-AM.jpeg');
}

/**
 * Return the WooCommerce shop page URL safely.
 */
function arf_shop_url() {
    return function_exists('wc_get_page_id')
        ? get_permalink(wc_get_page_id('shop'))
        : home_url('/shop/');
}

/**
 * Return the WooCommerce cart URL safely.
 */
function arf_cart_url() {
    return function_exists('wc_get_cart_url')
        ? wc_get_cart_url()
        : home_url('/cart/');
}

/**
 * Return the WooCommerce my-account page URL safely.
 */
function arf_account_url() {
    return function_exists('wc_get_page_id')
        ? get_permalink(wc_get_page_id('myaccount'))
        : home_url('/my-account/');
}

// ── 5. PERFORMANCE ───────────────────────────────────────────

// Remove emoji bloat (~10 KB of scripts/styles)
add_action('init', function () {
    remove_action('wp_head',             'print_emoji_detection_script', 7);
    remove_action('wp_print_styles',     'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles',  'print_emoji_styles');
    remove_filter('the_content_feed',    'wp_staticize_emoji');
    remove_filter('comment_text_rss',    'wp_staticize_emoji');
    remove_filter('wp_mail',             'wp_staticize_emoji_for_email');
});

// Remove oEmbed discovery links
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// Remove WP shortlink
remove_action('wp_head', 'wp_shortlink_wp_head');

// Remove REST API link from head (not needed on frontend)
remove_action('wp_head', 'rest_output_link_wp_head');

// Register span.cart-count as a WC fragment so it refreshes after every AJAX add-to-cart.
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = (function_exists('WC') && WC()->cart)
        ? absint(WC()->cart->get_cart_contents_count())
        : 0;
    $fragments['span.cart-count'] = '<span class="cart-count">' . $count . '</span>';
    return $fragments;
});

// Add resource hints for Google Fonts already in <head>
add_filter('wp_resource_hints', function ($hints, $relation_type) {
    if ('preconnect' === $relation_type) {
        $hints[] = ['href' => 'https://fonts.googleapis.com', 'crossorigin' => 'anonymous'];
        $hints[] = ['href' => 'https://fonts.gstatic.com',   'crossorigin' => 'anonymous'];
    }
    return $hints;
}, 10, 2);

// WebP support declaration
add_filter('upload_mimes', function ($types) {
    $types['webp'] = 'image/webp';
    return $types;
});

// ── 6. SEO FOUNDATION ────────────────────────────────────────

// Product structured data (JSON-LD) — injected per product page
// RankMath handles the main schema; this adds extra product fields it may miss.
add_action('wp_head', function () {
    if (!is_singular('product') || !class_exists('WooCommerce')) return;
    // Use get_queried_object_id(): $product global is not yet hydrated when wp_head fires.
    $product = wc_get_product(get_queried_object_id());
    if (!($product instanceof WC_Product)) return;

    $name        = esc_js($product->get_name());
    $desc        = esc_js(wp_strip_all_tags($product->get_short_description() ?: $product->get_description()));
    $sku         = esc_js($product->get_sku());
    $price       = esc_js($product->get_price());
    $currency    = esc_js(get_woocommerce_currency());
    $availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
    $img_id      = $product->get_image_id();
    $img_url     = $img_id ? esc_url(wp_get_attachment_url($img_id)) : '';
    $url         = esc_url(get_permalink());
    $rating      = (float) $product->get_average_rating();
    $review_count = (int) $product->get_review_count();

    $schema = '{
      "@context":"https://schema.org/",
      "@type":"Product",
      "name":"' . $name . '",
      "description":"' . $desc . '",
      "url":"' . $url . '"' .
      ($sku   ? ',"sku":"' . $sku . '"' : '') .
      ($img_url ? ',"image":"' . $img_url . '"' : '') . ',
      "offers":{
        "@type":"Offer",
        "priceCurrency":"' . $currency . '",
        "price":"' . $price . '",
        "availability":"' . $availability . '",
        "url":"' . $url . '"
      }' .
      ($rating > 0 ? ',"aggregateRating":{"@type":"AggregateRating","ratingValue":"' . $rating . '","reviewCount":"' . $review_count . '"}' : '') . '
    }';

    echo '<script type="application/ld+json">' . $schema . '</script>' . "\n";
}, 5);

// Breadcrumb JSON-LD on all pages
add_action('wp_head', function () {
    $items = [];
    $pos   = 1;
    $items[] = '{"@type":"ListItem","position":' . $pos++ . ',"name":"Home","item":"' . esc_url(home_url('/')) . '"}';
    if (is_singular('product') && class_exists('WooCommerce')) {
        $items[] = '{"@type":"ListItem","position":' . $pos++ . ',"name":"Shop","item":"' . esc_url(arf_shop_url()) . '"}';
        $items[] = '{"@type":"ListItem","position":' . $pos . ',"name":"' . esc_js(get_the_title()) . '","item":"' . esc_url(get_permalink()) . '"}';
    } elseif (is_woocommerce()) {
        $items[] = '{"@type":"ListItem","position":' . $pos . ',"name":"Shop","item":"' . esc_url(arf_shop_url()) . '"}';
    }
    if (count($items) < 2) return;
    echo '<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[' . implode(',', $items) . ']}</script>' . "\n";
}, 5);

// ── 7. SECURITY HARDENING ────────────────────────────────────

// Remove WP version from head
add_filter('the_generator', '__return_empty_string');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Prevent user enumeration via /?author=N
add_action('template_redirect', function () {
    if (is_author()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
    if (isset($_GET['author']) && !is_admin()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

// Hide generic login error messages
add_filter('login_errors', function () {
    return esc_html__('Incorrect credentials. Please try again.', 'hello-elementor-child');
});

// Disable file editing from WP admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// ── 8. EMAIL SENDER IDENTITY ──────────────────────────────────
// Ensures all wp_mail() calls (WooCommerce + contact form) go out with a
// branded From address instead of the default server hostname.

add_filter( 'wp_mail_from', function () {
    return 'orders@abdulrasheedfurnitures.com';
} );
add_filter( 'wp_mail_from_name', function () {
    return 'AbdurRashid Furnitures';
} );

// ── 9. CONTACT FORM AJAX ──────────────────────────────────────
// Recipient = WP Administration Email (WP Admin → Settings → General → Administration Email Address)

add_action( 'wp_ajax_arf_contact',        'arf_contact_handler' );
add_action( 'wp_ajax_nopriv_arf_contact', 'arf_contact_handler' );
function arf_contact_handler() {
    check_ajax_referer( 'arf_contact_nonce', 'nonce' );

    // Rate-limit: 1 contact submission per IP per hour
    $ip_key = 'arf_contact_' . md5( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) ) );
    if ( get_transient( $ip_key ) ) {
        wp_send_json_error( [ 'message' => __( 'Too many submissions. Please wait before trying again.', 'hello-elementor-child' ) ] );
    }

    $name     = sanitize_text_field( wp_unslash( $_POST['cf_name']     ?? '' ) );
    $email    = sanitize_email(      wp_unslash( $_POST['cf_email']    ?? '' ) );
    $phone    = sanitize_text_field( wp_unslash( $_POST['cf_phone']    ?? '' ) );
    $interest = sanitize_text_field( wp_unslash( $_POST['cf_interest'] ?? '' ) );
    $message  = sanitize_textarea_field( wp_unslash( $_POST['cf_message'] ?? '' ) );

    if ( empty( $name ) || ! is_email( $email ) || empty( $message ) ) {
        wp_send_json_error( [ 'message' => __( 'Please fill in all required fields with valid information.', 'hello-elementor-child' ) ] );
    }

    set_transient( $ip_key, 1, HOUR_IN_SECONDS );

    $to      = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Contact Message from %s — AbdurRashid Furnitures', 'hello-elementor-child' ), $name );
    $body    = '<html><body style="font-family:sans-serif;color:#1A1A1A;">'
             . '<h2 style="color:#B8956A;">New Contact Message</h2>'
             . '<table style="border-collapse:collapse;width:100%;">'
             . '<tr><td style="padding:8px 0;font-weight:700;width:130px;">Name</td><td>' . esc_html( $name ) . '</td></tr>'
             . '<tr><td style="padding:8px 0;font-weight:700;">Email</td><td>' . esc_html( $email ) . '</td></tr>'
             . '<tr><td style="padding:8px 0;font-weight:700;">Phone</td><td>' . esc_html( $phone ?: '—' ) . '</td></tr>'
             . '<tr><td style="padding:8px 0;font-weight:700;">Interested In</td><td>' . esc_html( $interest ?: '—' ) . '</td></tr>'
             . '</table>'
             . '<p style="margin-top:16px;font-weight:700;">Message:</p>'
             . '<p style="background:#F9F6F2;padding:16px;border-left:4px solid #B8956A;">' . nl2br( esc_html( $message ) ) . '</p>'
             . '</body></html>';
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_send_json_success( [ 'message' => __( 'Your message has been sent. We will get back to you within 24 hours.', 'hello-elementor-child' ) ] );
    } else {
        wp_send_json_error( [ 'message' => __( 'Could not send your message. Please try again or call us directly.', 'hello-elementor-child' ) ] );
    }
}

// ── 10. NEWSLETTER AJAX ───────────────────────────────────────

add_action( 'wp_ajax_arf_newsletter',        'arf_newsletter_handler' );
add_action( 'wp_ajax_nopriv_arf_newsletter', 'arf_newsletter_handler' );
function arf_newsletter_handler() {
    check_ajax_referer( 'arf_newsletter_nonce', 'nonce' );

    $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'hello-elementor-child' ) ] );
    }

    // Idempotent: already subscribed → silent success
    $key = 'arf_nl_' . md5( $email );
    if ( get_transient( $key ) ) {
        wp_send_json_success( [ 'message' => __( 'You\'re already on the list!', 'hello-elementor-child' ) ] );
        return;
    }
    set_transient( $key, 1, DAY_IN_SECONDS );

    $subscribers = get_option( 'arf_newsletter_subscribers', [] );
    if ( ! in_array( $email, $subscribers, true ) ) {
        $subscribers[] = $email;
        update_option( 'arf_newsletter_subscribers', $subscribers, false );
    }

    // Notify admin of new subscriber
    wp_mail(
        get_option( 'admin_email' ),
        'New Newsletter Subscriber — AbdurRashid Furnitures',
        '<html><body style="font-family:sans-serif;"><p>New subscriber: <strong>' . esc_html( $email ) . '</strong></p></body></html>',
        [ 'Content-Type: text/html; charset=UTF-8' ]
    );

    wp_send_json_success( [ 'message' => __( 'Thank you for subscribing!', 'hello-elementor-child' ) ] );
}

// Output newsletter nonce to footer for JS use
add_action( 'wp_footer', function () {
    echo '<script>var arfData=' . wp_json_encode( [
        'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
        'newsletterNonce' => wp_create_nonce( 'arf_newsletter_nonce' ),
    ] ) . ';</script>' . "\n";
}, 1 );

// ── 11. WOOCOMMERCE ORDER FLOW ───────────────────────────────

// COD orders → set status to "processing" immediately so stock reduces and
// the "Customer Processing Order" email (with full order details) fires.
add_filter( 'woocommerce_cod_process_payment_order_status', function () {
    return 'processing';
} );

// Append COD payment instructions to the customer-facing order email.
add_action( 'woocommerce_email_after_order_table', function ( $order, $sent_to_admin, $plain_text ) {
    if ( $order->get_payment_method() !== 'cod' || $sent_to_admin || $plain_text ) return;
    echo '<div style="margin-top:24px;padding:16px 20px;background:#F9F6F2;border-left:4px solid #B8956A;">'
       . '<p style="margin:0 0 8px;font-weight:700;color:#1A1A1A;">Cash on Delivery — What Happens Next?</p>'
       . '<ol style="margin:0;padding-left:18px;color:#3A3A3A;line-height:1.8;">'
       . '<li>' . esc_html__( 'Our team will confirm your order within 24 hours.', 'hello-elementor-child' ) . '</li>'
       . '<li>' . esc_html__( 'We will call you before dispatch to confirm the delivery slot.', 'hello-elementor-child' ) . '</li>'
       . '<li>' . esc_html__( 'Please have the exact amount ready when our delivery team arrives.', 'hello-elementor-child' ) . '</li>'
       . '</ol>'
       . '<p style="margin:12px 0 0;font-size:.88em;color:#6B6B6B;">'
       . esc_html__( 'Questions? Call us: +92-300-1234567 or email orders@abdulrasheedfurnitures.com', 'hello-elementor-child' )
       . '</p></div>';
}, 10, 3 );

// ── 9. WISHLIST AJAX ─────────────────────────────────────────

// Returns product data (name, image, price, permalink) for an array of IDs.
// Used by page-wishlist.php to hydrate the grid from localStorage IDs.
add_action('wp_ajax_arf_wishlist_products',        'arf_wishlist_products_handler');
add_action('wp_ajax_nopriv_arf_wishlist_products', 'arf_wishlist_products_handler');
function arf_wishlist_products_handler() {
    check_ajax_referer('arf_wishlist_nonce', 'nonce');
    if (!class_exists('WooCommerce')) { wp_send_json_success([]); }

    $raw = isset($_POST['ids']) ? sanitize_text_field(wp_unslash($_POST['ids'])) : '';
    $ids = array_filter(array_map('absint', explode(',', $raw)));
    if (empty($ids)) { wp_send_json_success([]); }

    $data = [];
    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if (!$product || !$product->is_visible()) continue;
        $img_id    = $product->get_image_id();
        $cat_terms = wc_get_product_terms($id, 'product_cat', ['number' => 1]);
        $data[] = [
            'id'         => $product->get_id(),
            'name'       => esc_html($product->get_name()),
            'price_html' => wp_kses_post($product->get_price_html()),
            'permalink'  => esc_url(get_permalink($id)),
            'image'      => $img_id
                ? esc_url(wp_get_attachment_image_url($img_id, 'arf-card'))
                : esc_url(wc_placeholder_img_src('arf-card')),
            'image_alt'  => esc_attr($img_id
                ? (get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: $product->get_name())
                : $product->get_name()),
            'cat'        => !empty($cat_terms) ? esc_html($cat_terms[0]->name) : '',
        ];
    }
    wp_send_json_success($data);
}
