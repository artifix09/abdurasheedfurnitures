<?php
/**
 * Display Recent Products
 */
if (class_exists('WooCommerce')): ?>
<section class="featured-products">
    <div class="container">
        <h2>Featured Collections</h2>
        <ul class="products columns-4">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $loop = new WP_Query($args);
            if ($loop->have_posts()) {
                while ($loop->have_posts()) : $loop->the_post();
                    wc_get_template_part('content', 'product');
                endwhile;
            } else {
                echo __('No products found');
            }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</section>
<?php endif; ?>