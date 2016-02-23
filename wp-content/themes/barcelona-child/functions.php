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
	wp_enqueue_style( 'backfeed-login', trailingslashit( get_stylesheet_directory_uri() ).'custom-login.css' );
});

add_filter('login_message', function($message) {
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

	if ($action == 'register')
		return '<h1>Register</h1><h2>Micro-copy</h2>';
	else if ($action == 'login')
		return '<h1>Login</h1><h2>Micro-copy</h2>';
	else
		return $message;
});

add_action('login_footer', function() {
	echo '<a class="logingoback" href="javascript:history.back()">&lt; Back</a>';

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';
	if ($action == 'register')
		echo '<div class="login-footer">Already a member? <a href="'.esc_url(wp_login_url()).'">Login</a></div>';
	else if ($action == 'login')
		echo '<div class="login-footer">Not a member? <a href="'.esc_url(wp_registration_url()).'">Register</a></div>';
});

add_filter('login_redirect', function() {
	return esc_url(home_url('/'));
});