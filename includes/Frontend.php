<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {

        $this->add_actions([
            'wp_head' => 'head', 
            'wp_enqueue_scripts' => 'list_restrictor_enqueue_scripts', 
        ]);

    }

    public function head() {


       $args = [
		    'post_type'      => 'at_biz_dir',
		    'posts_per_page' => -1,
		    'post_status'    => 'publish',
		    'meta_query'     => [
		        [
		            'key'     => '_custom-date',
		            'compare' => 'EXISTS',
		        ]
		    ],
		];

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
		    if ( '99962' == $post->ID ) {

		        // Print post ID
		        Helper::pri( $post->ID );

		        // Get the custom date
		        $all_meta     = get_post_meta( $post->ID );
		        $custom_date  = isset( $all_meta['_custom-date'][0] ) ? $all_meta['_custom-date'][0] : '';

		        Helper::pri( $custom_date );

		        if ( ! empty( $custom_date ) ) {
		            // Convert to timestamps for comparison
		            $today_ts      = strtotime( date('Y-m-d') );
		            $custom_date_ts = strtotime( $custom_date );

		            if ( $custom_date_ts > $today_ts ) {
		                Helper::pri('Custom date is in the future');
		            } elseif ( $custom_date_ts < $today_ts ) {
		                Helper::pri('Custom date is in the past');
		            } else {
		                Helper::pri('Custom date is today');
		            }
		        } else {
		            Helper::pri('No custom date found');
		        }
		    }
		}
	}

	public function list_restrictor_enqueue_scripts() {
        // Register the script
        wp_enqueue_script(
            'list-restrictor-js',
            LIST_RESTRICTOR_URL . 'assets/js/script.js', // Using constant
            ['jquery'],
            '1.0.0',
            true
        );

        // Enqueue the script
        wp_enqueue_script('list-restrictor-js');

        // Optional: Pass PHP variables to JS
        wp_localize_script('list-restrictor-js', 'RESTRICT_LIST', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('list_restrictor_nonce'),
            'is_login' => is_user_logged_in(),
        ]);
    }

}
