<?php
require_once('lib/login.php');
require_once('lib/ajax.php');
require_once('lib/template-tags.php');

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

add_filter('gform_rich_text_editor_options', function($editor_settings) {
	$editor_settings['media_buttons'] = true;
	return $editor_settings;
});

// Number of tokens owned by logged in agent, are added to the Submit Article form, so that the form could be hidden
// if agent doesn't have enough tokens for the contribution fee.
if (function_exists('Backfeed\get_current_agent_tokens'))
	add_filter('gform_field_value_tokensOfUser', 'Backfeed\get_current_agent_tokens');

// Update the token values in the UI upon submission of the Submit Article form.
// Assuming the contribution fee is 1.
add_action('gform_after_submission_1', function() {
?><script>
	Array.from(document.getElementsByClassName('backfeed-stat-tokens-value')).forEach(function(element) {
		element.textContent -= 1;
	});
</script><?php
});