<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {

        $this->add_actions([
            'wp_head'       => 'head', 
            'login_init'    => 'show_new_url', 
        ]);

    }

    public function head() {
         // $saved_data = array_flip( get_option('listing_status_data', [] ));
    	// Helper::pri( $saved_data, true );

	}

    public function show_new_url() {
        // Get saved page ID, default to 99939 if nothing saved
        $log_in_page_id = get_option( 'login_redirect_page', 99939 );

        // Get full URL of the page
        $redirect_url = get_permalink( $log_in_page_id );

        if ( $redirect_url ) {
            wp_redirect( $redirect_url );
            exit;
        }
    }

}
