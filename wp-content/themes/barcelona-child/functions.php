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

// Remove auto generated feed links
function my_remove_feeds() {
	remove_action('wp_head', 'rsd_link'); //removes EditURI/RSD (Really Simple Discovery) link.
	remove_action('wp_head', 'wlwmanifest_link'); //removes wlwmanifest (Windows Live Writer) link.
	remove_action('wp_head', 'wp_generator'); //removes meta name generator.
	remove_action('wp_head', 'wp_shortlink_wp_head'); //removes shortlink.
	// Might want to keep you RSS Feed
	// remove_action('wp_head', 'feed_links', 2 ); //removes feed links.
	remove_action('wp_head', 'feed_links_extra', 3 );  //removes comments feed.
	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  	// filter to remove TinyMCE emojis
  	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'after_setup_theme', 'my_remove_feeds' );

function remove_recent_comment_style() {
	global $wp_widget_factory;
	remove_action( 
            'wp_head', 
            array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) 
        );
}
add_action( 'widgets_init', 'remove_recent_comment_style' );