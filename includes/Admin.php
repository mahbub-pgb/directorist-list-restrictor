<?php
namespace ListRestrictor;

class Admin {
    use Hookable;

    /**
     * Initialize hooks
     */
    public function init() {
        $this->add_actions([
            'save_post_at_biz_dir'    => ['change_post_type', 20, 3],
            'admin_menu'              => 'admin_menu',
            'admin_enqueue_scripts'   => 'enqueue_script',
        ]);
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_script() {
        wp_enqueue_script(
            'listing-admin',
            LIST_RESTRICTOR_URL . 'assets/js/admin.js',
            ['jquery'],
            '1.0',
            true
        );

        wp_enqueue_style(
            'listing-admin',
            LIST_RESTRICTOR_URL . 'assets/css/admin.css',
            [],
            '1.0'
        );
    }

    /**
     * Add settings submenu under CPT
     */
    public function admin_menu() {
        add_submenu_page(
            'edit.php?post_type=at_biz_dir',
            'Plugin Settings',
            'Restrictor Settings',
            'manage_options',
            'list-restrictor',
            [$this, 'list_restrictor']
        );
    }

    /**
     * Render settings page
     */
    public function list_restrictor() {
        $items = $this->get_all_listing_type_options();
        $status_options = [
            'pre_sale' => 'Pre Sale',
            'sale'     => 'Sale',
            'sold'     => 'Sold',
        ];

        // Handle form submission
        if ( isset( $_POST['listing_status_nonce'] ) && wp_verify_nonce( $_POST['listing_status_nonce'], 'save_listing_status' ) ) {

            // Save Listing Status
            $saved_data = [];
            foreach ( $items as $id => $title ) {
                $saved_data[ $id ] = sanitize_text_field( $_POST[ 'status_' . $id ] ?? '' );
            }
            update_option( 'listing_status_data', $saved_data );

            // Save Login Redirect Page (before login)
            $login_redirect_page = intval( $_POST['login_redirect_page'] ?? 0 );
            update_option( 'login_redirect_page', $login_redirect_page );

            // Save After Login Redirect Page
            $after_login_redirect_page = intval( $_POST['after_login_redirect_page'] ?? 0 );
            update_option( 'after_login_redirect_page', $after_login_redirect_page );

            echo '<div class="notice notice-success is-dismissible"><p>Settings saved!</p></div>';
        }

        $saved_data                = get_option( 'listing_status_data', [] );
        $saved_login_redirect      = get_option( 'login_redirect_page', 99939 );
        $saved_after_login_redirect = get_option( 'after_login_redirect_page', 98882 );
        $all_pages                 = get_pages();
        ?>
        <div class="wrap">
            <h1>Listing Status & Login Redirect</h1>
            <form method="post" id="settings-form">
                <?php wp_nonce_field( 'save_listing_status', 'listing_status_nonce' ); ?>

                <h2>Listing Status Form</h2>
                <table class="form-table listing-status-table">
                    <tbody>
                    <?php foreach ( $items as $id => $title ) : ?>
                        <tr>
                            <th scope="row"><?php echo esc_html( $title ); ?></th>
                            <td>
                                <select name="status_<?php echo esc_attr( $id ); ?>" class="status-select" data-id="<?php echo esc_attr( $id ); ?>">
                                    <option value="">-- Select Status --</option>
                                    <?php foreach ( $status_options as $key => $label ) : ?>
                                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $saved_data[ $id ] ?? '', $key ); ?>>
                                            <?php echo esc_html( $label ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h2>Login Redirect Page (Before Login)</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">Redirect wp-login.php to</th>
                        <td>
                            <select name="login_redirect_page">
                                <?php foreach ( $all_pages as $page ) : ?>
                                    <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $saved_login_redirect, $page->ID ); ?>>
                                        <?php echo esc_html( $page->post_title ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Users visiting wp-login.php will be redirected to this page.</p>
                        </td>
                    </tr>
                </table>

                <h2>After Login Redirect Page</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">Redirect users after login to:</th>
                        <td>
                            <select name="after_login_redirect_page">
                                <?php foreach ( $all_pages as $page ) : ?>
                                    <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $saved_after_login_redirect, $page->ID ); ?>>
                                        <?php echo esc_html( $page->post_title ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Select the page where users should be redirected after login.</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button( 'Save Settings' ); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get all listing types
     *
     * @return array Term ID => Term Name
     */
    public function get_all_listing_type_options() {
        $listing_types = get_terms([
            'taxonomy'   => 'atbdp_listing_types',
            'hide_empty' => false,
            'orderby'    => 'date',
            'order'      => 'DESC',
        ]);

        $options = [];

        if ( ! is_wp_error( $listing_types ) && ! empty( $listing_types ) ) {
            foreach ( $listing_types as $type ) {
                $options[ $type->term_id ] = $type->name;
            }
        }

        return $options;
    }

    /**
     * Change directory type and optionally update term relationship
     *
     * @param int     $post_id Post ID
     * @param WP_Post $post    Post object
     * @param bool    $update  Whether this is an update
     */
    public function change_post_type( $post_id, $post, $update ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;
        if ( get_post_type( $post_id ) !== 'at_biz_dir' ) return;

        $all_meta     = get_post_meta( $post_id );
        $select_value = $all_meta['_custom-select-2'][0] ?? '';

        $saved_data = array_flip( get_option( 'listing_status_data', [] ) );

        $terms = [
            'disable' => $saved_data['pre_sale'] ?? null,
            'enable'  => $saved_data['sale'] ?? null,
            'sold'    => $saved_data['sold'] ?? null,
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
     * @param int $post_id
     * @param int $new_term_taxonomy_id
     * @return int|false
     */
    private function update_post_term_taxonomy( $post_id, $new_term_taxonomy_id ) {
        global $wpdb;

        $post_id = intval( $post_id );
        $new_term_taxonomy_id = intval( $new_term_taxonomy_id );

        if ( ! $post_id || ! $new_term_taxonomy_id ) {
            return false;
        }

        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT object_id FROM {$wpdb->term_relationships} WHERE object_id = %d",
            $post_id
        ) );

        if ( $existing ) {
            $updated = $wpdb->update(
                $wpdb->term_relationships,
                ['term_taxonomy_id' => $new_term_taxonomy_id],
                ['object_id' => $post_id],
                ['%d'],
                ['%d']
            );
        } else {
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

        clean_object_term_cache( $post_id, $this->get_taxonomy_by_term_taxonomy_id( $new_term_taxonomy_id ) );

        return $updated;
    }

    /**
     * Helper: Get taxonomy by term_taxonomy_id
     *
     * @param int $term_taxonomy_id
     * @return string|null
     */
    private function get_taxonomy_by_term_taxonomy_id( $term_taxonomy_id ) {
        global $wpdb;

        return $wpdb->get_var( $wpdb->prepare(
            "SELECT taxonomy FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %d",
            intval( $term_taxonomy_id )
        ) );
    }
}
