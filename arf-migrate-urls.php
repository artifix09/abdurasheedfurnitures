<?php
/**
 * ONE-SHOT MIGRATION SCRIPT — AbdurRashid Furnitures → abdulrasheedfurnitures.com
 *
 * Replaces every occurrence of the local dev URL (and the old brand domain)
 * in the database with the production domain. Serialized-data safe:
 * serialized PHP values are unserialized, replaced recursively, and
 * re-serialized so string lengths stay correct (a plain SQL REPLACE breaks them).
 * Also handles JSON-escaped URLs (Elementor stores http:\/\/... in _elementor_data).
 *
 * USAGE (on the PRODUCTION server, after importing the SQL dump):
 *   Dry-run (counts only, changes nothing):
 *     https://abdulrasheedfurnitures.com/arf-migrate-urls.php?key=ARF_MIGRATE_2026_x9K2
 *   Execute:
 *     https://abdulrasheedfurnitures.com/arf-migrate-urls.php?key=ARF_MIGRATE_2026_x9K2&go=1
 *
 *   >>> DELETE THIS FILE IMMEDIATELY AFTER RUNNING. <<<
 */

define( 'ARF_MIGRATE_KEY', 'ARF_MIGRATE_2026_x9K2' );

// Search → replace pairs, most specific first.
$arf_pairs = [
    'https://adbulrasheed-local.local'   => 'https://abdulrasheedfurnitures.com',
    'http://adbulrasheed-local.local'    => 'https://abdulrasheedfurnitures.com',
    'adbulrasheed-local.local'           => 'abdulrasheedfurnitures.com',
    // JSON-escaped variants (Elementor _elementor_data)
    'https:\\/\\/adbulrasheed-local.local' => 'https:\\/\\/abdulrasheedfurnitures.com',
    'http:\\/\\/adbulrasheed-local.local'  => 'https:\\/\\/abdulrasheedfurnitures.com',
    // Old brand domain used in page content / emails before launch
    'abdurrashidfurnitures.com'          => 'abdulrasheedfurnitures.com',
];

require_once __DIR__ . '/wp-load.php';

if ( ! isset( $_GET['key'] ) || $_GET['key'] !== ARF_MIGRATE_KEY ) {
    status_header( 404 );
    exit( 'Not found.' );
}

$dry_run = empty( $_GET['go'] );

set_time_limit( 300 );
global $wpdb;

/** Recursively replace strings inside any PHP value. */
function arf_deep_replace( $data, array $pairs ) {
    if ( is_string( $data ) ) {
        return str_replace( array_keys( $pairs ), array_values( $pairs ), $data );
    }
    if ( is_array( $data ) ) {
        $out = [];
        foreach ( $data as $k => $v ) {
            $out[ arf_deep_replace( $k, $pairs ) ] = arf_deep_replace( $v, $pairs );
        }
        return $out;
    }
    if ( is_object( $data ) ) {
        if ( $data instanceof __PHP_Incomplete_Class ) {
            return $data; // can't safely touch it
        }
        foreach ( get_object_vars( $data ) as $k => $v ) {
            $data->$k = arf_deep_replace( $v, $pairs );
        }
        return $data;
    }
    return $data;
}

/** Replace inside one value, handling serialized data transparently. */
function arf_replace_value( $value, array $pairs, &$skipped ) {
    if ( ! is_string( $value ) || $value === '' ) {
        return $value;
    }
    if ( is_serialized( $value ) ) {
        $un = @unserialize( $value, [ 'allowed_classes' => true ] );
        if ( false === $un && 'b:0;' !== $value ) {
            $skipped++;
            return $value; // corrupt serialized data — leave untouched
        }
        return serialize( arf_deep_replace( $un, $pairs ) );
    }
    return str_replace( array_keys( $pairs ), array_values( $pairs ), $value );
}

/** Does this string contain any of the search terms? */
function arf_contains( $value, array $pairs ) {
    foreach ( array_keys( $pairs ) as $needle ) {
        if ( false !== strpos( $value, $needle ) ) {
            return true;
        }
    }
    return false;
}

// table => [ primary key, [ columns to process ] ]
$targets = [
    $wpdb->options  => [ 'option_id',   [ 'option_value' ] ],
    $wpdb->posts    => [ 'ID',          [ 'post_content', 'post_excerpt', 'guid' ] ],
    $wpdb->postmeta => [ 'meta_id',     [ 'meta_value' ] ],
    $wpdb->termmeta => [ 'meta_id',     [ 'meta_value' ] ],
    $wpdb->usermeta => [ 'umeta_id',    [ 'meta_value' ] ],
    $wpdb->comments => [ 'comment_ID',  [ 'comment_content', 'comment_author_url' ] ],
];

header( 'Content-Type: text/plain; charset=utf-8' );
echo "ARF URL MIGRATION — " . ( $dry_run ? "DRY RUN (nothing changed; add &go=1 to execute)" : "EXECUTING" ) . "\n";
echo str_repeat( '=', 60 ) . "\n\n";

$total_updated = 0;
$skipped       = 0;

foreach ( $targets as $table => $info ) {
    list( $pk, $columns ) = $info;
    $updated = 0;

    foreach ( $columns as $col ) {
        // Only fetch rows that actually contain one of the needles.
        $likes = [];
        foreach ( array_keys( $arf_pairs ) as $needle ) {
            $likes[] = $wpdb->prepare( "`$col` LIKE %s", '%' . $wpdb->esc_like( $needle ) . '%' );
        }
        $where = implode( ' OR ', $likes );
        $rows  = $wpdb->get_results( "SELECT `$pk` AS pk, `$col` AS val FROM `$table` WHERE $where" );

        foreach ( $rows as $row ) {
            $new = arf_replace_value( $row->val, $arf_pairs, $skipped );
            if ( $new !== $row->val ) {
                $updated++;
                if ( ! $dry_run ) {
                    $wpdb->update( $table, [ $col => $new ], [ $pk => $row->pk ] );
                }
            }
        }
    }

    printf( "%-25s %s %d row(s)\n", $table, $dry_run ? 'would update' : 'updated', $updated );
    $total_updated += $updated;
}

echo "\nTotal: $total_updated row(s)" . ( $dry_run ? ' would be' : '' ) . " updated.\n";
if ( $skipped ) {
    echo "Skipped $skipped corrupt serialized value(s) (left untouched).\n";
}

if ( ! $dry_run ) {
    // Belt-and-braces: force the two canonical options.
    update_option( 'siteurl', 'https://abdulrasheedfurnitures.com' );
    update_option( 'home',    'https://abdulrasheedfurnitures.com' );

    // Flush caches that hold old URLs.
    wp_cache_flush();
    flush_rewrite_rules();
    if ( class_exists( '\Elementor\Plugin' ) ) {
        \Elementor\Plugin::instance()->files_manager->clear_cache();
        echo "Elementor CSS cache cleared.\n";
    }
    delete_transient( 'wc_products_onsale' );
    echo "\nDONE. Now:\n";
    echo "1. DELETE this file (arf-migrate-urls.php) from the server NOW.\n";
    echo "2. Re-save permalinks: wp-admin > Settings > Permalinks > Save.\n";
    echo "3. Purge LiteSpeed cache: wp-admin > LiteSpeed Cache > Purge All.\n";
}
