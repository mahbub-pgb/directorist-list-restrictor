<?php
namespace ListRestrictor;
use ListRestrictor\Helper;

class Frontend {
    use Hookable;

    public function init() {

        $this->add_actions([
            'wp_head' => 'head', 
        ]);

    }

    public function head() {
    	// Helper::pri( LR_SOLD_SALE_ID );

	}
}
