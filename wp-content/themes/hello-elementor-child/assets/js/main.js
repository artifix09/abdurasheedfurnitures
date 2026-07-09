/* =============================================
   AbdurRashid Furnitures — main.js
   Vanilla JS — No jQuery, No Frameworks
   ============================================= */

(function () {
  'use strict';

  /* --- Mobile Menu --- */
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  const mobileOverlay = document.querySelector('.mobile-overlay');
  const mobileClose = document.querySelector('.mobile-menu-close');

  function openMenu() {
    if (mobileMenu) mobileMenu.classList.add('is-open');
    if (mobileOverlay) mobileOverlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    if (mobileMenu) mobileMenu.classList.remove('is-open');
    if (mobileOverlay) mobileOverlay.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (menuToggle) menuToggle.addEventListener('click', openMenu);
  if (mobileClose) mobileClose.addEventListener('click', closeMenu);
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeMenu);

  // Close search overlay on Escape (handled in search overlay IIFE below)

  /* --- Cart (localStorage) --- */
  const CART_KEY = 'arf_cart';

  function getCart() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch (e) { return []; }
  }
  function saveCart(cart) { localStorage.setItem(CART_KEY, JSON.stringify(cart)); }

  function updateCartCount() {
    const cart = getCart();
    const total = cart.reduce(function (s, i) { return s + i.qty; }, 0);
    document.querySelectorAll('.cart-count').forEach(function (el) { el.textContent = total; });
  }

  function addToCart(product) {
    var cart = getCart();
    var existing = cart.find(function (i) { return i.id === product.id; });
    if (existing) { existing.qty += product.qty || 1; }
    else { cart.push({ id: product.id, name: product.name, price: product.price, qty: product.qty || 1 }); }
    saveCart(cart);
    updateCartCount();
    showNotification(product.name + ' added to cart');
  }

  // Expose globally for inline handlers
  window.ARFCart = { getCart: getCart, saveCart: saveCart, addToCart: addToCart, updateCartCount: updateCartCount };

  /* --- Notification Toast --- */
  function showNotification(msg) {
    var toast = document.createElement('div');
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1A1A1A;color:#fff;padding:14px 24px;font-size:.9rem;z-index:9999;opacity:0;transition:opacity .3s ease;max-width:320px;';
    toast.textContent = msg;
    document.body.appendChild(toast);
    requestAnimationFrame(function () { toast.style.opacity = '1'; });
    setTimeout(function () {
      toast.style.opacity = '0';
      setTimeout(function () { toast.remove(); }, 300);
    }, 2600);
  }

  /* --- Quantity Selector --- */
  document.querySelectorAll('.qty-selector').forEach(function (sel) {
    var input = sel.querySelector('input');
    var minus = sel.querySelector('[data-qty="minus"]');
    var plus = sel.querySelector('[data-qty="plus"]');
    if (!input) return;
    if (minus) minus.addEventListener('click', function () {
      var v = parseInt(input.value, 10) || 1;
      if (v > 1) input.value = v - 1;
    });
    if (plus) plus.addEventListener('click', function () {
      var v = parseInt(input.value, 10) || 1;
      input.value = v + 1;
    });
  });

  /* --- Product Gallery (single-product page) --- */
  var mainImg = document.querySelector('.product-gallery-main img');
  var thumbButtons = document.querySelectorAll('.product-gallery-thumbs button');

  thumbButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      thumbButtons.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      if (mainImg) {
        mainImg.src = btn.dataset.full || btn.querySelector('img').src;
        mainImg.alt = btn.querySelector('img').alt || '';
      }
    });
  });

  /* --- Add to Cart Button (product page) --- */
  var addBtn = document.querySelector('.js-add-to-cart');
  if (addBtn) {
    addBtn.addEventListener('click', function () {
      var qtyInput = document.querySelector('.qty-selector input');
      var qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;
      addToCart({
        id: addBtn.dataset.id || 'prod-1',
        name: addBtn.dataset.name || 'Product',
        price: parseFloat(addBtn.dataset.price) || 0,
        qty: qty
      });
    });
  }

  /* --- Quick Add Buttons (product cards) --- */
  document.querySelectorAll('.js-quick-add').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      addToCart({
        id: btn.dataset.id,
        name: btn.dataset.name,
        price: parseFloat(btn.dataset.price),
        qty: 1
      });
    });
  });

  /* --- Cart Page Rendering --- */
  var cartTableBody = document.querySelector('.js-cart-body');
  var cartSummary = document.querySelector('.js-cart-summary');
  var emptyCart = document.querySelector('.js-empty-cart');

  function renderCart() {
    if (!cartTableBody) return;
    var cart = getCart();

    if (cart.length === 0) {
      if (emptyCart) emptyCart.style.display = '';
      cartTableBody.closest('.cart-layout').style.display = 'none';
      return;
    }

    if (emptyCart) emptyCart.style.display = 'none';
    cartTableBody.closest('.cart-layout').style.display = '';

    var html = '';
    var subtotal = 0;

    cart.forEach(function (item) {
      var lineTotal = item.price * item.qty;
      subtotal += lineTotal;
      html += '<tr>' +
        '<td><div class="cart-item-info">' +
          '<div class="cart-item-img"><div style="width:80px;height:80px;background:var(--color-border-light);display:flex;align-items:center;justify-content:center;font-family:monospace;font-size:.65rem;color:var(--color-text-muted);">IMG</div></div>' +
          '<div><div class="cart-item-name">' + item.name + '</div>' +
          '<button class="cart-item-remove" data-remove="' + item.id + '">Remove</button></div>' +
        '</div></td>' +
        '<td>₨ ' + item.price.toLocaleString() + '</td>' +
        '<td><div class="qty-selector">' +
          '<button data-qty="minus" data-cart-id="' + item.id + '" aria-label="Decrease quantity">−</button>' +
          '<input type="number" value="' + item.qty + '" min="1" aria-label="Quantity" data-cart-input="' + item.id + '">' +
          '<button data-qty="plus" data-cart-id="' + item.id + '" aria-label="Increase quantity">+</button>' +
        '</div></td>' +
        '<td style="font-weight:500">₨ ' + lineTotal.toLocaleString() + '</td>' +
      '</tr>';
    });

    cartTableBody.innerHTML = html;

    if (cartSummary) {
      var shipping = subtotal > 50000 ? 0 : 2500;
      var total = subtotal + shipping;
      cartSummary.innerHTML =
        '<div class="cart-summary-row"><span>Subtotal</span><span>₨ ' + subtotal.toLocaleString() + '</span></div>' +
        '<div class="cart-summary-row"><span>Shipping</span><span>' + (shipping === 0 ? 'Free' : '₨ ' + shipping.toLocaleString()) + '</span></div>' +
        '<div class="cart-summary-row total"><span>Total</span><span>₨ ' + total.toLocaleString() + '</span></div>';
    }

    // Rebind cart qty buttons
    cartTableBody.querySelectorAll('[data-qty]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.dataset.cartId;
        var cart = getCart();
        var item = cart.find(function (i) { return i.id === id; });
        if (!item) return;
        if (btn.dataset.qty === 'minus' && item.qty > 1) item.qty--;
        else if (btn.dataset.qty === 'plus') item.qty++;
        saveCart(cart);
        updateCartCount();
        renderCart();
      });
    });

    // Rebind remove buttons
    cartTableBody.querySelectorAll('[data-remove]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.dataset.remove;
        var cart = getCart().filter(function (i) { return i.id !== id; });
        saveCart(cart);
        updateCartCount();
        renderCart();
      });
    });
  }

  /* --- Checkout Order Summary --- */
  function renderCheckoutSummary() {
    var el = document.querySelector('.js-checkout-items');
    if (!el) return;
    var cart = getCart();
    var subtotal = 0;
    var html = '';
    cart.forEach(function (item) {
      var line = item.price * item.qty;
      subtotal += line;
      html += '<div class="order-item"><span>' + item.name + ' × ' + item.qty + '</span><span>₨ ' + line.toLocaleString() + '</span></div>';
    });
    var shipping = subtotal > 50000 ? 0 : 2500;
    html += '<div class="order-item" style="border-top:1px solid var(--color-border);padding-top:12px;margin-top:8px"><span>Shipping</span><span>' + (shipping === 0 ? 'Free' : '₨ ' + shipping.toLocaleString()) + '</span></div>';
    html += '<div class="order-item" style="font-weight:600;font-size:1.05rem;border-top:2px solid var(--color-text);padding-top:12px;margin-top:8px"><span>Total</span><span>₨ ' + (subtotal + shipping).toLocaleString() + '</span></div>';
    el.innerHTML = html || '<p style="color:var(--color-text-muted);font-size:.9rem;">Your cart is empty.</p>';
  }

  /* --- Testimonials Slider --- */
  (function () {
    var outer = document.querySelector('.testimonials-slider-outer');
    if (!outer) return;

    var track   = outer.querySelector('.testimonials-track');
    var cards   = outer.querySelectorAll('.testimonial-card-v2');
    var prevBtn = outer.querySelector('.arf-slider-prev');
    var nextBtn = outer.querySelector('.arf-slider-next');
    if (!track || cards.length === 0) return;

    var current   = 0;
    var cardCount = cards.length;

    function visibleCount() {
      var w = window.innerWidth;
      if (w < 480) return 1;
      if (w < 1024) return 2;
      return 4;
    }

    function maxIndex() {
      return Math.max(0, cardCount - visibleCount());
    }

    function goTo(idx) {
      current = Math.max(0, Math.min(idx, maxIndex()));
      var cardW = cards[0].offsetWidth;
      var gap   = 16;
      track.style.transform = 'translateX(-' + (current * (cardW + gap)) + 'px)';
      if (prevBtn) prevBtn.disabled = current === 0;
      if (nextBtn) nextBtn.disabled = current >= maxIndex();
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(current + 1); });

    // Recalculate on resize
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () { goTo(Math.min(current, maxIndex())); }, 100);
    });

    goTo(0);
  }());

  /* --- Product Tabs (single product page) --- */
  (function () {
    var tabBtns   = document.querySelectorAll('.product-tabs-nav .tab-btn');
    var tabPanels = document.querySelectorAll('.product-tabs-wrap .tab-panel');
    if (!tabBtns.length) return;

    tabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = btn.dataset.tab;

        tabBtns.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        tabPanels.forEach(function (p) {
          p.classList.remove('active');
          p.hidden = true;
        });

        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        var panel = document.getElementById('tab-' + target);
        if (panel) {
          panel.classList.add('active');
          panel.hidden = false;
        }
      });
    });
  }());

  /* --- Init --- */
  renderCart();
  renderCheckoutSummary();

  /* Show toast when WC fragment updates the cart count after AJAX add-to-cart */
  (function () {
    var countEl = document.querySelector('.cart-count');
    if (!countEl) return;
    var prev = parseInt(countEl.textContent, 10) || 0;
    new MutationObserver(function () {
      var curr = parseInt(countEl.textContent, 10) || 0;
      if (curr > prev) { showNotification('Item added to cart'); }
      prev = curr;
    }).observe(countEl, { childList: true, characterData: true, subtree: true });
  }());

})();

/* =============================================
   PRODUCT DETAIL PAGE — Accordion · Gallery · Qty · Buy Now
   ============================================= */
(function () {
  'use strict';

  /* --- Accordion toggle ([data-accordion]) --- */
  document.querySelectorAll('[data-accordion]').forEach(function (acc) {
    var header = acc.querySelector('.pd-accordion-header, .pd-delivery-header');
    if (!header) return;
    header.addEventListener('click', function () {
      acc.classList.toggle('open');
      header.setAttribute('aria-expanded', acc.classList.contains('open'));
    });
    header.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); header.click(); }
    });
  });

  /* --- Gallery thumbnail → update main image --- */
  var pdMainImg = document.getElementById('pd-main-img');
  document.querySelectorAll('.pd-thumb').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.pd-thumb').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      if (pdMainImg) {
        pdMainImg.src = btn.dataset.full || btn.querySelector('img').src;
        pdMainImg.alt = btn.querySelector('img').alt || '';
      }
    });
  });

  /* --- Custom qty +/- synced to WC's hidden form input --- */
  var pdQtyVis = document.getElementById('pd-qty-vis');
  if (pdQtyVis) {
    var pdQtyMax = parseInt(pdQtyVis.getAttribute('max'), 10) || 0;

    function clampQty(v) {
      v = Math.max(1, v);
      if (pdQtyMax > 0) v = Math.min(v, pdQtyMax);
      return v;
    }
    function syncWcQty() {
      pdQtyVis.value = clampQty(parseInt(pdQtyVis.value, 10) || 1);
      var wcQty = document.querySelector('.pd-actions .input-text.qty, .pd-actions input[name="quantity"]');
      if (wcQty) wcQty.value = pdQtyVis.value;
    }
    document.querySelectorAll('.pd-qty-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var v = parseInt(pdQtyVis.value, 10) || 1;
        if (btn.dataset.qty === 'minus') v = clampQty(v - 1);
        else                             v = clampQty(v + 1);
        pdQtyVis.value = v;
        syncWcQty();
      });
    });
    pdQtyVis.addEventListener('change', syncWcQty);
    pdQtyVis.addEventListener('blur',   syncWcQty);
  }

  /* --- Buy Now: AJAX add-to-cart then redirect to checkout --- */
  var buyNow = document.querySelector('.pd-buy-now');
  if (buyNow) {
    buyNow.addEventListener('click', function (e) {
      e.preventDefault();
      if (buyNow.disabled) return;
      var pid      = buyNow.dataset.productId;
      var qty      = pdQtyVis ? (parseInt(pdQtyVis.value, 10) || 1) : 1;
      var checkout = buyNow.dataset.checkoutUrl || buyNow.getAttribute('href');
      buyNow.disabled = true;
      buyNow.classList.add('loading');
      if (typeof wc_add_to_cart_params !== 'undefined' && pid) {
        var url = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');
        var fd  = new FormData();
        fd.append('product_id', pid);
        fd.append('quantity',   qty);
        fetch(url, { method: 'POST', body: fd })
          .catch(function () {})
          .then(function () { window.location.href = checkout; });
      } else {
        window.location.href = checkout;
      }
    });
  }

}());

/* =============================================
   SEARCH OVERLAY
   ============================================= */
(function () {
  'use strict';

  var toggle   = document.getElementById('search-toggle');
  var mToggle  = document.getElementById('mobile-search-btn');
  var overlay  = document.getElementById('search-overlay');
  var closeBtn = document.getElementById('search-overlay-close');
  var input    = document.getElementById('search-overlay-input');

  if (!overlay) return;

  var searchLastFocus = null;

  function openSearch() {
    searchLastFocus = document.activeElement;
    overlay.classList.add('is-open');
    overlay.removeAttribute('aria-hidden');
    document.body.style.overflow = 'hidden';
    if (input) { input.focus(); input.select(); }
  }

  function closeSearch() {
    overlay.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (searchLastFocus) searchLastFocus.focus();
  }

  if (toggle) toggle.addEventListener('click', function (e) { e.preventDefault(); openSearch(); });

  if (mToggle) mToggle.addEventListener('click', function (e) {
    e.preventDefault();
    // Close mobile menu first, then open search
    var menu = document.querySelector('.mobile-menu');
    var mOverlay = document.querySelector('.mobile-overlay');
    if (menu) menu.classList.remove('is-open');
    if (mOverlay) mOverlay.classList.remove('is-open');
    document.body.style.overflow = '';
    setTimeout(openSearch, 160);
  });

  if (closeBtn) closeBtn.addEventListener('click', closeSearch);

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeSearch();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeSearch();
  });
}());

/* =============================================
   WISHLIST — localStorage, no plugin required
   ============================================= */
(function () {
  'use strict';

  var STORAGE_KEY = 'arf_wishlist';

  function getList() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
    catch (e) { return []; }
  }

  function saveList(list) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
  }

  function inList(id) {
    return getList().indexOf(String(id)) !== -1;
  }

  function toggle(id) {
    var list = getList();
    var idx  = list.indexOf(String(id));
    if (idx === -1) { list.push(String(id)); }
    else            { list.splice(idx, 1); }
    saveList(list);
    return idx === -1; // true = just added
  }

  function applyState(btn, active) {
    btn.classList.toggle('is-wishlisted', active);
    btn.setAttribute('aria-label', active ? 'Remove from wishlist' : 'Add to wishlist');
    var label = btn.querySelector('.pd-wishlist-label');
    if (label) label.textContent = active ? 'Saved to Wishlist' : 'Save to Wishlist';
  }

  function updateBadge() {
    var count = getList().length;
    var badge = document.querySelector('.wishlist-count');
    if (!badge) return;
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';
  }

  function init() {
    // Restore state for all buttons on page
    document.querySelectorAll('[data-product-id].fp-wishlist, .fp-wishlist[data-product-id]').forEach(function (btn) {
      applyState(btn, inList(btn.dataset.productId));
    });
    updateBadge();

    // Delegate click
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.fp-wishlist[data-product-id]');
      if (!btn) return;
      e.preventDefault();
      var added = toggle(btn.dataset.productId);
      applyState(btn, added);
      updateBadge();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}());

/* =============================================
   NEWSLETTER SIGNUP
   ============================================= */
(function () {
  'use strict';

  var input = document.getElementById('footer-email');
  if (!input) return;
  var wrap  = input.closest('.newsletter-input');
  var btn   = wrap && wrap.querySelector('button');
  if (!btn) return;

  function subscribe() {
    var email = input.value.trim();
    var valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    input.style.outlineColor = valid ? '' : '#C0392B';
    if (!valid) { input.focus(); return; }
    if (typeof arfData === 'undefined') return;

    btn.disabled  = true;
    input.disabled = true;

    var fd = new FormData();
    fd.append('action', 'arf_newsletter');
    fd.append('nonce',  arfData.newsletterNonce);
    fd.append('email',  email);

    fetch(arfData.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        btn.disabled   = false;
        input.disabled = false;
        if (res.success) {
          input.value = '';
          input.placeholder = res.data.message;
        } else {
          input.style.outlineColor = '#C0392B';
        }
      })
      .catch(function () { btn.disabled = false; input.disabled = false; });
  }

  btn.addEventListener('click', subscribe);
  input.addEventListener('keydown', function (e) { if (e.key === 'Enter') subscribe(); });
}());
