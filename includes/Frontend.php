<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {
        // add_action( 'wp_head', [$this, 'head'] );

        add_filter( 'directorist_listings_query_results', [$this, 'directorist_listings_query_results'], );

       
   }

   public function directorist_listings_query_results( $arg ){
        // Remove ID 99962
        $arg->ids = array_values( array_filter( $arg->ids, function( $id ) {
            return $id != 99962;
        }) );

        // Update total count
        $arg->total = count( $arg->ids );
        return $arg;
    }




   public function head(){
    $post_id = 123; // Replace with your post ID
    $post_type = get_post_type( $post_id );

    echo 'Post type of ID ' . $post_id . ' is: ' . $post_type;



     Helper::pri( $post_type );
   }

    

   

    
}
