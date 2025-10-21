<?php
namespace ListRestrictor;

use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    /**
     * Initialize hooks
     */
    public function init() {
        $this->add_actions([
            'wp_head'    => 'head',
            'login_init' => 'show_new_url',
            'wp_enqueue_scripts'  => 'enqueue_scripts',
        ]);

        // After-login redirect
        add_filter( 'atbdp_login_redirection_page_url', [ $this, 'login_redirect_option'], 100, 2 );
    }
 
    /**
     * Optional: Output debug info in head
     */
    public function head() {
        // Example debug: listing status data
        // $saved_data = array_flip( get_option('listing_status_data', []) );
        // Helper::pri( $saved_data, true );
    }

    public function enqueue_scripts() {
	    wp_enqueue_script(
	        'list-restrictor',
	        LIST_RESTRICTOR_URL . 'assets/js/front.js',
	        ['jquery'], 
	        '1.0',
	        true 
	    );
	}

    /**
     * Redirect users visiting wp-login.php to selected page
     */
    public function show_new_url() {
        // Get saved page ID, default to 99939
        $log_in_page_id = get_option( 'login_redirect_page', 99939 );

        // Get full URL
        $redirect_url = get_permalink( $log_in_page_id );

        if ( $redirect_url ) {
            wp_safe_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Redirect users after login
     *
     * @param string   $redirect_to           Default redirect URL
     * @param string   $requested_redirect_to URL from redirect_to param
     * @param WP_User  $user                  WP_User object
     * @return string
     */
    public function login_redirect_option( $link, $page_id ) {

        // Build URL with query parameter
        $link = $link . '?directory_type=preventa';

        return  $link;
    }
}
