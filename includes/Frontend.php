<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {
        // add_action( 'wp_head', [$this, 'head'] );
       // add_action( 'directorist_before_listing_types', [$this, 'before_listing_types'] );

       add_action( 'directorist_all_listings_query_arguments', [$this, 'listings_query_arguments'], 10, 2 );
       // add_filter( 'atbdp_listing_search_query_argument', [$this, 'restrict_directorist_all_listings'] );

       add_filter( 'atbdp_search_listings_meta_queries', [$this, 'listings_meta_queries'] );
   }

   public function head(){
    $listings = new \Directorist\Directorist_Listings( $atts = [], $type = 'instant_search' );
    $ids = $listings->query_results->ids;

    // Remove both IDs
    $ids = array_filter($ids, function($id) {
        return !in_array($id, [ 99962]);
    });

    // Reindex array if needed
    $ids = array_values($ids);

     Helper::pri( $ids );
   }

    public function restrict_directorist_all_listings( $arg ){

        $ids = $arg->query_results->ids;

        // Remove both IDs
         $ids = array_filter($ids, function($id) {
            return !in_array($id, [ 99962]);
        });

        // Reindex array if needed
        $ids = array_values($ids);

        // Helper::pri( $arg );
        return $arg;
    }

    public function listings_meta_queries( $arg ){

        // update_option( 'arg', $arg );

        // Helper::pri( $arg );
        return $arg;
    }


    public function listings_query_arguments( $arg, $list ){

        update_option( 'arg', $arg );

        Helper::pri( $list );
        return $arg;
    }

    public function before_listing_types( $arg ) {

        $ids = $arg->all_listings->ids;

        // Remove both IDs
         $ids = array_filter($ids, function($id) {
            return !in_array($id, [ 99962]);
        });

        // Reindex array if needed
        $ids = array_values($ids);

        Helper::pri( $ids );


    }

    
}
