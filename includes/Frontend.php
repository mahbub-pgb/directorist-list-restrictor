<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {
        add_action( 'wp_head', [ $this, 'head' ] );
        
    }

    public function head() {
        // $post_id   = 99962; // Replace with your post ID
        // $all_meta  = get_post_meta( $post_id );
        // $post_type = get_post_type( $post_id );

        // if ( isset( $all_meta['_custom-select-2'][0] ) && $all_meta['_custom-select-2'][0] === 'enable' ) {
        // }
        //     // update_post_meta( $post_id, '_directory_type', '57' );

        // Helper::pri( $post_type );
        // Helper::pri( $all_meta['_custom-select-2'][0] );
    }

}
