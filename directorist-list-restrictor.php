<?php
/**
 * Plugin Name: Directorist List Restrictor
 * Description: Restricts access to certain lists. Loads separate admin and frontend logic.
 * Version: 1.0.0
 * Author: Mahbub
 * Author URI: https://techwithmahbub.com/
 * Author Email: mahbub.progressivebyte@gmail.com
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'LIST_RESTRICTOR_URL' ) ) {
    define( 'LIST_RESTRICTOR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'LIST_RESTRICTOR_PATH' ) ) {
    define( 'LIST_RESTRICTOR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'LR_PRE_SALE_ID' ) ) {
    define( 'LR_PRE_SALE_ID', '58' );
}

if ( ! defined( 'LR_FOR_SALE_ID' ) ) {
    define( 'LR_FOR_SALE_ID', '57' );
}

if ( ! defined( 'LR_SOLD_SALE_ID' ) ) {
    define( 'LR_SOLD_SALE_ID', '59' );
}


// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Use namespace
use ListRestrictor\Loader;

// Initialize plugin
function list_restrictor_init() {
    $loader = new Loader();
    $loader->init();
}
add_action( 'plugins_loaded', 'list_restrictor_init' );

function schedule_daily_event() {
    if ( ! wp_next_scheduled( 'check_expired_dates_daily' ) ) {
        wp_schedule_event( time(), 'daily', 'check_expired_dates_daily' );
    }
}
register_activation_hook( __FILE__, 'schedule_daily_event' );

// Register deactivation hook
register_deactivation_hook( __FILE__, 'list_restrictor_deactivate' );

/**
 * Runs when plugin is deactivated
 */
function list_restrictor_deactivate() {
    // Example: Unschedule cron job if you have one
    $timestamp = wp_next_scheduled( 'check_expired_dates_daily' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'check_expired_dates_daily' );
    }

}
