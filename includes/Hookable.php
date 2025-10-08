<?php
namespace ListRestrictor;

trait Hookable {

    /**
     * Register multiple actions at once, with optional priority and args.
     */
    protected function add_actions( array $actions ) {
        foreach ( $actions as $hook => $data ) {
            if ( is_string( $data ) ) {
                // Default: method name only
                add_action( $hook, [ $this, $data ] );
            } elseif ( is_array( $data ) ) {
                // [ method, priority, accepted_args ]
                $method        = $data[0] ?? null;
                $priority      = $data[1] ?? 10;
                $accepted_args = $data[2] ?? 1;

                add_action( $hook, [ $this, $method ], $priority, $accepted_args );
            }
        }
    }


    /**
     * Register multiple filters at once, with optional priority and argument count.
     *
     * Example:
     * $this->add_filters([
     *     'the_content' => ['filter_content', 20, 2],
     *     'init'        => 'do_something_simple',
     * ]);
     */
    protected function add_filters( array $filters ) {
        foreach ( $filters as $hook => $callback ) {

            if ( is_array( $callback ) ) {
                // Format: [ method, priority, accepted_args ]
                $method        = $callback[0] ?? null;
                $priority      = $callback[1] ?? 10;
                $accepted_args = $callback[2] ?? 1;
            } else {
                // Default: just method name string
                $method        = $callback;
                $priority      = 10;
                $accepted_args = 1;
            }

            add_filter( $hook, [ $this, $method ], $priority, $accepted_args );
        }
    }


    /**
     * Quick single hook helper.
     */
    protected function hook( $type, $hook, $method, $priority = 10, $accepted_args = 1 ) {
        if ( $type === 'action' ) {
            add_action( $hook, [ $this, $method ], $priority, $accepted_args );
        } elseif ( $type === 'filter' ) {
            add_filter( $hook, [ $this, $method ], $priority, $accepted_args );
        }
    }
}
