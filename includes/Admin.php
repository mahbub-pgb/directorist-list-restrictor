<?php
namespace ListRestrictor;

class Admin {
    use Hookable;

    public function init() {
        $this->add_actions([
            'admin_menu' => 'add_admin_menu',
        ]);
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
