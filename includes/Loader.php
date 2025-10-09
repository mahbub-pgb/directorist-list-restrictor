<?php
namespace ListRestrictor;

class Loader {

    public function init() {
        if ( is_admin() ) {
            $this->admin();
        } else {
            $this->frontend();

        }
        $this->common();
        $this->ajax();
    }

    private function admin() {
        $admin = new Admin();
        $admin->init();
    }

    private function frontend() {
        $frontend = new Frontend();
        $frontend->init();
    }

    private function common() {
        $common = new Common();
        $common->init();
    }

    private function ajax() {
        $ajax = new Ajax();
        $ajax->init();
    }
}
