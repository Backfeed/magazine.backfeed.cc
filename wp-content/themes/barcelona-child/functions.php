<?php
/*
 * Enqueue Child Scripts & Styles
 */
add_action( 'after_setup_theme', function() {
	add_action( 'wp_enqueue_scripts', function() {

		if ( ! is_admin() ) {

			wp_register_style( 'barcelona-main-child', trailingslashit( get_stylesheet_directory_uri() ).'style.css', array(), BARCELONA_THEME_VERSION, 'all' );
			wp_enqueue_style( 'barcelona-main-child' );

			wp_enqueue_script( 'mailchimp', '//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', array(), false, true);
			wp_enqueue_script( 'bf-main', get_stylesheet_directory_uri().'/main.js', array(), false, true);

		}

	}, 99 );
}, 99 );

add_action('login_enqueue_scripts', function() {
	wp_enqueue_style( 'backfeed-login', trailingslashit( get_stylesheet_directory_uri() ).'login.css' );
});