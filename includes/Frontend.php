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
    if ( ! empty( $arg->ids ) ) {
        foreach ( $arg->ids as $post_id ) {
            if ( $post_id == '99962' ) {
                // code...
            
                echo "<h3>Post ID: $post_id</h3>";
                
                // Get all post meta
                $all_meta = get_post_meta( $post_id );

                echo '<pre>';
                print_r( $all_meta );
                echo '</pre>';
            }
        }
    } else {
        echo 'No listings found.';
    }

    return $arg;
}




   public function head(){
    $post_id = 123; // Replace with your post ID
    $post_type = get_post_type( $post_id );

    echo 'Post type of ID ' . $post_id . ' is: ' . $post_type;



     Helper::pri( $post_type );
   }

    

   

    
}
