<?php
namespace ListRestrictor;

class Helper {

    /**
     * Safely get an option with a default fallback.
     */
    public static function get_option( $key, $default = '' ) {
        $value = get_option( $key );
        return ( $value !== false ) ? $value : $default;
    }

    /**
     * Check if current user has admin privileges.
     */
    public static function is_admin_user() {
        return current_user_can( 'manage_options' );
    }

    /**
     * Simple HTML escape wrapper.
     */
    public static function esc( $string ) {
        return esc_html( $string );
    }

    /**
     * Log debug information (optional — for development only).
     */
    public static function log( $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[ListRestrictor] ' . print_r( $message, true ) );
        }
    }

    /**
     * Pretty print or dump data in a readable format.
     * Works only for admin users to avoid leaking info to visitors.
     */
    public static function pri( $data, $admin = false ) {
        // Only admins can see if $admin is true
        if ( ! $admin && ! current_user_can('manage_options') ) {
            return;
        }

        echo '<pre style="
            background:#1e1e1e;
            color:#dcdcdc;
            padding:10px;
            border-radius:8px;
            overflow:auto;
        ">';
        print_r( $data );
        echo '</pre>';
    }
}
