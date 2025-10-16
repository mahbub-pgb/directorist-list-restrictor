<?php
/**
 * Custom Directorist Grid Template with Login Notice
 * 
 * @author  wpWax
 * @since   6.6
 * @version 8.1
 */

use \Directorist\Helper;
use ListRestrictor\Helper as Help;

if ( ! defined( 'ABSPATH' ) ) exit;

// URL to login page
$loginUrl = wp_login_url( get_permalink() );

// Include is_plugin_active() if not already loaded
if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if the Directorist List Restrictor plugin is active
$plugin_active = is_plugin_active( 'directorist-list-restrictor/directorist-list-restrictor.php' );
?>

<div class="directorist-archive-items directorist-archive-grid-view <?php echo esc_attr( $listings->pagination_infinite_scroll_class() ); ?>">
    <div class="<?php Helper::directorist_container_fluid(); ?>">

        <?php do_action( 'directorist_before_grid_listings_loop' ); ?>

        <?php if ( $listings->have_posts() ) : ?>

            <div class="<?php echo $listings->has_masonry() ? 'directorist-masonry' : ''; ?> <?php Helper::directorist_row(); ?>">

                <?php 

                 $saved_data = array_flip( get_option('listing_status_data', [] ));

                 // Help::pri( $saved_data, true );
                 // Help::pri( $listings->current_listing_type, true );
                // Show login notice for restricted listing type if plugin is active
                if ( $plugin_active && $listings->current_listing_type == $saved_data['pre_sale'] && ! is_user_logged_in() ) : ?>
                    
                    <div class="custom-login-notice" style="
                        border: 1px solid #e0e0e0;
                        padding: 20px;
                        margin-bottom: 20px;
                        background-color: #fff8f0;
                        border-radius: 6px;
                        text-align: center;
                        font-size: 16px;
                        color: #333;
                    ">
                        <span style="font-size: 24px; display: block; margin-bottom: 10px;">🔒 Acceso restringido</span>
                        <p>Por favor, <a href="<?php echo esc_url( $loginUrl ); ?>" style="color: #0073aa; font-weight: bold;">inicie sesión</a> para ver los listados de preventa.</p>
                    </div>

                    <?php
                    // Stop rendering grid for this listing type
                    return;
                endif;

                // Render the grid view
                $listings->render_grid_view( $listings->post_ids() ); 
                ?>

            </div>

            <?php
            // Pagination
            if ( $listings->show_pagination && 'numbered' === $listings->options['pagination_type'] ) {
                do_action( 'directorist_before_listings_pagination' );
                $listings->pagination();
                do_action( 'directorist_after_listings_pagination' );
            }
            ?>

            <?php do_action( 'directorist_after_grid_listings_loop' ); ?>

        <?php else : ?>

            <div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

        <?php endif; ?>

    </div>
</div>
