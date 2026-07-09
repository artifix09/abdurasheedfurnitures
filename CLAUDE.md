# AbdurRashid Furnitures — WordPress + WooCommerce Store

Local development site (Local by Flywheel/WP Engine) at `http://adbulrasheed-local.local`.
A furniture ecommerce store for the Pakistani market (prices in PKR, Cash-on-Delivery focused).

## Stack

- WordPress 7.0, PHP theme-based (no build step, no Composer, no npm — plain PHP/CSS/JS)
- WooCommerce 10.9.3 — the entire shop (products, cart, checkout, my-account)
- Parent theme: Hello Elementor 3.4.9; Elementor 4.1.4 installed but pages are rendered by **custom child-theme PHP templates**, not Elementor layouts
- Other plugins: Rank Math SEO, LiteSpeed Cache, WebP Express, Cloudflare, Contact Form 7 (largely unused — contact form is custom AJAX), Regenerate Thumbnails
- DB: `local`, prefix `wp_`; not a git repository

## Where all custom code lives

Everything custom is in the child theme: `wp-content/themes/hello-elementor-child/`.
All custom functions/handles are prefixed `arf_` (AbdurRashid Furnitures).

| File | Purpose |
|---|---|
| `functions.php` | Single hub: enqueues, WC tweaks, AJAX handlers, SEO JSON-LD, security hardening, email branding (~440 lines) |
| `header.php` / `footer.php` | Hand-built header (announcement bar, logo row, nav, mobile slide-in menu, search overlay) and footer |
| `front-page.php` | Homepage: hero banner, featured products, categories, testimonials |
| `archive-product.php` / `single-product.php` | Custom shop grid and product detail (accordion, gallery, qty, Buy Now) |
| `woocommerce/content-product.php` | Product card partial |
| `woocommerce/checkout/thankyou.php` | Custom order-received page |
| `page-about.php`, `page-contact.php`, `page-wishlist.php` | Page templates matched by slug |
| `assets/css/main.css` | ~2450 lines, all site styling; sectioned with `/* === */` banners |
| `assets/js/main.js` | ~580 lines vanilla JS (no jQuery): mobile menu, PDP accordion/gallery, search overlay, wishlist, newsletter |

## Key architectural decisions

- **Performance-first**: jQuery is deregistered on non-WooCommerce pages; emoji/oEmbed/shortlink head cruft removed; main.js deferred; asset versions from `filemtime()` (edit CSS/JS and cache busts automatically).
- **Wishlist is plugin-free**: product IDs in `localStorage` (`arf_wishlist` key); `page-wishlist.php` hydrates via AJAX action `arf_wishlist_products`.
- **Custom AJAX endpoints** in functions.php (admin-ajax, nonce-checked): `arf_contact` (rate-limited 1/IP/hour, mails admin), `arf_newsletter` (subscribers stored in option `arf_newsletter_subscribers`), `arf_wishlist_products`. JS gets `ajaxUrl` + nonces via inline `arfData` object printed in `wp_footer`.
- **COD order flow**: COD orders jump straight to `processing` (stock reduces, customer email fires); a branded "what happens next" block is appended to customer emails.
- **Images**: custom `arf-card` size 600×800 (3:4 portrait); WC thumbnail filter matches it; WebP served via WebP Express.
- **SEO**: Rank Math does main schema; functions.php adds Product + Breadcrumb JSON-LD; header.php hand-rolls Open Graph/Twitter meta.
- **Security hardening** in functions.php: XML-RPC disabled, author enumeration blocked, generic login errors, `DISALLOW_FILE_EDIT`.
- **Cart count** in header is a WC fragment (`span.cart-count`) so it updates on AJAX add-to-cart.
- Product categories referenced by slug throughout nav: `living`, `bedroom`, `dining`, `office`. Helpers: `arf_product_cat_url()`, `arf_shop_url()`, `arf_cart_url()`, `arf_account_url()`, `arf_logo_url()`.
- **Logo** is an image (uploads/2026/07/WhatsApp-Image-…jpeg, 1080×1032 white-bg JPEG) rendered via `arf_logo_url()` in header + mobile menu; CSS uses `mix-blend-mode: multiply` so the white background blends. Sizes: 84px desktop → 48px small mobile (see "SITE LOGO" section in main.css).
- **Legal pages** (`/payments-shipping/`, `/terms-of-use/`, `/privacy-policy/`) are published DB pages styled by the `.arf-legal-page` CSS; created via `_local-only/arf-legal-pages.php`. WooCommerce **Coming Soon mode is ON** locally — anonymous requests see a placeholder; disable at launch (in DEPLOYMENT.md checklist).

## Design language

- Brand accent gold/tan `#B8956A`, dark text `#1A1A1A`, cream background `#F9F6F2`
- Fonts: Nunito (body) + Ranade (headings) from Google Fonts, preconnected in header.php
- Logo is text: "AbdurRashid." — no image logo

## Production / deployment (added 2026-07-08)

- Production domain: **https://abdulrasheedfurnitures.com** (Hostinger, LiteSpeed). All code/DB references to the older `abdurrashidfurnitures.com` spelling were replaced.
- The local site is the staging copy; deployment = file upload + SQL import per **`DEPLOYMENT.md`** (step-by-step runbook at root).
- `wp-config-production.php` — production config template (fresh salts, WP_HOME/WP_SITEURL locked to the domain, FORCE_SSL_ADMIN, debug off). User fills Hostinger DB creds and renames to `wp-config.php` on the server. The local `wp-config.php` is untouched (`WP_ENVIRONMENT_TYPE=local`).
- `arf-migrate-urls.php` — one-shot serialized-safe DB search-replace (local URL → prod domain, old brand domain → new). Key-protected, dry-run by default; must be deleted from the server after running.
- `.htaccess` forces HTTPS + non-www **scoped to the production host only** (local unaffected), and blocks wp-config*, .htaccess*, *.sql/*.log/*.md, readme.html, license.txt, xmlrpc.php. `wp-content/uploads/.htaccess` blocks PHP execution.

## Root-level oddities

- `_local-only/` — quarantined dev leftovers, never upload: static `frontend/` mockups, one-shot `arf-create-pages.php` / `setup-pages.php`, wp-config and .htaccess backups.
- `local-xdebuginfo.php` — Local-app file; keep locally, don't upload.

## Conventions when editing

- Keep everything in the child theme; never modify parent theme, WooCommerce, or other plugins.
- Prefix new functions, handles, options, and AJAX actions with `arf_`.
- Escape output (`esc_html`, `esc_url`, `esc_attr`) and use text domain `hello-elementor-child` — existing code is consistent about this.
- Vanilla JS only (IIFE modules in main.js); don't reintroduce jQuery dependencies.
- Append styles to `assets/css/main.css` under a new `/* === SECTION === */` banner; cache busting is automatic.
