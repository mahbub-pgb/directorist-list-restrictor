<?php
namespace ListRestrictor;

class Admin {
    use Hookable;

    /**
     * Initialize hooks
     */
    public function init() {
        $this->add_actions([
            'save_post_at_biz_dir' => ['change_post_type', 20, 3], 
            'wp_enqueue_scripts' => 'list_restrictor_enqueue_scripts', 
        ]);

    }
     /**
     * Change directory type and optionally update term relationship
     *
     * @param int     $post_id Post ID
     * @param WP_Post $post    Post object
     * @param bool    $update  Whether this is an update
     */
    public function change_post_type( $post_id, $post, $update ) {
        // Prevent autosave loops
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // Check permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        // Only for 'at_biz_dir' post type
        if ( get_post_type( $post_id ) !== 'at_biz_dir' ) return;

        // Get meta value safely
        $all_meta     = get_post_meta( $post_id );
        $select_value = isset( $all_meta['_custom-select-2'][0] ) ? $all_meta['_custom-select-2'][0] : '';

        // Directory type / term_taxonomy mapping
        $terms = [
            'disable' => 58,
            'enable'  => 57,
            'sold'    => 59,
        ];

        if ( isset( $terms[ $select_value ] ) ) {
            $term_id = intval( $terms[ $select_value ] );
            update_post_meta( $post_id, '_directory_type', $term_id );
            $this->update_post_term_taxonomy( $post_id, $term_id );
        }
    }


    /**
     * Update a post's term_taxonomy_id in wp_term_relationships
     *
     * @param int $post_id               Post ID
     * @param int $new_term_taxonomy_id  New term_taxonomy_id
     * @return int|false Number of rows affected or false
     */
    private function update_post_term_taxonomy( $post_id, $new_term_taxonomy_id ) {
        global $wpdb;

        $post_id = intval( $post_id );
        $new_term_taxonomy_id = intval( $new_term_taxonomy_id );

        if ( ! $post_id || ! $new_term_taxonomy_id ) {
            return false;
        }

        // Check if a relationship exists
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT object_id FROM {$wpdb->term_relationships} WHERE object_id = %d",
            $post_id
        ) );

        if ( $existing ) {
            // Update existing term_taxonomy_id
            $updated = $wpdb->update(
                $wpdb->term_relationships,
                ['term_taxonomy_id' => $new_term_taxonomy_id],
                ['object_id' => $post_id],
                ['%d'],
                ['%d']
            );
        } else {
            // Insert new relationship if none exists
            $updated = $wpdb->insert(
                $wpdb->term_relationships,
                [
                    'object_id'        => $post_id,
                    'term_taxonomy_id' => $new_term_taxonomy_id,
                    'term_order'       => 0,
                ],
                ['%d','%d','%d']
            );
        }

        // Clear caches
        clean_object_term_cache( $post_id, $this->get_taxonomy_by_term_taxonomy_id( $new_term_taxonomy_id ) );

        return $updated;
    }

    /**
     * Helper: Get taxonomy by term_taxonomy_id
     *
     * @param int $term_taxonomy_id
     * @return string|null Taxonomy slug or null
     */
    private function get_taxonomy_by_term_taxonomy_id( $term_taxonomy_id ) {
        global $wpdb;

        return $wpdb->get_var( $wpdb->prepare(
            "SELECT taxonomy FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %d",
            intval( $term_taxonomy_id )
        ) );
    }
}
