<?php
/**
 * Template: Shop Archive (archive-product.php)
 * Handles main shop page + category / tag archives
 * Project: AbdurRashid Furnitures
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="main-content">

    <!-- ===== PAGE HERO ===== -->
    <?php
    $up = '/uploads/2026/06/';

    // Keys are matched by category NAME (lowercased, spaces→hyphens), not slug.
    // This survives slug suffixes like "living-room-2" from CSV re-imports.
    $cat_heroes = [
        'living-room' => [
            'type' => 'single',
            'src'  => content_url( $up . 'banner-living.jpg' ),
            'w'    => 3798, 'h' => 870,
        ],
        'bedroom' => [
            'type' => 'single',
            'src'  => content_url( $up . 'bedroom-banner.jpg' ),
            'w'    => 3798, 'h' => 870,
        ],
        'dining' => [
            'type'  => 'split',
            'left'  => [ 'src' => content_url( $up . 'dining.jpg' ),    'alt' => 'Dining Room Collection' ],
            'right' => [ 'src' => content_url( $up . 'dining-2.webp' ), 'alt' => 'Dining Furniture' ],
        ],
        'office' => [
            'type'  => 'split',
            'left'  => [ 'src' => content_url( $up . 'office.jpg' ),   'alt' => 'Office Collection' ],
            'right' => [ 'src' => content_url( $up . 'office-2.jpg' ), 'alt' => 'Office Furniture' ],
        ],
    ];

    $cat_obj      = is_product_category() ? get_queried_object() : null;
    $cat_name     = $cat_obj ? $cat_obj->name : '';
    // Derive a normalised key from the category name so it matches regardless of slug suffix
    $name_key     = strtolower( preg_replace( '/\s+/', '-', trim( $cat_name ) ) );
    // Also try the raw slug in case the name key differs
    $slug_key     = $cat_obj ? $cat_obj->slug : '';
    $hero         = isset( $cat_heroes[ $name_key ] )
                  ? $cat_heroes[ $name_key ]
                  : ( isset( $cat_heroes[ $slug_key ] ) ? $cat_heroes[ $slug_key ] : null );

    // Main shop page banner (shown when not on a specific category)
    $is_main_shop = is_shop() && ! is_product_category();
    ?>

    <?php if ( $is_main_shop ) : ?>
    <div class="cat-hero-split" role="img" aria-label="<?php esc_attr_e( 'AbdurRashid Furnitures — Premium Furniture Collection', 'hello-elementor-child' ); ?>">
        <div class="cat-hero-split__half">
            <img src="<?php echo esc_url( content_url( $up . 'shop-banner2.jpg' ) ); ?>"
                 alt="<?php esc_attr_e( 'Premium Furniture Collection', 'hello-elementor-child' ); ?>"
                 loading="eager" fetchpriority="high" decoding="auto">
        </div>
        <div class="cat-hero-split__half">
            <img src="<?php echo esc_url( content_url( $up . 'shop-banner.jpg' ) ); ?>"
                 alt="<?php esc_attr_e( 'AbdurRashid Furnitures Shop', 'hello-elementor-child' ); ?>"
                 loading="eager" decoding="auto">
        </div>
    </div>

    <?php elseif ( $hero && $hero['type'] === 'single' ) : ?>
    <div class="cat-hero-banner">
        <img src="<?php echo esc_url( $hero['src'] ); ?>"
             alt="<?php echo esc_attr( $cat_name . ' Collection — AbdurRashid Furnitures' ); ?>"
             class="cat-hero-img"
             width="<?php echo absint( $hero['w'] ); ?>"
             height="<?php echo absint( $hero['h'] ); ?>"
             loading="eager" fetchpriority="high" decoding="auto">
    </div>

    <?php elseif ( $hero && $hero['type'] === 'split' ) : ?>
    <div class="cat-hero-split" role="img" aria-label="<?php echo esc_attr( $cat_name . ' Collection — AbdurRashid Furnitures' ); ?>">
        <div class="cat-hero-split__half">
            <img src="<?php echo esc_url( $hero['left']['src'] ); ?>"
                 alt="<?php echo esc_attr( $hero['left']['alt'] ); ?>"
                 <?php if ( ! empty( $hero['left']['pos'] ) ) : ?>style="object-position:<?php echo esc_attr( $hero['left']['pos'] ); ?>;"<?php endif; ?>
                 loading="eager" fetchpriority="high" decoding="auto">
        </div>
        <div class="cat-hero-split__half">
            <img src="<?php echo esc_url( $hero['right']['src'] ); ?>"
                 alt="<?php echo esc_attr( $hero['right']['alt'] ); ?>"
                 <?php if ( ! empty( $hero['right']['pos'] ) ) : ?>style="object-position:<?php echo esc_attr( $hero['right']['pos'] ); ?>;"<?php endif; ?>
                 loading="lazy" decoding="async">
        </div>
    </div>

    <?php else : ?>
    <div class="page-hero">
        <div class="container">
            <h1><?php woocommerce_page_title(); ?></h1>
            <p><?php esc_html_e( 'Browse our complete collection of premium furniture', 'hello-elementor-child' ); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== BREADCRUMB ===== -->
    <div class="container">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>/</span>
            <?php if ( is_product_category() ) : ?>
                <a href="<?php echo esc_url( arf_shop_url() ); ?>">Shop</a>
                <span>/</span>
                <span><?php single_term_title(); ?></span>
            <?php elseif ( is_product_tag() ) : ?>
                <a href="<?php echo esc_url( arf_shop_url() ); ?>">Shop</a>
                <span>/</span>
                <span><?php single_term_title(); ?></span>
            <?php else : ?>
                <span>Shop</span>
            <?php endif; ?>
        </nav>
    </div>

    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="shop-layout">

                <!-- ===== SIDEBAR FILTERS ===== -->
                <aside class="shop-sidebar">

                    <!-- Categories -->
                    <div class="filter-group">
                        <h4>Categories</h4>
                        <?php
                        $current_cat = is_product_category() ? get_queried_object() : null;
                        $shop_cats   = get_terms( [
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'parent'     => 0,
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                        ] );
                        ?>
                        <label class="<?php echo ! $current_cat ? 'filter-active' : ''; ?>">
                            <a href="<?php echo esc_url( arf_shop_url() ); ?>">All Furniture</a>
                        </label>
                        <?php if ( $shop_cats && ! is_wp_error( $shop_cats ) ) :
                            foreach ( $shop_cats as $cat ) : ?>
                        <label class="<?php echo ( $current_cat && $current_cat->term_id === $cat->term_id ) ? 'filter-active' : ''; ?>">
                            <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                        </label>
                        <?php endforeach; endif; ?>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <h4>Price Range</h4>
                        <?php
                        $cur_min  = isset( $_GET['min_price'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : 0;
                        $cur_max  = isset( $_GET['max_price'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : 0;
                        $base_url = remove_query_arg( [ 'min_price', 'max_price' ] );
                        $ranges   = [
                            [ 0,      50000,   'Under &#x20A8; 50,000' ],
                            [ 50000,  100000,  '&#x20A8; 50,000 &#8211; &#x20A8; 100,000' ],
                            [ 100000, 200000,  '&#x20A8; 100,000 &#8211; &#x20A8; 200,000' ],
                            [ 200000, 9999999, 'Above &#x20A8; 200,000' ],
                        ];
                        foreach ( $ranges as $r ) :
                            $is_active = ( $cur_min === $r[0] && $cur_max === $r[1] );
                            $url       = add_query_arg( [ 'min_price' => $r[0], 'max_price' => $r[1] ], $base_url );
                        ?>
                        <label class="<?php echo $is_active ? 'filter-active' : ''; ?>">
                            <a href="<?php echo esc_url( $url ); ?>"><?php echo $r[2]; ?></a>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <!-- Material / Tags -->
                    <?php
                    $cur_tag  = is_product_tag() ? get_queried_object() : null;
                    $mat_tags = get_terms( [
                        'taxonomy'   => 'product_tag',
                        'hide_empty' => true,
                        'number'     => 10,
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                    ] );
                    if ( $mat_tags && ! is_wp_error( $mat_tags ) ) :
                    ?>
                    <div class="filter-group">
                        <h4>Material</h4>
                        <?php foreach ( $mat_tags as $tag ) : ?>
                        <label class="<?php echo ( $cur_tag && $cur_tag->term_id === $tag->term_id ) ? 'filter-active' : ''; ?>">
                            <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </aside><!-- /.shop-sidebar -->

                <!-- ===== PRODUCT GRID ===== -->
                <div>

                    <?php
                    // Detach count + ordering from the hook so they don't render twice.
                    // We output them manually below inside our styled toolbar.
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
                    ?>

                    <!-- Toolbar: results count + ordering -->
                    <div class="shop-toolbar">
                        <?php woocommerce_result_count(); ?>
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>

                    <?php if ( have_posts() ) : ?>

                        <?php do_action( 'woocommerce_before_shop_loop' ); // now only fires notices ?>

                        <div class="shop-product-grid">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                            <?php endwhile; ?>
                        </div>

                        <?php do_action( 'woocommerce_after_shop_loop' ); ?>
                        <?php woocommerce_pagination(); ?>

                    <?php else : ?>

                        <?php do_action( 'woocommerce_no_products_found' ); ?>

                    <?php endif; ?>

                </div><!-- /.product grid wrapper -->

            </div><!-- /.shop-layout -->
        </div>
    </section>

</main>

<?php get_footer(); ?>
