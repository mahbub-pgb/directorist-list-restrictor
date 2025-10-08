<?php
namespace ListRestrictor;

class Loader {

    public function init() {
        if ( is_admin() ) {
            $this->admin();
        } else {
            $this->frontend();
        }
    }

    private function admin() {
        $admin = new Admin();
        $admin->init();
    }

    private function frontend() {
        $frontend = new Frontend();
        $frontend->init();
    }
}
