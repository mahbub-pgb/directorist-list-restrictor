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
            'wp_head'            => 'head',
            // 'login_init'         => 'show_new_url',
            'wp_enqueue_scripts' => 'enqueue_scripts',
        ]);

        // After-login redirect filter
        add_filter( 'atbdp_login_redirection_page_url', [ $this, 'login_redirect_option' ], 100, 2 );

        // Registration message filter
        add_filter( 'atbdp_registration_page_registered_msg', [ $this, 'registered_msg' ], 10 );
    }

    /**
     * Optional: Output debug info in head
     */
    public function head() {
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'list-restrictor',
            LIST_RESTRICTOR_URL . 'assets/js/front.js',
            ['jquery'],
            LIST_RESTRICTOR_VERSION,
            true
        );
    }

    /**
     * Modify Directorist registration messages
     *
     * @param string $error_message Original message
     * @return string Modified message
     */
    public function registered_msg( $error_message ) {
        // Only change text and link text, keep href intact
        if ( strpos( $error_message, 'The account page is only accessible to logged-out users.' ) !== false ) {
            // Split by the <a> tag
            $parts = explode( '<a', $error_message, 2 ); // limit to 2 parts
            $link  = isset( $parts[1] ) ? '<a' . $parts[1] : ''; // rebuild link part

            // Change link text
            if ( $link ) {
                $link = preg_replace( '/>(.*?)<\/a>/', '>Ir al Escritorio</a>', $link );
            }

            // Replace main text and append the modified link
            $error_message = 'El área de cliente solo es accesible para usuarios que han iniciado sesión. ' . $link;
        }

        return $error_message;
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
     * Redirect users after login with query parameter
     *
     * @param string $link    Original page URL
     * @param int    $page_id Page ID
     * @return string Modified URL
     */
    public function login_redirect_option( $link, $page_id ) {
        // Append query parameter
        return $link . '?directory_type=preventa';
    }
}
