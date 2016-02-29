<?php

define( 'BARCELONA_THEME_VERSION', '1.3.0' );
define( 'BARCELONA_THEME_NAME', esc_html_x( 'Barcelona.', 'Theme Name', 'barcelona' ) );
define( 'BARCELONA_THEME_PATH', trailingslashit( get_template_directory_uri() ) );
define( 'BARCELONA_SERVER_PATH', trailingslashit( get_template_directory() ) );
define( 'BARCELONA_DATE_FORMAT', get_option( 'date_format' ) );

/*
 * Require core functionality
 */
$barcelona_core = array( 'helpers', 'snippets', 'theme-support', 'ajax', 'template-tags', 'nav-menu', 'scripts', 'widgets', 'filters' );
foreach ( $barcelona_core as $k ) {
	require_once BARCELONA_SERVER_PATH . 'includes/core/'. sanitize_key( $k ) .'.php';
}

/*
 * Require option-tree functions
 */
require_once BARCELONA_SERVER_PATH .'option-tree/ot-loader.php';
require_once BARCELONA_SERVER_PATH .'includes/admin/theme-options.php';
require_once BARCELONA_SERVER_PATH .'includes/admin/meta-boxes.php';

/*
 * Require TGM plugin activation
 */
require_once BARCELONA_SERVER_PATH .'includes/admin/tgm.php';

/*
 * Set content width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 750;
}

/*
 * BuddyPress definitions
 */
if ( function_exists('buddypress') ) {

	if ( ! defined( 'BP_AVATAR_FULL_WIDTH' ) ) {
		define ( 'BP_AVATAR_FULL_WIDTH', 160 );
	}

	if ( ! defined( 'BP_AVATAR_FULL_HEIGHT' ) ) {
		define ( 'BP_AVATAR_FULL_HEIGHT', 160 );
	}

	if ( ! defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) {
		define ( 'BP_AVATAR_THUMB_HEIGHT', 80 );
	}

	if ( ! defined( 'BP_AVATAR_THUMB_WIDTH' ) ) {
		define ( 'BP_AVATAR_THUMB_WIDTH', 80 );
	}

}