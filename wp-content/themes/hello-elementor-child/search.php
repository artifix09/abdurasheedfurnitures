<?php
/**
 * Template: Search Results
 * Project: AbdurRashid Furnitures
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$search_query = get_search_query();
$found        = $wp_query->found_posts;
?>

<main id="main-content">

    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <?php if ( $search_query ) : ?>
            <h1><?php printf( esc_html__( 'Results for "%s"', 'hello-elementor-child' ), esc_html( $search_query ) ); ?></h1>
            <?php else : ?>
            <h1><?php esc_html_e( 'Search', 'hello-elementor-child' ); ?></h1>
            <?php endif; ?>
        </div>
    </div>

    <section class="section">
        <div class="container">

            <!-- Inline search form -->
            <div class="search-page-form">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" name="s"
                           placeholder="<?php esc_attr_e( 'Search furniture…', 'hello-elementor-child' ); ?>"
                           value="<?php echo esc_attr( $search_query ); ?>"
                           autocomplete="off">
                    <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'hello-elementor-child' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>
            </div>

            <?php if ( have_posts() ) : ?>

                <p class="search-result-count">
                    <?php printf(
                        esc_html( _n( '%s result found', '%s results found', $found, 'hello-elementor-child' ) ),
                        '<strong>' . number_format_i18n( $found ) . '</strong>'
                    ); ?>
                </p>

                <div class="fp-grid" style="margin-top:32px;">
                    <?php while ( have_posts() ) : the_post();
                        if ( class_exists( 'WooCommerce' ) && get_post_type() === 'product' ) {
                            global $product;
                            $product = wc_get_product( get_the_ID() );
                            if ( $product && $product->is_visible() ) {
                                wc_get_template_part( 'content', 'product' );
                            }
                        } else {
                            // Non-product results
                            ?>
                            <article class="search-result-post">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            </article>
                            <?php
                        }
                    endwhile; ?>
                </div>

                <?php the_posts_pagination( [ 'mid_size' => 2 ] ); ?>

            <?php else : ?>

                <div class="search-no-results">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <h3><?php esc_html_e( 'No results found', 'hello-elementor-child' ); ?></h3>
                    <?php if ( $search_query ) : ?>
                    <p><?php printf(
                        esc_html__( 'Nothing matched "%s". Try a different keyword.', 'hello-elementor-child' ),
                        esc_html( $search_query )
                    ); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( arf_shop_url() ); ?>" class="btn-cta">
                        <?php esc_html_e( 'Browse All Products', 'hello-elementor-child' ); ?>
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
