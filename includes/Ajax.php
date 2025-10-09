<?php
namespace ListRestrictor;

class Ajax {
    use Hookable;

    /**
     * Initialize hooks
     */
    public function init() {

        $this->add_actions([
                'wp_ajax_nopriv_hide_listing' => 'hide_listing',
                'wp_ajax_hide_listing' => 'hide_listing',
        ]);
    }

    function hide_listing() {
        // Check nonce for security
        if ( ! isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'] , 'list_restrictor_nonce' ) ) {
            wp_send_json_error('Invalid nonce');
        }

        $data_id = (int) $_POST['data_id'];
        $ids = $this->get_post_ids_by_meta( $data_id );

        wp_send_json_success( $ids );


    }

    /**
     * Get post IDs by meta key and value
     *
     * @param string $meta_key   The meta key to search
     * @param mixed  $meta_value The meta value to match
     * @param string $post_type  Optional: restrict by post type
     * @return array             Array of post IDs
     */
    public function get_post_ids_by_meta( $meta_value = '', $meta_key = '_directory_type', $post_type = '' ) {
        global $wpdb;

        // Start base SQL
        $sql = "SELECT pm.post_id
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                WHERE pm.meta_key = %s
                AND pm.meta_value = %s
                AND p.post_status = 'publish'";

        // Prepare params
        $params = [ $meta_key, $meta_value ];

        // Optional post type filter
        if ( ! empty( $post_type ) ) {
            $sql .= " AND p.post_type = %s";
            $params[] = $post_type;
        }

        // Prepare & run query
        $prepared = $wpdb->prepare( $sql, $params );
        $post_ids = $wpdb->get_col( $prepared );

        return $post_ids;
    }


    
}