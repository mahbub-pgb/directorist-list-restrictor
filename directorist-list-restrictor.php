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

// Plugin Constants

if ( ! defined( 'LIST_RESTRICTOR_URL' ) ) {
    define( 'LIST_RESTRICTOR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'LIST_RESTRICTOR_PATH' ) ) {
    define( 'LIST_RESTRICTOR_PATH', plugin_dir_path( __FILE__ ) );
}

// Listing Type IDs
if ( ! defined( 'LR_PRE_SALE_ID' ) ) {
    define( 'LR_PRE_SALE_ID', '58' );
}

if ( ! defined( 'LR_FOR_SALE_ID' ) ) {
    define( 'LR_FOR_SALE_ID', '57' );
}

if ( ! defined( 'LR_SOLD_SALE_ID' ) ) {
    define( 'LR_SOLD_SALE_ID', '59' );
}

// Autoloader

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Namespace

use ListRestrictor\Loader;

// Plugin Initialization

function list_restrictor_init() {
    $loader = new Loader();
    $loader->init();
}
add_action( 'plugins_loaded', 'list_restrictor_init' );

