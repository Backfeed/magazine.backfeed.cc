<?php
/*
 * Barcelona. Child Theme Function File
 * You can modify any function here. Simply copy any function from parent and paste here. It will override the parent's version.
 */

add_action( 'after_setup_theme', 'barcelona_child_theme_scripts', 99 );

function barcelona_child_theme_scripts() {

	add_action( 'wp_enqueue_scripts', 'barcelona_enqueue_scripts_child', 99 );
}

/*
 * Enqueue Child Scripts & Styles
 */
function barcelona_enqueue_scripts_child() {

	if ( ! is_admin() ) {

		wp_register_style( 'barcelona-main-child', trailingslashit( get_stylesheet_directory_uri() ).'style.css', array(), BARCELONA_THEME_VERSION, 'all' );
		wp_enqueue_style( 'barcelona-main-child' );

		wp_enqueue_script( 'mailchimp', '//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', array(), false, true);
		wp_enqueue_script( 'bf-main', get_stylesheet_directory_uri().'/main.js', array(), false, true);

	}

}