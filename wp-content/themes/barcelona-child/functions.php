<?php
require_once('lib/login.php');

/*
 * Enqueue Child Scripts & Styles
 */
add_action('after_setup_theme', function() {
	add_action('wp_enqueue_scripts', function() {
		if (!is_admin()) {
			wp_register_style( 'barcelona-main-child', trailingslashit( get_stylesheet_directory_uri() ).'style.css', [], BARCELONA_THEME_VERSION, 'all' );
			wp_enqueue_style( 'barcelona-main-child' );

			wp_enqueue_script( 'mailchimp', '//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', [], false, true);
			wp_enqueue_script( 'bf-main', get_stylesheet_directory_uri().'/main.js', [], false, true);
		}
	}, 99);
}, 99);



add_filter('ninja_forms_textarea_rte', function($default_args) {
	$args = [
		'quicktags' => false,
		'drag_drop_upload' => true
	];
	return array_merge($default_args, $args);
});