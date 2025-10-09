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
    	// Helper::pri( $this->get_post_ids_by_meta( '57' ) );

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
            'login_url' => wp_login_url(),
        ]);
    }

}
