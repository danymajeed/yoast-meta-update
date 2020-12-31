<?php

include __DIR__ . '/classes/yoast-update-controller.php';
add_action( 'plugins_loaded', 'MetaTagsUpdate_init' );

/**
 * Plugin Name: META TAGS UPDATE
 * Description: Update meta tags through Custom WC REST Endpoint
 * Author: Daniyal Majeed
 * Version: 1.0.0
 */


/* CUSTOM WC ENDPOINT */
class META_TAGS_UPDATE  {
    public function add_custom_endpoint(){
      add_filter( 'woocommerce_rest_api_get_rest_namespaces', [ $this, 'woo_custom_api' ] );
    }
    function woo_custom_api( $controllers ) {
        $controllers['wc/v3']['custom'] = 'YoastUpdateController';
        return $controllers;
    }
}


function MetaTagsUpdate_init() {
	if ( class_exists( 'WPSEO_Frontend' ) ) {
        $meta_tags_update = new META_TAGS_UPDATE();
        $meta_tags_update->add_custom_endpoint();
	} else {
		add_action( 'admin_notices', 'wpseo_not_loaded' );
	}
}

function wpseo_not_loaded() {
	printf(
		'<div class="error"><p>%s</p></div>',
		__( '<b>META TAGS UPDATE</b> plugin not working because <b>Yoast SEO</b> plugin is not active.' )
	);
}