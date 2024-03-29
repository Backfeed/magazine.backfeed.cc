<?php

require_once('lib/ajax.php');
require_once('lib/autoblogging.php');
require_once('lib/template-tags.php');
require_once('lib/submit-article-form.php');

add_action('after_setup_theme', function() {
	remove_action('wp_head', 'rsd_link'); //removes EditURI/RSD (Really Simple Discovery) link.
	remove_action('wp_head', 'wlwmanifest_link'); //removes wlwmanifest (Windows Live Writer) link.
	remove_action('wp_head', 'wp_generator'); //removes meta name generator.
	remove_action('wp_head', 'wp_shortlink_wp_head'); //removes shortlink.
	remove_action('wp_head', 'feed_links_extra', 3);  //removes comments feed.
	// all actions related to emojis
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');

	add_action('wp_enqueue_scripts', function() {
		wp_dequeue_style( 'barcelona-font' );
		wp_enqueue_style( 'backfeed-font', '//fonts.googleapis.com/css?family=Montserrat:300,400,700' );

		wp_deregister_script( 'underscore' );
		wp_register_script( 'underscore', '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js', [], '1.8.3' );
		wp_enqueue_script( 'underscore' );

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js', [], '2.2.3' );
		wp_enqueue_script( 'jquery' );

		wp_register_style( 'barcelona-main-child', trailingslashit( get_stylesheet_directory_uri() ).'style.css', [], BARCELONA_THEME_VERSION, 'all' );
		wp_enqueue_style( 'barcelona-main-child' );

		wp_enqueue_script( 'backstretch', '//cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js', ['jquery'], false);
//		wp_enqueue_script( 'mailchimp', '//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', [], false, true);
		wp_enqueue_script( 'bf-main', get_stylesheet_directory_uri().'/main.js', [], false, true);
	}, 100);
}, 100);


add_action('widgets_init', function() {
	global $wp_widget_factory;
	remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);
});

// After successful register, login the user and redirect to homepage.
add_action('wppb_register_success', function($http_request, $form_name, $user_id) {
	wp_update_user([
		'ID' => $user_id,
		'display_name' => $http_request['first_name'].' '.$http_request['last_name']
	]);

	wp_signon([
		'user_login' => $http_request['email'],
		'user_password' => $http_request['passw1']
	]);
	wp_redirect(home_url()); exit;
}, 20, 3);

add_filter('gettext', function ($translated_text, $text, $domain) {
	switch ($domain) {
		case 'profile-builder':
			switch ($text) {
				case 'Send these credentials via email.':
					return 'Send username and password to my email.';
				case 'Remove':
					return 'Replace';
			}
	}
	return $translated_text;
}, 20, 3);

add_filter('wp_nav_menu_items', function($items, $args) {
	if ($args->theme_location == 'top') {
		if (is_user_logged_in()) {
			$items .= '<li class="menu-item"><a href="'. wp_logout_url() .'">Logout</a></li>';
		}
	}
	return $items;
}, 10, 2);

// Just added autofocus
add_filter('get_search_form', function() {

	static $barcelona_search_i = 1;

	$form = '<form class="search-form" method="get" action="' . esc_url( home_url( '/' ) ) . '">
				 <div class="search-form-inner"><div class="barcelona-sc-close"><span class="barcelona-ic">&times;</span><span class="barcelona-text">'. esc_html__( 'Close', 'barcelona' ) .'</span></div>
				 	<div class="input-group">
				        <span class="input-group-addon" id="searchAddon'. intval( $barcelona_search_i ) .'"><span class="fa fa-search"></span></span>
		                <input type="text" name="s" class="form-control search-field" autofocus autocomplete="off" placeholder="'. esc_attr_x( 'Search&hellip;', 'placeholder', 'barcelona' ) .'" title="' . esc_attr_x( 'Search for:', 'label', 'barcelona' ) . '" value="'. esc_attr( get_search_query() ) .'" aria-describedby="searchAddon'. intval( $barcelona_search_i ) .'" />
		                <span class="input-group-btn">
		                    <button type="submit" class="btn"><span class="btn-search-text">'. esc_attr_x( 'Search', 'submit button', 'barcelona' ) .'</span><span class="btn-search-icon"><span class="fa fa-search"></span></span></button>
		                </span>
	                </div>
                </div>
            </form>';

	$barcelona_search_i++;

	return $form;

}, 11);