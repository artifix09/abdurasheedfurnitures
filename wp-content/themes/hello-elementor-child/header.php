<?php
/**
 * Template: Header
 * Description: Global site header with navigation and mega menus
 * Project: AbdurRashid Furnitures
 */
if (!defined('ABSPATH')) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&family=Ranade:wght@400;600;700&display=swap" rel="stylesheet">
    <?php
    // Open Graph / Twitter Card meta
    $og_title = is_singular() ? get_the_title() : get_bloginfo('name');
    $og_desc  = is_singular() ? wp_trim_words(get_the_excerpt() ?: get_bloginfo('description'), 25) : get_bloginfo('description');
    $og_url   = is_singular() ? get_permalink() : home_url('/');
    $og_img   = '';
    if (is_singular('product') && class_exists('WooCommerce')) {
        // $product global may not be hydrated yet when header.php runs
        // (get_header() fires before the_post() in single-product.php)
        $og_product = wc_get_product(get_queried_object_id());
        if ($og_product instanceof WC_Product && $og_product->get_image_id()) {
            $og_img = wp_get_attachment_image_url($og_product->get_image_id(), 'large');
        }
    }
    if (!$og_img && has_post_thumbnail()) {
        $og_img = get_the_post_thumbnail_url(null, 'large');
    }
    if (!$og_img) {
        $og_img = get_stylesheet_directory_uri() . '/assets/img/og-default.jpg';
    }
    ?>
    <meta property="og:type"        content="<?php echo is_singular('product') ? 'product' : 'website'; ?>">
    <meta property="og:site_name"   content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    <meta property="og:title"       content="<?php echo esc_attr($og_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($og_desc); ?>">
    <meta property="og:url"         content="<?php echo esc_url($og_url); ?>">
    <?php if ($og_img) : ?>
    <meta property="og:image"       content="<?php echo esc_url($og_img); ?>">
    <?php endif; ?>
    <meta name="twitter:card"       content="summary_large_image">
    <meta name="twitter:title"      content="<?php echo esc_attr($og_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($og_desc); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'hello-elementor-child'); ?></a>

<!-- ====== LOGO ROW ====== -->
<div class="logo-row">
    <div class="container logo-row-inner">
        <button class="menu-toggle" aria-label="<?php esc_attr_e('Open navigation menu', 'hello-elementor-child'); ?>">
            <span></span><span></span><span></span>
        </button>
        <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo esc_url(arf_logo_url()); ?>"
                 alt="<?php esc_attr_e('AbdurRasheed Furnitures — Designed for Living', 'hello-elementor-child'); ?>"
                 width="1080" height="1032" decoding="async" fetchpriority="high">
        </a>
        <div class="header-icons">
            <a href="#" class="icon-btn" id="search-toggle" aria-label="<?php esc_attr_e('Search', 'hello-elementor-child'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </a>
            <a href="<?php echo esc_url(arf_account_url()); ?>" class="icon-btn" aria-label="<?php esc_attr_e('My account', 'hello-elementor-child'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </a>
            <a href="<?php echo esc_url( home_url('/wishlist/') ); ?>" class="icon-btn" aria-label="<?php esc_attr_e('Wishlist', 'hello-elementor-child'); ?>" id="header-wishlist-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <span class="wishlist-count" aria-label="wishlist items" style="display:none;">0</span>
            </a>
            <a href="<?php echo esc_url(arf_cart_url()); ?>" class="icon-btn" aria-label="<?php esc_attr_e('Shopping cart', 'hello-elementor-child'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span class="cart-count"><?php echo class_exists('WooCommerce') ? absint(WC()->cart->get_cart_contents_count()) : 0; ?></span>
            </a>
        </div>
    </div>
</div>

<!-- ====== NAVIGATION BAR ====== -->
<nav class="nav-bar" aria-label="<?php esc_attr_e('Primary navigation', 'hello-elementor-child'); ?>">
    <div class="container nav-inner">
        <ul class="nav-list">
            <li class="nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_product_category('living') ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(arf_product_cat_url('living')); ?>"><?php esc_html_e('Living Room', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_product_category('bedroom') ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(arf_product_cat_url('bedroom')); ?>"><?php esc_html_e('Bedroom', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_product_category('dining') ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(arf_product_cat_url('dining')); ?>"><?php esc_html_e('Dining', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_product_category('office') ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(arf_product_cat_url('office')); ?>"><?php esc_html_e('Office', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_shop() ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(arf_shop_url()); ?>"><?php esc_html_e('Shop All', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item <?php echo is_page('contact') ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'hello-elementor-child'); ?></a>
            </li>
            <li class="nav-item nav-sale">
                <a href="<?php echo esc_url(arf_shop_url()); ?>"><?php esc_html_e('Sale', 'hello-elementor-child'); ?></a>
            </li>
        </ul>
    </div>
</nav>

<!-- Mobile Overlay + Menu v2 -->
<div class="mobile-overlay" aria-hidden="true"></div>
<aside class="mobile-menu" aria-label="<?php esc_attr_e('Mobile navigation', 'hello-elementor-child'); ?>">

    <!-- Brand + Close -->
    <div class="mobile-menu-header">
        <span class="mobile-menu-brand">
            <img src="<?php echo esc_url(arf_logo_url()); ?>"
                 alt="<?php esc_attr_e('AbdurRasheed Furnitures', 'hello-elementor-child'); ?>"
                 width="1080" height="1032" decoding="async">
        </span>
        <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'hello-elementor-child'); ?>">&#10005;</button>
    </div>

    <!-- Quick actions: Search + Account -->
    <div class="mobile-menu-actions">
        <a href="#" class="mobile-action-btn" id="mobile-search-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <?php esc_html_e('Search', 'hello-elementor-child'); ?>
        </a>
        <a href="<?php echo esc_url(arf_account_url()); ?>" class="mobile-action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <?php esc_html_e('Account', 'hello-elementor-child'); ?>
        </a>
    </div>

    <!-- Scrollable nav -->
    <div class="mobile-menu-scroll">
        <nav>
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_product_cat_url('living')); ?>"><?php esc_html_e('Living Room', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_product_cat_url('bedroom')); ?>"><?php esc_html_e('Bedroom', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_product_cat_url('dining')); ?>"><?php esc_html_e('Dining', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_product_cat_url('office')); ?>"><?php esc_html_e('Office', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_shop_url()); ?>"><?php esc_html_e('Shop All', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'hello-elementor-child'); ?></a>
            <a href="<?php echo esc_url(arf_shop_url()); ?>" class="mobile-sale">
                <?php esc_html_e('Sale', 'hello-elementor-child'); ?>
                <span class="mobile-sale-badge"><?php esc_html_e('30% Off', 'hello-elementor-child'); ?></span>
            </a>
        </nav>
    </div>

    <!-- Footer CTA -->
    <div class="mobile-menu-footer">
        <a href="<?php echo esc_url(arf_cart_url()); ?>" class="btn-cta">
            <?php esc_html_e('View Cart', 'hello-elementor-child'); ?>
        </a>
    </div>

</aside>

<!-- Search Overlay -->
<div class="search-overlay" id="search-overlay" aria-hidden="true" aria-label="<?php esc_attr_e('Search', 'hello-elementor-child'); ?>">
    <div class="search-overlay-inner">
        <button class="search-overlay-close" id="search-overlay-close" aria-label="<?php esc_attr_e('Close search', 'hello-elementor-child'); ?>">&#10005;</button>
        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
            <input type="search" name="s"
                   id="search-overlay-input"
                   placeholder="<?php esc_attr_e('Search furniture…', 'hello-elementor-child'); ?>"
                   autocomplete="off"
                   value="<?php echo esc_attr( get_search_query() ); ?>">
            <button type="submit" aria-label="<?php esc_attr_e('Submit search', 'hello-elementor-child'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </form>
    </div>
</div>
