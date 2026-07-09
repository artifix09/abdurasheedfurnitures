<?php
/**
 * Template: 404 Not Found
 * Project: AbdurRashid Furnitures
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="main-content">

    <section class="error-404-section">
        <div class="container">

            <div class="error-404-num" aria-hidden="true">404</div>

            <h1><?php esc_html_e( 'Page Not Found', 'hello-elementor-child' ); ?></h1>

            <p><?php esc_html_e( 'The page you\'re looking for doesn\'t exist or has been moved.', 'hello-elementor-child' ); ?></p>

            <div class="error-404-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cta">
                    <?php esc_html_e( 'Go Home', 'hello-elementor-child' ); ?>
                </a>
                <a href="<?php echo esc_url( arf_shop_url() ); ?>" class="btn btn-outline">
                    <?php esc_html_e( 'Browse Products', 'hello-elementor-child' ); ?>
                </a>
            </div>

            <!-- Inline search nudge -->
            <div class="error-404-search">
                <p style="color:var(--color-text-muted); font-size:.9rem; margin-bottom:12px;">
                    <?php esc_html_e( 'Or search for what you need:', 'hello-elementor-child' ); ?>
                </p>
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-form">
                    <input type="search" name="s"
                           placeholder="<?php esc_attr_e( 'Search furniture…', 'hello-elementor-child' ); ?>"
                           autocomplete="off">
                    <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'hello-elementor-child' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
