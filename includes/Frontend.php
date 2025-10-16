<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {

        $this->add_actions([
            'wp_head' => 'head', 
        ]);

    }

    public function head() {
         // $saved_data = array_flip( get_option('listing_status_data', [] ));
    	// Helper::pri( $saved_data, true );

	}
}
