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
