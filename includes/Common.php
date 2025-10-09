<?php
namespace ListRestrictor;

class Common {
    use Hookable;

    /**
     * Initialize hooks
     */
    public function init() {
        $this->add_actions([
            'init' => 'run_corn', 
            'check_expired_dates_daily' => 'check_expired_dates_callback', 
        ]);
    }

    public function run_corn(){
        wp_schedule_event( time() + 3600, 'daily', 'my_custom_cron_event' );
        
        if ( ! wp_next_scheduled('check_expired_dates_daily')) {
            wp_schedule_event(time(), 'daily', 'check_expired_dates_daily');
        }
    }

    public function check_expired_dates_callback() {
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

        $posts = get_posts($args);

        $today_ts = strtotime(date('Y-m-d'));

        foreach ($posts as $post) {
            $all_meta = get_post_meta($post->ID);
            $custom_date = isset($all_meta['_custom-date'][0]) ? $all_meta['_custom-date'][0] : '';

            if (!empty($custom_date)) {
                $custom_ts = strtotime($custom_date);

                if ($custom_ts < $today_ts) {
                    // Expired
                    error_log("Post ID {$post->ID} has expired on {$custom_date}");
                    // Or use your Helper::pri if you want
                    // Helper::pri("Post ID {$post->ID} has expired on {$custom_date}");
                }
            }
        }
    }
}