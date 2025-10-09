<?php
namespace ListRestrictor;

class Admin {
    use Hookable;

    public function init() {
        $this->add_actions([
            'admin_menu' => 'add_admin_menu',
        ]);

        add_action( 'save_post_at_biz_dir', [ $this, 'change_post_type'] , 20, 3 );
    }

    public function change_post_type( $post_id, $post, $update ) {
        // Prevent infinite loops and unnecessary runs
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Only target specific post types
        if ( get_post_type( $post_id ) !== 'at_biz_dir' ) {
            return;
        }

        // Get all post meta
        $all_meta = get_post_meta( $post_id );

        // Safely read the meta value
        $select_value = isset( $all_meta['_custom-select-2'][0] ) ? $all_meta['_custom-select-2'][0] : '';

        // Update directory type based on the value
        if ( $select_value === 'disable' ) {
            update_post_meta( $post_id, '_directory_type', '58' );
        } elseif ( $select_value === 'enable' ) {
            update_post_meta( $post_id, '_directory_type', '57' );
        }
    }


    public function add_admin_menu() {
        add_menu_page(
            __( 'List Restrictor', 'list-restrictor' ),
            __( 'List Restrictor', 'list-restrictor' ),
            'manage_options',
            'list-restrictor',
            [ $this, 'render_admin_page' ],
            'dashicons-lock',
            20
        );
    }

    public function render_admin_page() {
        echo '<div class="wrap"><h1>' . Helper::esc( 'List Restrictor Admin' ) . '</h1>';
        echo '<p>Settings for list restriction go here.</p></div>';
    }
}
