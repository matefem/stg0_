<?php

function capstan_scripts() {
	wp_enqueue_style('capstan-style', "/wp-content/themes/capstan/style.css", array(), null);
	wp_enqueue_script('capstan-js', "/wp-content/themes/capstan/app.js", array(), null, true );

	wp_enqueue_style('capstan-style-smartbanner', "/wp-content/themes/capstan/resources/vendor/smartbanner/smartbanner.min.css", array(), null);
	wp_enqueue_script('capstan-js-smartbanner', "/wp-content/themes/capstan/resources/vendor/smartbanner/smartbanner.min.js", array(), null);

	wp_register_script( 'capstan_admin_ajax_url', '' );
	wp_enqueue_script( 'capstan_admin_ajax_url' );
	wp_add_inline_script('capstan_admin_ajax_url', 'window.ADMIN_AJAX_URL = "'.admin_url('admin-ajax.php').'";');
}
add_action( 'wp_enqueue_scripts', 'capstan_scripts' );

function admin_style() {
	wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
	// wp_enqueue_style('modules-styles', get_template_directory_uri().'/modules-admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');


// Remove useless scripts
function capstan_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' );
}
add_action( 'wp_enqueue_scripts', 'capstan_remove_wp_block_library_css', 100 );

function disable_embeds_code_init() {
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	// add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	// add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );

	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}

add_action( 'init', 'disable_embeds_code_init', 9999 );

add_filter( 'show_admin_bar', '__return_false' );