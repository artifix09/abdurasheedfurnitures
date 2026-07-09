<?php
/**
 * WooCommerce product card — fp-card structure (consistent across all pages)
 * Project: AbdurRashid Furnitures
 */
defined( 'ABSPATH' ) || exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;

// ── Badge label ──────────────────────────────────────────────
$badge       = '';
$badge_class = '';
$regular     = (float) $product->get_regular_price();
$sale        = (float) $product->get_sale_price();
$discount    = 0;

if ( $product->is_on_sale() && $regular > 0 && $sale > 0 ) {
    $discount    = round( ( ( $regular - $sale ) / $regular ) * 100 );
    $badge       = $discount . '% OFF';
    $badge_class = 'fp-badge--sale';
} elseif ( $product->is_featured() ) {
    $badge       = 'Best Seller';
    $badge_class = 'fp-badge--best';
} else {
    $created = strtotime( $product->get_date_created() );
    if ( $created && ( time() - $created ) < ( 45 * DAY_IN_SECONDS ) ) {
        $badge       = 'New';
        $badge_class = 'fp-badge--new';
    }
}

// ── Rating ───────────────────────────────────────────────────
$rating       = (float) $product->get_average_rating();
$review_count = (int)   $product->get_review_count();

// ── Category label ───────────────────────────────────────────
$cat_terms = wc_get_product_terms( $product->get_id(), 'product_cat', [ 'number' => 1 ] );
$cat_name  = ! empty( $cat_terms ) ? $cat_terms[0]->name : '';

// ── Star string ──────────────────────────────────────────────
$stars_html = '';
if ( $rating > 0 ) {
    for ( $i = 1; $i <= 5; $i++ ) {
        $stars_html .= $i <= round( $rating ) ? '&#9733;' : '&#9734;';
    }
}
?>

<div <?php wc_product_class( 'fp-card', $product ); ?>>

    <div class="fp-card-img">

        <?php if ( $badge ) : ?>
            <span class="fp-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $badge ); ?></span>
        <?php endif; ?>

        <button class="fp-wishlist" type="button"
                aria-label="<?php esc_attr_e( 'Add to wishlist', 'hello-elementor-child' ); ?>"
                data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </button>

        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" tabindex="-1" aria-hidden="true">
            <?php echo $product->get_image( 'arf-card', [
                'loading' => 'lazy',
                'class'   => 'fp-card-image',
            ] ); ?>
        </a>

        <?php if ( $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
        <div class="fp-cart-overlay">
            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
               class="fp-cart-btn add_to_cart_button ajax_add_to_cart"
               data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
               data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
               data-quantity="1"
               rel="nofollow"
               aria-label="<?php esc_attr_e( 'Add to cart', 'hello-elementor-child' ); ?>">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <?php esc_html_e( 'Add to Cart', 'hello-elementor-child' ); ?>
            </a>
        </div>
        <?php elseif ( $product->is_in_stock() ) : ?>
        <div class="fp-cart-overlay">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>"
               class="fp-cart-btn"
               aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <?php echo esc_html( $product->add_to_cart_text() ); ?>
            </a>
        </div>
        <?php else : ?>
        <div class="fp-cart-overlay">
            <span class="fp-cart-btn" style="opacity:.5;cursor:default;">Sold Out</span>
        </div>
        <?php endif; ?>

    </div><!-- /.fp-card-img -->

    <div class="fp-card-info">

        <?php if ( $cat_name ) : ?>
            <div class="fp-card-category"><?php echo esc_html( $cat_name ); ?></div>
        <?php endif; ?>

        <h4 class="fp-card-name">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
        </h4>

        <?php if ( $rating > 0 ) : ?>
        <div class="fp-rating">
            <span class="fp-stars" aria-label="Rated <?php echo esc_attr( number_format( $rating, 1 ) ); ?> out of 5"><?php echo $stars_html; ?></span>
            <?php if ( $review_count > 0 ) : ?>
                <span class="fp-rating-count">(<?php echo esc_html( $review_count ); ?>)</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="fp-card-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

    </div><!-- /.fp-card-info -->

</div>
