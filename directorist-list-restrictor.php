<?php
/**
 * Plugin Name: Directorist List Restrictor
 * Description: Restricts access to certain lists. Loads separate admin and frontend logic.
 * Version: 1.0.1
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
if ( ! defined( 'LIST_RESTRICTOR_VERSION' ) ) {
    define( 'LIST_RESTRICTOR_VERSION', '1.0.1' );
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

