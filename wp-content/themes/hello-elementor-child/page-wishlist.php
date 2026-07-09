<?php
/**
 * Template Name: Wishlist
 * Description: Shows products saved to the local-storage wishlist via AJAX.
 * Project: AbdurRashid Furnitures
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="main-content">

    <div class="page-hero">
        <div class="container">
            <h1><?php esc_html_e( 'My Wishlist', 'hello-elementor-child' ); ?></h1>
            <p><?php esc_html_e( 'Products you\'ve saved for later', 'hello-elementor-child' ); ?></p>
        </div>
    </div>

    <section class="section">
        <div class="container">

            <!-- Loading -->
            <div id="wishlist-loading" class="wishlist-state" style="text-align:center; padding:80px 0; color:var(--color-text-muted);">
                <?php esc_html_e( 'Loading your wishlist…', 'hello-elementor-child' ); ?>
            </div>

            <!-- Empty state -->
            <div id="wishlist-empty" class="wishlist-state" style="display:none; text-align:center; padding:80px 0;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true" style="margin:0 auto 24px; display:block; color:var(--color-text-muted);"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <h3 style="font-family:var(--font-heading); font-weight:400; font-size:1.5rem; margin-bottom:12px;"><?php esc_html_e( 'Your wishlist is empty', 'hello-elementor-child' ); ?></h3>
                <p style="color:var(--color-text-secondary); margin-bottom:32px; max-width:400px; margin-inline:auto;"><?php esc_html_e( 'Browse our collection and tap the heart icon to save items you love.', 'hello-elementor-child' ); ?></p>
                <a href="<?php echo esc_url( arf_shop_url() ); ?>" class="btn-cta"><?php esc_html_e( 'Browse Products', 'hello-elementor-child' ); ?></a>
            </div>

            <!-- Product grid — filled by JS -->
            <div id="wishlist-grid" class="fp-grid" style="display:none;"></div>

            <!-- Clear all -->
            <div id="wishlist-clear-row" style="display:none; text-align:center; margin-top:40px;">
                <button id="wishlist-clear-btn" class="pd-tag" type="button">
                    <?php esc_html_e( 'Clear Wishlist', 'hello-elementor-child' ); ?>
                </button>
            </div>

        </div>
    </section>

</main>

<script>
(function () {
    'use strict';

    var STORAGE_KEY = 'arf_wishlist';
    var ajaxUrl     = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
    var nonce       = <?php echo wp_json_encode( wp_create_nonce( 'arf_wishlist_nonce' ) ); ?>;

    var grid      = document.getElementById('wishlist-grid');
    var loading   = document.getElementById('wishlist-loading');
    var empty     = document.getElementById('wishlist-empty');
    var clearRow  = document.getElementById('wishlist-clear-row');
    var clearBtn  = document.getElementById('wishlist-clear-btn');

    function getIds() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
        catch (e) { return []; }
    }

    function showEmpty() {
        loading.style.display  = 'none';
        grid.style.display     = 'none';
        clearRow.style.display = 'none';
        empty.style.display    = '';
    }

    function syncBadge(count) {
        var badge = document.querySelector('.wishlist-count');
        if (!badge) return;
        badge.textContent    = count;
        badge.style.display  = count > 0 ? 'flex' : 'none';
    }

    function renderProducts(products) {
        loading.style.display = 'none';
        if (!products.length) { showEmpty(); return; }

        grid.innerHTML = products.map(function (p) {
            return '<div class="fp-card">' +
                '<div class="fp-card-img">' +
                    '<button class="fp-wishlist is-wishlisted" type="button" data-product-id="' + p.id + '" aria-label="Remove from wishlist">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;stroke:var(--color-accent);fill:var(--color-accent);stroke:var(--color-accent);"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>' +
                    '</button>' +
                    '<a href="' + p.permalink + '" tabindex="-1" aria-hidden="true">' +
                        '<img src="' + p.image + '" alt="' + p.image_alt + '" class="fp-card-image" loading="lazy">' +
                    '</a>' +
                    '<div class="fp-cart-overlay">' +
                        '<a href="' + p.permalink + '" class="fp-cart-btn" aria-label="View product">' +
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>' +
                            'View Product' +
                        '</a>' +
                    '</div>' +
                '</div>' +
                '<div class="fp-card-info">' +
                    (p.cat ? '<div class="fp-card-category">' + p.cat + '</div>' : '') +
                    '<h4 class="fp-card-name"><a href="' + p.permalink + '">' + p.name + '</a></h4>' +
                    '<div class="fp-card-price">' + p.price_html + '</div>' +
                '</div>' +
            '</div>';
        }).join('');

        grid.style.display     = '';
        clearRow.style.display = '';
        syncBadge(getIds().length);
    }

    function loadWishlist() {
        var ids = getIds();
        if (!ids.length) { showEmpty(); return; }

        var fd = new FormData();
        fd.append('action', 'arf_wishlist_products');
        fd.append('nonce',  nonce);
        fd.append('ids',    ids.join(','));

        fetch(ajaxUrl, { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) { if (data.success) { renderProducts(data.data); } else { showEmpty(); } })
            .catch(function () { showEmpty(); });
    }

    // Remove product on heart click and fade out card
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('#wishlist-grid .fp-wishlist[data-product-id]');
        if (!btn) return;
        e.preventDefault();
        var id   = String(btn.dataset.productId);
        var list = getIds().filter(function (i) { return i !== id; });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
        syncBadge(list.length);
        var card = btn.closest('.fp-card');
        if (card) {
            card.style.transition = 'opacity .25s ease, transform .25s ease';
            card.style.opacity    = '0';
            card.style.transform  = 'scale(.95)';
            setTimeout(function () {
                card.remove();
                if (!document.querySelector('#wishlist-grid .fp-card')) { showEmpty(); }
            }, 250);
        }
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            localStorage.removeItem(STORAGE_KEY);
            syncBadge(0);
            showEmpty();
        });
    }

    loadWishlist();
}());
</script>

<?php get_footer(); ?>
