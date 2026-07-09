<?php
/**
 * Template: Front Page (Homepage)
 * Description: Homepage with hero, featured products, categories, and testimonials
 * Project: AbdurRashid Furnitures
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="main-content">

    <!-- Hero Banner -->
    <section class="hero-banner" aria-label="<?php esc_attr_e('Hero banner', 'hello-elementor-child'); ?>">
        <img src="<?php echo esc_url( content_url('/uploads/2026/06/banner3.png') ); ?>"
             alt="<?php esc_attr_e('AbdurRashid Furnitures — Premium Furniture', 'hello-elementor-child'); ?>"
             class="hero-banner-img"
             loading="eager"
             fetchpriority="high"
             decoding="auto">
        <div class="hero-banner-cta">
            <a href="<?php echo esc_url( arf_shop_url() ); ?>" class="btn-cta">
                <?php esc_html_e('Shop Now', 'hello-elementor-child'); ?>
            </a>
        </div>
    </section>

    <!-- Featured Products -->
    <?php
    if (class_exists('WooCommerce')) :
        $fp_products = wc_get_products([
            'status'   => 'publish',
            'featured' => true,
            'limit'    => 4,
            'orderby'  => 'date',
            'order'    => 'DESC',
        ]);
        if (empty($fp_products)) {
            $fp_products = wc_get_products([
                'status'  => 'publish',
                'limit'   => 4,
                'orderby' => 'date',
                'order'   => 'DESC',
            ]);
        }
    endif;
    if (!empty($fp_products)) :
    ?>
    <section class="fp-section">
        <div class="fp-container">

            <div class="fp-header">
                <h2><?php esc_html_e('Featured Products', 'hello-elementor-child'); ?></h2>
                <p><?php esc_html_e('Handpicked pieces crafted for modern living', 'hello-elementor-child'); ?></p>
            </div>

            <div class="fp-grid">
                <?php foreach ($fp_products as $fp_prod) :
                    $fp_img_id  = $fp_prod->get_image_id();
                    $fp_img_url = $fp_img_id
                        ? wp_get_attachment_image_url($fp_img_id, 'arf-card')
                        : wc_placeholder_img_src('arf-card');
                    $fp_img_alt = $fp_img_id
                        ? (get_post_meta($fp_img_id, '_wp_attachment_image_alt', true) ?: $fp_prod->get_name())
                        : $fp_prod->get_name();

                    // Badge type
                    $fp_created = strtotime($fp_prod->get_date_created());
                    $fp_is_new  = $fp_created && (time() - $fp_created) < (45 * DAY_IN_SECONDS);
                    $fp_badge_label = '';
                    $fp_badge_class = '';
                    if ($fp_prod->is_on_sale()) {
                        $fp_badge_label = __('Sale', 'hello-elementor-child');
                        $fp_badge_class = 'fp-badge--sale';
                    } elseif ($fp_is_new) {
                        $fp_badge_label = __('New', 'hello-elementor-child');
                        $fp_badge_class = 'fp-badge--new';
                    } elseif ($fp_prod->is_featured()) {
                        $fp_badge_label = __('Best Seller', 'hello-elementor-child');
                        $fp_badge_class = 'fp-badge--best';
                    }

                    // Category name (first one)
                    $fp_cats      = wc_get_product_terms($fp_prod->get_id(), 'product_cat', ['number' => 1]);
                    $fp_cat_name  = !empty($fp_cats) ? $fp_cats[0]->name : '';

                    // Star rating (Unicode chars)
                    $fp_rating      = (float) $fp_prod->get_average_rating();
                    $fp_rev_count   = (int)   $fp_prod->get_review_count();
                    $fp_stars       = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $fp_stars .= $i <= round($fp_rating) ? '★' : '☆';
                    }
                    ?>
                    <div class="fp-card">
                        <div class="fp-card-img">

                            <?php if ($fp_badge_label) : ?>
                                <span class="fp-badge <?php echo esc_attr($fp_badge_class); ?>"><?php echo esc_html($fp_badge_label); ?></span>
                            <?php endif; ?>

                            <button class="fp-wishlist" aria-label="<?php esc_attr_e('Add to wishlist', 'hello-elementor-child'); ?>">
                                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>

                            <a href="<?php echo esc_url($fp_prod->get_permalink()); ?>" tabindex="-1" aria-hidden="true">
                                <img src="<?php echo esc_url($fp_img_url); ?>"
                                     alt="<?php echo esc_attr($fp_img_alt); ?>"
                                     width="600" height="690" loading="lazy">
                            </a>

                            <?php if ($fp_prod->is_in_stock()) : ?>
                            <div class="fp-cart-overlay">
                                <a href="<?php echo esc_url($fp_prod->add_to_cart_url()); ?>"
                                   class="fp-cart-btn add_to_cart_button<?php echo $fp_prod->supports('ajax_add_to_cart') ? ' ajax_add_to_cart' : ''; ?>"
                                   data-product_id="<?php echo esc_attr($fp_prod->get_id()); ?>"
                                   data-product_sku="<?php echo esc_attr($fp_prod->get_sku()); ?>"
                                   data-quantity="1" rel="nofollow"
                                   aria-label="<?php echo esc_attr($fp_prod->add_to_cart_description()); ?>">
                                    <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    <?php echo esc_html($fp_prod->add_to_cart_text()); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                        </div>
                        <div class="fp-card-info">
                            <?php if ($fp_cat_name) : ?>
                                <div class="fp-card-category"><?php echo esc_html($fp_cat_name); ?></div>
                            <?php endif; ?>
                            <h4 class="fp-card-name">
                                <a href="<?php echo esc_url($fp_prod->get_permalink()); ?>"><?php echo esc_html($fp_prod->get_name()); ?></a>
                            </h4>
                            <?php if ($fp_rating > 0) : ?>
                            <div class="fp-rating">
                                <span class="fp-stars" aria-label="<?php echo esc_attr(sprintf(__('Rated %.1f out of 5', 'woocommerce'), $fp_rating)); ?>"><?php echo esc_html($fp_stars); ?></span>
                                <?php if ($fp_rev_count > 0) : ?>
                                    <span class="fp-rating-count">(<?php echo esc_html($fp_rev_count); ?>)</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="fp-card-price"><?php echo wp_kses_post($fp_prod->get_price_html()); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="fp-view-all">
                <a href="<?php echo esc_url(arf_shop_url()); ?>">
                    <?php esc_html_e('View All Products', 'hello-elementor-child'); ?>
                    <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

        </div>
    </section>
    <?php endif; ?>

    <!-- Shop by Category -->
    <?php
    $sc_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 4,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'exclude'    => [get_option('default_product_cat')],
    ]);
    if ($sc_cats && !is_wp_error($sc_cats) && count($sc_cats) > 0) :
    ?>
    <section class="sc-section">
        <div class="sc-container">

            <div class="sc-header">
                <h2><?php esc_html_e('Shop by Category', 'hello-elementor-child'); ?></h2>
                <p><?php esc_html_e('Browse our curated collections for every room', 'hello-elementor-child'); ?></p>
            </div>

            <div class="sc-grid">
                <?php foreach ($sc_cats as $sc_cat) :
                    $sc_thumb_id  = get_term_meta($sc_cat->term_id, 'thumbnail_id', true);
                    $sc_img_url   = $sc_thumb_id
                        ? wp_get_attachment_image_url($sc_thumb_id, 'large')
                        : '';
                    $sc_img_alt   = esc_attr($sc_cat->name . ' ' . __('furniture collection', 'hello-elementor-child'));
                    $sc_count     = sprintf(
                        _n('%d Product', '%d Products', $sc_cat->count, 'hello-elementor-child'),
                        $sc_cat->count
                    );
                    ?>
                    <a href="<?php echo esc_url(get_term_link($sc_cat)); ?>" class="sc-card">
                        <div class="sc-card-img">
                            <?php if ($sc_img_url) : ?>
                                <img src="<?php echo esc_url($sc_img_url); ?>"
                                     alt="<?php echo $sc_img_alt; ?>"
                                     width="800" height="700" loading="lazy">
                            <?php else : ?>
                                <div class="sc-card-img--placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="sc-card-content">
                            <div class="sc-card-label"><?php esc_html_e('Collection', 'hello-elementor-child'); ?></div>
                            <h3><?php echo esc_html($sc_cat->name); ?></h3>
                            <div class="sc-card-count"><?php echo esc_html($sc_count); ?></div>
                            <span class="sc-card-link">
                                <?php esc_html_e('Shop Now', 'hello-elementor-child'); ?>
                                <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials -->
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('What Our Customers Say', 'hello-elementor-child'); ?></h2>
            </div>
            <div class="testimonials-slider-outer">
                <button class="slider-btn arf-slider-prev" aria-label="<?php esc_attr_e('Previous reviews', 'hello-elementor-child'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <div class="testimonials-slider-wrap">
                    <div class="testimonials-track">
                        <?php
                        $star_svg = '<svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';

                        $reviews = get_comments([
                            'post_type' => 'product',
                            'status'    => 'approve',
                            'number'    => 8,
                            'orderby'   => 'comment_date',
                            'order'     => 'DESC',
                        ]);

                        if (!empty($reviews)) :
                            foreach ($reviews as $rev) :
                                $rev_rating   = (int) get_comment_meta($rev->comment_ID, 'rating', true);
                                $product_name = get_the_title($rev->comment_post_ID);
                                $product_url  = get_permalink($rev->comment_post_ID);
                                ?>
                                <div class="testimonial-card-v2">
                                    <p class="testimonial-review-text"><?php echo wp_kses_post(wp_trim_words($rev->comment_content, 25, '...')); ?></p>
                                    <div class="testimonial-stars-v2" aria-label="<?php echo esc_attr(sprintf(__('Rated %d out of 5', 'woocommerce'), $rev_rating)); ?>">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="<?php echo $i <= $rev_rating ? 'star-filled' : 'star-empty'; ?>"><?php echo $star_svg; ?></span>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="testimonial-reviewer">
                                        <strong><?php echo esc_html($rev->comment_author); ?></strong>
                                        <span class="testimonial-check" aria-label="<?php esc_attr_e('Verified purchase', 'hello-elementor-child'); ?>">&#10003;</span>
                                    </div>
                                    <div class="testimonial-product">
                                        <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a>
                                    </div>
                                </div>
                            <?php endforeach;
                        else :
                            // Static fallback testimonials
                            $static_reviews = [
                                ['text' => 'The quality of craftsmanship is outstanding. Our sofa has been the centerpiece of our home for over a year now.', 'author' => 'Ahmed K.', 'product' => 'Luxe Velvet Sofa', 'rating' => 5],
                                ['text' => 'Beautiful furniture, exactly as shown on the website. The delivery team was professional and assembled everything perfectly.', 'author' => 'Fatima S.', 'product' => 'Royal Oak Bed Set', 'rating' => 5],
                                ['text' => 'Excellent customer service and prompt delivery. The bedroom set exceeded our expectations in quality and design.', 'author' => 'Hassan M.', 'product' => 'Elegance Wardrobe', 'rating' => 5],
                                ['text' => 'Great in quality and durability. Love the furniture — it has completely transformed our living room.', 'author' => 'Sara A.', 'product' => 'Walnut Coffee Table', 'rating' => 5],
                                ['text' => 'As always excellent product, delivery and installation service. Very professional packaging when the delivery team arrived.', 'author' => 'Bilal R.', 'product' => 'Heritage Dining Set', 'rating' => 5],
                            ];
                            foreach ($static_reviews as $rev) : ?>
                                <div class="testimonial-card-v2">
                                    <p class="testimonial-review-text"><?php echo esc_html($rev['text']); ?></p>
                                    <div class="testimonial-stars-v2">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="<?php echo $i <= $rev['rating'] ? 'star-filled' : 'star-empty'; ?>"><?php echo $star_svg; ?></span>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="testimonial-reviewer">
                                        <strong><?php echo esc_html($rev['author']); ?></strong>
                                        <span class="testimonial-check" aria-label="<?php esc_attr_e('Verified', 'hello-elementor-child'); ?>">&#10003;</span>
                                    </div>
                                    <div class="testimonial-product"><?php echo esc_html($rev['product']); ?></div>
                                </div>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>
                </div>

                <button class="slider-btn arf-slider-next" aria-label="<?php esc_attr_e('Next reviews', 'hello-elementor-child'); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
