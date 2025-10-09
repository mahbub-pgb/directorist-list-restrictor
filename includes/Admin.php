<?php
namespace ListRestrictor;

class Admin {
    use Hookable;

    public function init() {
        $this->add_actions([
            'save_post_at_biz_dir' => ['change_post_type', 20, 4],
        ]);
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
            // wp_set_post_terms( $post_id, [ 76 ], 'at_biz_dir-category' );
        } elseif ( $select_value === 'enable' ) {
            update_post_meta( $post_id, '_directory_type', '57' );
        } elseif ( $select_value === 'sold' ) {
            update_post_meta( $post_id, '_directory_type', '59' );
        }
    }


    

    
}
