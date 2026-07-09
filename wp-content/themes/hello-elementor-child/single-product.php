<?php
/**
 * Template: Single Product — pd-* design (product_detail.html)
 * Project: AbdurRashid Furnitures
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) :
    the_post();
    global $product;
    if ( ! $product ) $product = wc_get_product( get_the_ID() );
    if ( ! $product ) continue;

    // ── Gallery ─────────────────────────────────────────────────
    $main_id     = $product->get_image_id();
    $gallery_ids = $product->get_gallery_image_ids();
    $all_ids     = array_filter( array_merge( [ $main_id ], $gallery_ids ) );
    $main_url    = $main_id
        ? wp_get_attachment_image_url( $main_id, 'large' )
        : wc_placeholder_img_src( 'large' );
    $main_alt    = $main_id
        ? ( get_post_meta( $main_id, '_wp_attachment_image_alt', true ) ?: $product->get_name() )
        : $product->get_name();

    // ── Discount ─────────────────────────────────────────────────
    $discount = 0;
    $regular  = (float) $product->get_regular_price();
    $sale     = (float) $product->get_sale_price();
    if ( $product->is_on_sale() && $regular > 0 && $sale > 0 ) {
        $discount = round( ( ( $regular - $sale ) / $regular ) * 100 );
    }

    // ── Rating ───────────────────────────────────────────────────
    $rating       = (float) $product->get_average_rating();
    $review_count = (int)   $product->get_review_count();

    // ── Category / tags ──────────────────────────────────────────
    $cat_terms = wc_get_product_terms( $product->get_id(), 'product_cat', [ 'number' => 1 ] );
    $first_cat = ! empty( $cat_terms ) ? $cat_terms[0] : null;
    $tag_terms = get_the_terms( $product->get_id(), 'product_tag' );

    $star_svg = '<svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
?>

<main id="main-content">

    <!-- ===== BREADCRUMB ===== -->
    <div class="container">
        <nav class="pd-breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo esc_url( arf_shop_url() ); ?>" aria-label="Back to shop">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo esc_url( arf_shop_url() ); ?>">Products</a>
            <?php if ( $first_cat ) : ?>
                <span>/</span>
                <a href="<?php echo esc_url( get_term_link( $first_cat ) ); ?>"><?php echo esc_html( $first_cat->name ); ?></a>
            <?php endif; ?>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </nav>
    </div>

    <!-- ===== PRODUCT TOP: Gallery + Info ===== -->
    <section class="container">
        <div class="pd-top">

            <!-- Gallery: main image -->
            <div class="pd-gallery">
                <div class="pd-main-img">
                    <?php if ( $discount > 0 ) : ?>
                        <div class="pdp-discount-badge" aria-label="<?php echo esc_attr( $discount . '% off' ); ?>">
                            <span><?php echo esc_html( $discount . '%' ); ?></span>
                            <span>OFF</span>
                        </div>
                    <?php endif; ?>
                    <img src="<?php echo esc_url( $main_url ); ?>"
                         alt="<?php echo esc_attr( $main_alt ); ?>"
                         id="pd-main-img"
                         width="800" height="880"
                         loading="eager">
                </div>
            </div>

            <!-- Product Info -->
            <div class="pd-info">

                <h1 class="pd-name"><?php the_title(); ?></h1>

                <?php if ( $rating > 0 ) : ?>
                <div class="pd-rating-row">
                    <div class="pd-rating-stars" aria-label="Rated <?php echo esc_attr( number_format( $rating, 1 ) ); ?> out of 5">
                        <?php for ( $i = 1; $i <= 5; $i++ ) :
                            $cls = $i <= $rating ? 'star-filled' : ( $i - 0.5 <= $rating ? 'star-half' : 'star-empty' );
                            echo '<span class="' . esc_attr( $cls ) . '">' . $star_svg . '</span>';
                        endfor; ?>
                    </div>
                    <span class="pd-rating-count">(<?php echo esc_html( $review_count ); ?> reviews)</span>
                </div>
                <?php endif; ?>

                <div class="pd-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

                <!-- Description accordion -->
                <?php
                $desc = $product->get_description() ?: $product->get_short_description();
                if ( $desc ) :
                ?>
                <div class="pd-accordion open" data-accordion>
                    <div class="pd-accordion-header" role="button" tabindex="0" aria-expanded="true">
                        Description
                        <svg class="pd-accordion-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="pd-accordion-body">
                        <div class="pd-accordion-content"><?php echo wp_kses_post( wpautop( $desc ) ); ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Key features from product tags -->
                <?php if ( $tag_terms && ! is_wp_error( $tag_terms ) ) : ?>
                <div class="pd-tags-label">Key Features</div>
                <div class="pd-tags">
                    <?php foreach ( $tag_terms as $tag ) : ?>
                        <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="pd-tag"><?php echo esc_html( $tag->name ); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Custom quantity selector (synced to WC form via JS) -->
                <?php if ( $product->is_in_stock() ) : ?>
                <?php
                    $max_qty  = $product->get_max_purchase_quantity();
                    $max_attr = ( $max_qty > 0 ) ? ' max="' . absint( $max_qty ) . '"' : '';
                ?>
                <div class="pd-qty-row">
                    <button class="pd-qty-btn" data-qty="minus" type="button" aria-label="<?php esc_attr_e( 'Decrease quantity', 'hello-elementor-child' ); ?>">&#8722;</button>
                    <input class="pd-qty-input" type="number" value="1" min="1"<?php echo $max_attr; ?> id="pd-qty-vis" aria-label="<?php esc_attr_e( 'Quantity', 'hello-elementor-child' ); ?>">
                    <button class="pd-qty-btn" data-qty="plus" type="button" aria-label="<?php esc_attr_e( 'Increase quantity', 'hello-elementor-child' ); ?>">&#43;</button>
                </div>
                <?php endif; ?>

                <!-- Action buttons: WC add-to-cart + Buy Now (simple products only) -->
                <div class="pd-actions">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                    <?php if ( $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                       class="pd-btn pd-btn-filled pd-buy-now"
                       data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                       data-checkout-url="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                       aria-label="<?php esc_attr_e( 'Buy Now', 'hello-elementor-child' ); ?>">
                        <?php esc_html_e( 'Buy Now', 'hello-elementor-child' ); ?>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Wishlist toggle -->
                <button class="pd-wishlist-toggle fp-wishlist"
                        type="button"
                        data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                        aria-label="<?php esc_attr_e( 'Add to wishlist', 'hello-elementor-child' ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    <span class="pd-wishlist-label"><?php esc_html_e( 'Save to Wishlist', 'hello-elementor-child' ); ?></span>
                </button>

            </div><!-- /.pd-info -->
        </div><!-- /.pd-top -->
    </section>

    <!-- ===== BOTTOM ROW: Thumbnails + Delivery ===== -->
    <section class="container">
        <div class="pd-bottom-row">

            <!-- Thumbnail strip -->
            <div>
                <div class="pd-thumbs">
                    <?php
                    $show_ids = ! empty( $all_ids ) ? $all_ids : ( $main_id ? [ $main_id ] : [] );
                    foreach ( $show_ids as $i => $img_id ) :
                        $t_url  = wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' );
                        $t_full = wp_get_attachment_image_url( $img_id, 'large' );
                        $t_alt  = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: $product->get_name();
                    ?>
                    <button class="pd-thumb <?php echo $i === 0 ? 'active' : ''; ?>"
                            type="button"
                            data-full="<?php echo esc_url( $t_full ); ?>"
                            aria-label="<?php echo esc_attr( sprintf( 'View image %d', $i + 1 ) ); ?>">
                        <img src="<?php echo esc_url( $t_url ); ?>"
                             alt="<?php echo esc_attr( $t_alt ); ?>"
                             width="200" height="200" loading="lazy">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Delivery Options accordion -->
            <div class="pd-delivery open" data-accordion>
                <div class="pd-delivery-header" role="button" tabindex="0" aria-expanded="true">
                    Delivery Options
                    <svg class="pd-delivery-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="pd-delivery-body">
                    <div class="pd-delivery-grid">
                        <div class="pd-delivery-card">
                            <div class="pd-delivery-icon">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                            </div>
                            <div>
                                <div class="pd-delivery-label">Discount</div>
                                <div class="pd-delivery-value">Flat 15% Off</div>
                            </div>
                        </div>
                        <div class="pd-delivery-card">
                            <div class="pd-delivery-icon">
                                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            </div>
                            <div>
                                <div class="pd-delivery-label">Payment</div>
                                <div class="pd-delivery-value">Cash on Delivery Available</div>
                            </div>
                        </div>
                        <div class="pd-delivery-card">
                            <div class="pd-delivery-icon">
                                <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            </div>
                            <div>
                                <div class="pd-delivery-label">Delivery Time</div>
                                <div class="pd-delivery-value">3&#8211;4 Working Days</div>
                            </div>
                        </div>
                        <div class="pd-delivery-card">
                            <div class="pd-delivery-icon">
                                <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                            </div>
                            <div>
                                <div class="pd-delivery-label">Return &amp; Warranty</div>
                                <div class="pd-delivery-value">7 Days Easy Return</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.pd-bottom-row -->
    </section>

    <!-- ===== RATING & REVIEWS ===== -->
    <?php if ( $review_count > 0 || comments_open() ) : ?>
    <section class="container pd-reviews-section">
        <h2 class="pd-reviews-title">Rating &amp; Reviews</h2>

        <?php if ( $rating > 0 ) : ?>
        <div class="pd-reviews-top">

            <!-- Big score -->
            <div>
                <div class="pd-score">
                    <span class="pd-score-num"><?php echo esc_html( number_format( $rating, 1 ) ); ?></span>
                    <span class="pd-score-max">/5</span>
                </div>
                <div class="pd-score-count">(<?php echo esc_html( $review_count ); ?> Reviews)</div>
            </div>

            <!-- Star distribution bars -->
            <div class="pd-star-bars">
                <?php
                $star_counts = [ 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 ];
                $all_revs    = get_comments( [
                    'post_id' => get_the_ID(),
                    'status'  => 'approve',
                    'type'    => 'review',
                ] );
                foreach ( $all_revs as $r ) {
                    $rv = (int) get_comment_meta( $r->comment_ID, 'rating', true );
                    if ( $rv >= 1 && $rv <= 5 ) $star_counts[ $rv ]++;
                }
                $max_count = max( $star_counts ) ?: 1;
                for ( $s = 5; $s >= 1; $s-- ) :
                    $pct = round( ( $star_counts[ $s ] / $max_count ) * 100 );
                ?>
                <div class="pd-star-row">
                    <span class="star">&#9733;</span>
                    <span class="num"><?php echo $s; ?></span>
                    <div class="pd-star-bar-track">
                        <div class="pd-star-bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%"></div>
                    </div>
                    <span class="dot"></span>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Write a review CTA -->
            <?php if ( comments_open() ) : ?>
            <div class="pd-review-cta">
                <div>
                    <h4>Review this product</h4>
                    <p>Share your thoughts with other customers</p>
                </div>
                <button class="pd-review-btn"
                        onclick="var el=document.getElementById('respond');if(el)el.scrollIntoView({behavior:'smooth'})">
                    Write a customer review
                </button>
            </div>
            <?php endif; ?>

        </div><!-- /.pd-reviews-top -->
        <?php endif; ?>

        <?php comments_template(); ?>

    </section>
    <?php endif; ?>

    <!-- ===== RELATED PRODUCTS ===== -->
    <?php
    $related_ids = wc_get_related_products( $product->get_id(), 8 );
    if ( ! empty( $related_ids ) ) :
        $rel_prods = array_filter( array_map( 'wc_get_product', $related_ids ) );
    ?>
    <section class="container pd-related-section">
        <h2 class="pd-related-title">You might also like</h2>
        <div class="pd-related-grid">
            <?php foreach ( $rel_prods as $rp ) :
                $r_img_id  = $rp->get_image_id();
                $r_img_url = $r_img_id
                    ? wp_get_attachment_image_url( $r_img_id, 'medium_large' )
                    : wc_placeholder_img_src();
                $r_img_alt = $r_img_id
                    ? ( get_post_meta( $r_img_id, '_wp_attachment_image_alt', true ) ?: $rp->get_name() )
                    : $rp->get_name();
                $r_rating  = (float) $rp->get_average_rating();
                $r_reviews = (int)   $rp->get_review_count();
                $r_stars   = '';
                for ( $i = 1; $i <= 5; $i++ ) {
                    $r_stars .= $i <= round( $r_rating ) ? '&#9733;' : '&#9734;';
                }
            ?>
            <div class="pd-rcard">
                <div class="pd-rcard-img">
                    <a href="<?php echo esc_url( $rp->get_permalink() ); ?>">
                        <img src="<?php echo esc_url( $r_img_url ); ?>"
                             alt="<?php echo esc_attr( $r_img_alt ); ?>"
                             width="400" height="400" loading="lazy">
                    </a>
                    <?php if ( $rp->is_in_stock() ) : ?>
                    <a href="<?php echo esc_url( $rp->add_to_cart_url() ); ?>"
                       class="pd-rcard-cart add_to_cart_button<?php echo $rp->supports( 'ajax_add_to_cart' ) ? ' ajax_add_to_cart' : ''; ?>"
                       data-product_id="<?php echo esc_attr( $rp->get_id() ); ?>"
                       data-quantity="1"
                       rel="nofollow"
                       aria-label="<?php echo esc_attr( $rp->add_to_cart_description() ); ?>">
                        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="pd-rcard-name">
                    <a href="<?php echo esc_url( $rp->get_permalink() ); ?>"><?php echo esc_html( $rp->get_name() ); ?></a>
                </div>
                <?php if ( $r_rating > 0 ) : ?>
                <div class="pd-rcard-meta">
                    <span class="stars" aria-label="Rated <?php echo esc_attr( $r_rating ); ?> out of 5"><?php echo $r_stars; ?></span>
                    <span>(<?php echo esc_html( $r_reviews ); ?>)</span>
                </div>
                <?php endif; ?>
                <div class="pd-rcard-price"><?php echo wp_kses_post( $rp->get_price_html() ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="pd-see-more">
            <a href="<?php echo esc_url( arf_shop_url() ); ?>">See more products</a>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php
endwhile;
get_footer();
?>
