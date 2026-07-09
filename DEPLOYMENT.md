# Go-Live Runbook — abdulrasheedfurnitures.com (Hostinger)

Follow these steps **in order**. Total time ~30–45 minutes.
(.md files are blocked by .htaccess, so this file is safe even if accidentally uploaded — but skip it anyway.)

---

## Phase 1 — Export from Local (on this PC)

1. **Export the database:**
   - Open the Local app → select the `adbulrasheed-local` site → **Database** tab → **Open Adminer**.
   - In Adminer: click **Export** (left menu) → Output: *save*, Format: *SQL*, check **all tables** → Export.
   - You get a `local.sql` file. Keep it — this goes to Hostinger.

2. **Zip the site files.** Zip everything in this folder **EXCEPT**:
   - `_local-only/` folder (dev leftovers — never upload)
   - `CLAUDE.md`, `DEPLOYMENT.md` (internal docs)
   - `local-xdebuginfo.php` (Local-specific)
   - `wp-config.php` (the LOCAL one — you'll use `wp-config-production.php` instead)

   Everything else — including `.htaccess` (hidden file!), `wp-config-production.php`, and
   `arf-migrate-urls.php` — must be included.

## Phase 2 — Hostinger setup (hPanel)

3. **Point the domain:** make sure `abdulrasheedfurnitures.com` is added to the hosting plan
   and DNS is pointed to Hostinger (or transferred/registered there).

4. **Create the database:** hPanel → **Databases → MySQL Databases** → create:
   - a database (e.g. `uXXXXXXXXX_arf`)
   - a user + strong password, assigned to that database
   - Note all three values.

5. **Enable SSL:** hPanel → **Security → SSL** → install the free Lifetime SSL for the domain.
   Wait until it shows **Active** before continuing (the site forces HTTPS).

## Phase 3 — Upload

6. **Upload files:** hPanel → **File Manager** → `public_html`:
   - Delete Hostinger's default placeholder files (`default.php` etc.) so `public_html` is empty.
   - Upload the zip → right-click → **Extract**. Files must land *directly* in `public_html`
     (i.e. `public_html/wp-content/...`, not `public_html/sitezip/wp-content/...`).
   - Confirm `.htaccess` is present (enable "show hidden files" in File Manager settings).

7. **Activate the production config:**
   - Edit `wp-config-production.php` → fill in `HOSTINGER_DB_NAME`, `HOSTINGER_DB_USER`,
     `HOSTINGER_DB_PASSWORD` from step 4.
   - **Rename it to `wp-config.php`** (delete any local `wp-config.php` first if it got uploaded).

8. **Import the database:** hPanel → **Databases → phpMyAdmin** (for the new DB) →
   **Import** tab → choose `local.sql` → Go. Wait for "Import has been successfully finished."

## Phase 4 — Migrate URLs (critical — do not skip)

9. **Dry run** — visit:
   `https://abdulrasheedfurnitures.com/arf-migrate-urls.php?key=ARF_MIGRATE_2026_x9K2`
   You should see a plain-text report of how many rows *would* change. If it errors,
   the DB credentials or import are wrong — fix before continuing.

10. **Execute** — visit:
    `https://abdulrasheedfurnitures.com/arf-migrate-urls.php?key=ARF_MIGRATE_2026_x9K2&go=1`
    This rewrites every `adbulrasheed-local.local` URL (and the old
    `abdurrashidfurnitures.com` brand references) to `https://abdulrasheedfurnitures.com`,
    safely handling serialized data and Elementor JSON.

11. **DELETE `arf-migrate-urls.php` from the server immediately.** (File Manager → delete.)

## Phase 5 — Post-migration checks (wp-admin)

Log in at `https://abdulrasheedfurnitures.com/wp-admin/` (same username/password as local).

12. **Settings → Permalinks** → click **Save Changes** (regenerates rewrite rules).
    Also: **WooCommerce → Settings → Site visibility** → switch from **Coming soon** to
    **Live** when you're ready for the public (the DB currently ships with Coming Soon ON —
    visitors see a placeholder until you flip this, which is actually handy while you verify).
13. **Settings → Reading** → make sure **"Discourage search engines"** is **UNCHECKED**.
14. **Settings → General** → set the real **Administration Email Address** (contact-form
    and newsletter notifications are sent there). WordPress will send a confirmation email.
15. **LiteSpeed Cache → Toolbox → Purge All.** (Hostinger runs LiteSpeed servers, so this
    plugin is a perfect fit — leave it active.)
16. **WebP Express → Settings** → just click **Save settings** once (regenerates its
    .htaccess rules/paths for the new server). Check an image loads as WebP after.
17. **Rank Math → Sitemap Settings** → verify sitemap at
    `https://abdulrasheedfurnitures.com/sitemap_index.xml`, then submit it in
    **Google Search Console** (add the new domain as a property).
18. **WooCommerce → Settings**:
    - **General** → confirm store address / selling location.
    - **Payments** → confirm Cash on Delivery is enabled.
    - **Emails** → confirm "From" shows AbdurRashid Furnitures / orders@abdulrasheedfurnitures.com.
19. **Email deliverability (important for order emails):** hPanel → **Emails** → create the
    mailbox `orders@abdulrasheedfurnitures.com`. Hostinger auto-adds SPF/DKIM DNS records —
    without this, order confirmation emails will land in spam. Send yourself a test order.
20. **Cloudflare plugin:** only keep it active if you actually route the domain through
    Cloudflare; otherwise deactivate it.

## Phase 6 — Smoke test (5 minutes, front-end)

- [ ] Homepage loads over HTTPS with padlock; `http://` and `www.` both redirect.
- [ ] Shop page, a category page (Living Room), and a single product page render.
- [ ] Add to cart → cart count badge updates → cart page → checkout with COD → order
      confirmation page → confirmation **email received** (check spam).
- [ ] The order appears in WooCommerce → Orders with status **Processing**.
- [ ] Contact form submits successfully (admin receives email).
- [ ] Newsletter signup in footer works.
- [ ] Wishlist: heart a product → visit /wishlist/ → product appears.
- [ ] Search overlay returns results.
- [ ] Mobile menu works on a phone.
- [ ] `https://abdulrasheedfurnitures.com/wp-config-production.php` returns **403/404** (blocked).

## Rollback

Nothing on Local is touched by going live — the local site keeps working as your staging
copy. If production breaks, fix locally, re-test, re-upload the changed files.

## Ongoing

- Take backups: hPanel → Files → Backups (enable weekly).
- Update WordPress/plugins from wp-admin (minor core security updates are automatic).
- Local site = staging. Make changes locally first, then upload only the changed theme files.
