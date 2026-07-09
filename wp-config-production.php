<?php
/**
 * PRODUCTION wp-config for abdulrasheedfurnitures.com (Hostinger)
 *
 * HOW TO USE:
 * 1. In Hostinger hPanel → Databases → MySQL Databases, create a database + user.
 * 2. Fill in the four HOSTINGER_* placeholders below with those credentials.
 * 3. After uploading files to public_html, RENAME this file to wp-config.php
 *    (replacing the local one if it was uploaded).
 * Nothing else in this file needs editing.
 */

define( 'WP_CACHE', true );

// ── Database (Hostinger credentials) ─────────────────────────
define( 'DB_NAME',     'HOSTINGER_DB_NAME' );     // e.g. u123456789_arf
define( 'DB_USER',     'HOSTINGER_DB_USER' );     // e.g. u123456789_arfuser
define( 'DB_PASSWORD', 'HOSTINGER_DB_PASSWORD' );
define( 'DB_HOST',     'localhost' );             // Hostinger uses localhost — leave as-is
define( 'DB_CHARSET',  'utf8' );
define( 'DB_COLLATE',  '' );

// ── Fresh production salts (never reuse local ones) ──────────
define( 'AUTH_KEY',          'X0@^Jp1>hq41Wv3BC(rl8a&H&ZOOX+NaKgBW(afv_!*Yd=Q<y]%z96e))~|R&,by' );
define( 'SECURE_AUTH_KEY',   'H~KU7C1LZ2I[Q2n07]ly-Gz4~7dJ+~Sb(qU,*M{RaN<YWKZB*O#=G%zp8=We:<(q' );
define( 'LOGGED_IN_KEY',     'HK+RxasxE~U155y,9i<#b;*uAsh}RIcQS!@EO&l<TQi6lU;rbCW~3@rh&bF60&Gn' );
define( 'NONCE_KEY',         's4_UCE2%;A!c}C>^J9}s6FGD%cm19kil!G*}YMn,At$)gaQDs(<bK!.q(bO]Dn*:' );
define( 'AUTH_SALT',         'qSPGYN4Jj+$~:6XazH=yo^350SzWc=-plbaYd@.SWb;vA3r.C2k~BAF(|oIAza39' );
define( 'SECURE_AUTH_SALT',  '[upX$VszB5xi:rW.MD)<M68D12PR.vA{{9MO=4sAduMs4TKj&,DZyx-cSb4l3}s!' );
define( 'LOGGED_IN_SALT',    '3m=-vJC.KU<][wVTJON~$v6T6zR*{S2#bB12y6#[[Sg.MAK:rEXRL0Sfr=OX%aS2' );
define( 'NONCE_SALT',        'QUZBs3^M+h53P#w5dPkf.Z:8%~~-o1rA|66>.bfc-;;b6#5qI|~]4AbL@I_YcZws' );
define( 'WP_CACHE_KEY_SALT', 'W.%6j$u0-9v+y0;8E0#5,4326v|t=Zq58ZY}~Kk9FVoe1@7r!vxO@W^@Pfm7aK:;' );

$table_prefix = 'wp_';

// ── Site URL — hard-locked so the site can never come up on the wrong domain ──
define( 'WP_HOME',    'https://abdulrasheedfurnitures.com' );
define( 'WP_SITEURL', 'https://abdulrasheedfurnitures.com' );

// ── Production hardening ─────────────────────────────────────
define( 'WP_ENVIRONMENT_TYPE', 'production' );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', false );
define( 'FORCE_SSL_ADMIN', true );
define( 'DISALLOW_FILE_EDIT', true );   // no theme/plugin editor in wp-admin
define( 'WP_POST_REVISIONS', 5 );
define( 'EMPTY_TRASH_DAYS', 14 );
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' ); // auto-apply security releases only

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
