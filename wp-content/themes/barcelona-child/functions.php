<?php
/*
 * Barcelona. Child Theme Function File
 * You can modify any function here. Simply copy any function from parent and paste here. It will override the parent's version.
 */

add_action( 'after_setup_theme', 'barcelona_child_theme_scripts', 99 );

function barcelona_child_theme_scripts() {

	add_action( 'wp_enqueue_scripts', 'barcelona_enqueue_scripts_child', 99 );
	add_filter( 'ot_show_pages', '__return_true' );
}

/*
 * Enqueue Child Scripts & Styles
 */
function barcelona_enqueue_scripts_child() {

	if ( ! is_admin() ) {

		wp_register_style( 'barcelona-main-child', trailingslashit( get_stylesheet_directory_uri() ).'style.css', array(), BARCELONA_THEME_VERSION, 'all' );
		wp_enqueue_style( 'barcelona-main-child' );

	}

}

add_action( 'init', 'custom_init', 99 );

function custom_init() {
	add_filter( 'ot_show_pages', '__return_true' );
}