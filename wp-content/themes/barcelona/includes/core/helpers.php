<?php

/*
 * Require helper classes
 */
function barcelona_helper_classes() {

	if ( ! class_exists( 'Hybrid_Media_Grabber' ) ) {
		require_once BARCELONA_SERVER_PATH .'includes/classes/class-hybrid-media-grabber.php';
	}

	if ( ! class_exists( 'Mobile_Detect' ) ) {
		require_once BARCELONA_SERVER_PATH .'includes/classes/Mobile_Detect.php';
	}

}
add_action( 'init', 'barcelona_helper_classes' );

/*
 * Get current protocol
 */
function barcelona_get_protocol() {

	return is_ssl() ? 'https:' : 'http:';

}

/*
 * Check if is empty
 */
function barcelona_is_empty( $str ) {

	if ( ! is_string( $str ) ) {
		return true;
	}

	$str = preg_replace( '#\s+#is', '', $str );
	$str = str_replace( '&nbsp;', '', $str );
	$str = preg_replace( '#<br\s?\/?>#is', '', $str );

	if ( empty( $str ) ) {
		return true;
	}

	return false;

}

/*
 * Get social links
 */
function barcelona_get_social_links() {

	$barcelona_social_links = array(
		'rss' => array(
			'title' => esc_html__( 'RSS Feed', 'barcelona' ),
			'href' => barcelona_get_option( 'social_rss_feed_url' )
		),
		'facebook' => array(
			'title' => 'Facebook',
			'href' => barcelona_get_option( 'social_facebook_url' )
		),
		'twitter' => array(
			'title' => 'Twitter',
			'href' => barcelona_get_option( 'social_twitter_url' )
		),
		'google-plus' => array(
			'title' => 'Google Plus',
			'href' => barcelona_get_option( 'social_google_plus_url' )
		),
		'linkedin' => array(
			'title' => 'Linkedin',
			'href' => barcelona_get_option( 'social_linkedin_url' )
		),
		'youtube' => array(
			'title' => 'Youtube',
			'href' => barcelona_get_option( 'social_youtube_url' )
		),
		'vimeo' => array(
			'title' => 'Vimeo',
			'href' => barcelona_get_option( 'social_vimeo_url' )
		),
		'vk' => array(
			'title' => 'VKontakte',
			'href' => barcelona_get_option( 'social_vk_url' )
		),
		'instagram' => array(
			'title' => 'Instagram',
			'href' => barcelona_get_option( 'social_instagram_url' )
		),
		'pinterest' => array(
			'title' => 'Pinterest',
			'href' => barcelona_get_option( 'social_pinterest_url' )
		),
		'github' => array(
			'title' => 'Github',
			'href' => barcelona_get_option( 'social_github_url' )
		),
		'flickr' => array(
			'title' => 'Flickr',
			'href' => barcelona_get_option( 'social_flickr_url' )
		)
	);

	foreach ( $barcelona_social_links as $k => $v ) {

		if ( ! empty( $v['href'] ) ) {

			$i = $k;

			switch( $k ) {
				case 'vimeo':
					$i = 'vimeo-square';
					break;
				case 'pinterest':
					$i = 'pinterest-p';
					break;
			}

			$barcelona_social_links[ $k ]['icon'] = $i;

		} else {

			unset( $barcelona_social_links[ $k ] );

		}

	}

	return $barcelona_social_links;

}

/*
 * Get author social links
 */
function barcelona_get_author_social_links( $author_id ) {

	$barcelona_social_links = array(
		'facebook' => array(
			'title' => 'Facebook',
			'href' => get_the_author_meta( 'facebook', $author_id )
		),
		'twitter' => array(
			'title' => 'Twitter',
			'href' => get_the_author_meta( 'twitter', $author_id )
		),
		'instagram' => array(
			'title' => 'Instagram',
			'href' => get_the_author_meta( 'instagram', $author_id )
		),
		'google-plus' => array(
			'title' => 'Google Plus',
			'href' => get_the_author_meta( 'google_plus', $author_id )
		),
		'linkedin' => array(
			'title' => 'Linkedin',
			'href' => get_the_author_meta( 'linkedin', $author_id )
		)
	);

	foreach ( $barcelona_social_links as $k => $v ) {

		if ( ! empty( $v['href'] ) ) {

			$barcelona_social_links[ $k ]['icon'] = $k;

		} else {

			unset( $barcelona_social_links[ $k ] );

		}

	}

	return $barcelona_social_links;

}

/*
 * Get locale
 */
function barcelona_get_locale() {

	$barcelona_locale = get_locale();

	if( preg_match( '#^[a-z]{2}\-[A-Z]{2}$#', $barcelona_locale ) ) {

		$barcelona_locale = str_replace( '-', '_', $barcelona_locale );

	} else if ( preg_match( '#^[a-z]{2}$#', $barcelona_locale ) ) {

		$barcelona_locale .= '_'. mb_strtoupper( $barcelona_locale, 'UTF-8' );

	}

	if ( empty( $barcelona_locale ) ) {
		$barcelona_locale = 'en_US';
	}

	return $barcelona_locale;

}

/*
 * Get featured image url (void)
 */
function barcelona_thumbnail_url( $size, $post_id = NULL ) {

	$barcelona_thumbnail_url = barcelona_get_thumbnail_url( $size, $post_id );

	echo is_array( $barcelona_thumbnail_url ) ? esc_url( $barcelona_thumbnail_url[0] ) : '';

}

/*
 * Get featured image url
 */
function barcelona_get_thumbnail_url( $size, $post_id=NULL, $placeholder=TRUE, $is_attachment=FALSE ) {

	$attachment_id = ( is_attachment( $post_id ) || $is_attachment ) ? $post_id : get_post_thumbnail_id( $post_id );

	$output = false;

	if ( ( ( is_attachment( $post_id ) || $is_attachment ) && wp_attachment_is_image( $post_id ) ) || has_post_thumbnail( $post_id ) ) {

		$output = wp_get_attachment_image_src( $attachment_id, $size );

	} else if ( $placeholder ) { // Return default post thumbnail image url

		$output = array( 'assets/images/placeholders/'. ( $size == 'full' ? 'barcelona-full' : $size ) .'-pthumb.jpg', 0, 0 );

		if ( is_readable( BARCELONA_SERVER_PATH . $output[0] ) ) {
			@list( $output[1], $output[2] ) = getimagesize( BARCELONA_SERVER_PATH . $output[0] );
		}

		$output[0] = BARCELONA_THEME_PATH . $output[0];

	}

	if ( is_array( $output ) ) {
		$output[0] = esc_url( $output[0] );
	}

	return $output;

}

/*
 * Get featured image thumbnail (void)
 */
function barcelona_thumbnail( $size, $post_id = NULL, $attr = array() ) {

	echo barcelona_get_thumbnail( $size, $post_id, $attr );

}

/*
 * Get featured image thumbnail
 */
function barcelona_get_thumbnail( $size, $post_id = NULL, $attr = array() ) {

	if ( has_post_thumbnail( $post_id ) && get_the_post_thumbnail( $post_id ) != NULL ) {

		$output = get_the_post_thumbnail( $post_id, $size, $attr );

	} else { // Return default post thumbnail

		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$barcelona_post_format = get_post_format( $post_id );

		if ( $barcelona_post_format == 'video' && barcelona_get_option( 'use_yt_video_cover__single' ) == 'on' ) {
			$barcelona_placeholder_img = barcelona_get_youtube_video_thumbnail( get_post_meta( $post_id, 'barcelona_format_video_embed', true ), $size );
		}

		if ( ! isset( $barcelona_placeholder_img ) || false === $barcelona_placeholder_img ) {

			$barcelona_placeholder_img = 'assets/images/placeholders/' . ( $size == 'full' ? 'barcelona-full' : $size ) . '-pthumb.jpg';

			if ( is_readable( BARCELONA_SERVER_PATH . $barcelona_placeholder_img ) ) {
				@list( $barcelona_width, $barcelona_height ) = getimagesize( BARCELONA_SERVER_PATH . $barcelona_placeholder_img );
			}

			$barcelona_placeholder_img = BARCELONA_THEME_PATH . $barcelona_placeholder_img;

		}

		$output = '<img src="'. $barcelona_placeholder_img .'"'. ( isset( $barcelona_width ) && isset( $barcelona_height ) ? ' width="'. $barcelona_width .'" height="'. $barcelona_height .'"' : '' ) .' />';

	}

	return $output;

}

/*
 * Get post vote (void)
 */
function barcelona_post_vote( $post_id = NULL, $type = 'up' ) {

	echo barcelona_get_post_vote( $post_id, $type );

}

/*
 * Get Post Vote
 */
function barcelona_get_post_vote( $post_id = NULL, $type = 'up' ) {

	if ( is_null( $post_id ) ) {

		$post_id = get_the_ID();

		if( empty( $post_id ) ) {
			return false;
		}

	}

	if( ! in_array( $type, array( 'up', 'down' ) ) ) {
		return false;
	}

	$barcelona_vote_count = get_post_meta( $post_id, '_barcelona_vote_'. $type, true );
	if ( empty( $barcelona_vote_count ) || ! is_numeric( $barcelona_vote_count ) ) {
		$barcelona_vote_count = 0;
	}

	return intval( $barcelona_vote_count );

}

/*
 * Check if post is voted
 */
function barcelona_is_voted_post( $post_id = NULL ) {

	if ( is_null( $post_id ) ) {

		$post_id = get_the_ID();

		if( empty( $post_id ) ) {
			return false;
		}

	}

	$barcelona_voted_posts = array_key_exists( 'barcelona_voted_posts', $_COOKIE ) ? stripcslashes( $_COOKIE['barcelona_voted_posts'] ) : '';

	if ( ! empty( $barcelona_voted_posts ) ) {

		$barcelona_voted_posts = json_decode( $barcelona_voted_posts, true );

		if ( is_array( $barcelona_voted_posts ) && array_key_exists( 'post_'. $post_id, $barcelona_voted_posts ) ) {
			return $barcelona_voted_posts[ 'post_'. $post_id ];
		}

	}

	return false;

}

/*
 * Get comment vote (void)
 */
function barcelona_comment_vote( $comment_id = NULL, $type = 'up' ) {

	echo barcelona_get_comment_vote( $comment_id, $type );

}

/*
 * Get comment vote
 */
function barcelona_get_comment_vote( $comment_id = NULL, $type = 'up' ) {

	if ( is_null ( $comment_id ) ) {

		global $comment;

		if ( is_object( $comment ) ) {
			$comment_id = $comment->comment_ID;
		} else {
			return false;
		}

	}

	if( ! in_array( $type, array( 'up', 'down' ) ) ) {
		return false;
	}

	$barcelona_vote_count = get_comment_meta( $comment_id, '_barcelona_vote_'. $type, true );
	if ( empty( $barcelona_vote_count ) ) {
		$barcelona_vote_count = 0;
	}

	return intval( $barcelona_vote_count );

}

/*
 * Check if post is voted
 */
function barcelona_is_voted_comment( $comment_id = NULL ) {

	if ( is_null ( $comment_id ) ) {

		global $comment;

		if ( is_object( $comment ) ) {
			$comment_id = $comment->comment_ID;
		} else {
			return false;
		}

	}

	$barcelona_voted_comments = array_key_exists( 'barcelona_voted_comments', $_COOKIE ) ? stripcslashes( $_COOKIE['barcelona_voted_comments'] ) : '';

	if ( ! empty( $barcelona_voted_comments ) ) {

		$barcelona_voted_comments = json_decode( $barcelona_voted_comments, true );

		if ( is_array( $barcelona_voted_comments ) && array_key_exists( 'comment_'. $comment_id, $barcelona_voted_comments ) ) {
			return $barcelona_voted_comments[ 'comment_'. $comment_id ];
		}

	}

	return false;

}

/*
 * Get excerpt (void)
 */
function barcelona_excerpt( $length ) {

	echo barcelona_get_excerpt( $length );

}

/*
 * Get excerpt
 */
function barcelona_get_excerpt( $barcelona_words_length ) {

	global $post;

	if ( post_password_required() ) {
		return esc_html__( 'There is no excerpt because this is a protected post.', 'barcelona' );
	}

	$barcelona_post_excerpt = $post->post_excerpt;

	if ( $barcelona_post_excerpt == NULL ) {

		$barcelona_post_excerpt = $post->post_content;
		$barcelona_post_excerpt = strip_shortcodes( $barcelona_post_excerpt );
		$barcelona_post_excerpt = str_replace( ']]>', ']]&gt;', $barcelona_post_excerpt );
		$barcelona_post_excerpt = strip_tags( $barcelona_post_excerpt );
		$barcelona_post_excerpt = mb_substr( $barcelona_post_excerpt, 0, intval( $barcelona_words_length ) * 4.2, 'UTF-8' ) .'&hellip;';

	}

	return $barcelona_post_excerpt;

}

/*
 * The month abbrev
 */
function barcelona_the_month_abbrev() {

	$barcelona_m_i = get_the_time( 'm' );
	$barcelona_m = $GLOBALS['month'];
	$barcelona_m_a = $GLOBALS['month_abbrev'];

	echo esc_html( $barcelona_m_a[ $barcelona_m[ $barcelona_m_i ] ] );

}

/*
 * Get option
 */
function barcelona_get_option( $field, $is_default=FALSE, $affix_check=FALSE ) {

	global $wp_query;

	if ( $field == 'social_links' ) {
		return barcelona_get_social_links();
	}

	if ( $field == 'posts_layout' && ( is_archive() || is_search() ) && ! have_posts() ) {
		return 'none';
	}

	// Remove prefix of the key
	if ( strpos( $field, 'barcelona_' ) === 0 ) {
		$field = preg_replace( '#^barcelona_#is', '', $field );
	}

	if ( preg_match( '#__category_([0-9]+)$#is', $field, $barcelona_match ) ) {
		$barcelona_affix = '_category';
		$barcelona_cat_id = end( $barcelona_match );
		$field = preg_replace( '#_' . $barcelona_cat_id . '$#', '', $field );
	} else if ( is_home() ) {
		$barcelona_affix = '_home';
	} else if ( is_category() ) {
		$barcelona_affix = '_category';
		$barcelona_cat_id = $wp_query->get_queried_object_id();
	} else if ( is_tag() ) {
		$barcelona_affix = '_tag';
	} else if ( is_author() ) {
		$barcelona_affix = '_author';
	} else if ( is_search() ) {
		$barcelona_affix = '_search';
	} else if ( is_archive() ) {
		$barcelona_affix = '_archive';
	} else if ( is_single() ) {
		$barcelona_affix = '_single';
	} else if ( is_page() && ! is_front_page() && ! is_page_template() ) {
		$barcelona_affix = '_page';
	}

	/*
	 * Override global options if enabled
	 */
	if ( isset( $barcelona_affix )
		&& ! preg_match( '#_'. $barcelona_affix .'$#', $field )
		&& barcelona_get_option( 'override_options_'. $barcelona_affix ) == 'on' ) {

		$barcelona_global_option = barcelona_get_option( $field .'_'. $barcelona_affix );
		if ( ! empty( $barcelona_global_option ) ) {
			return $barcelona_global_option;
		}

	}

	if ( is_single() || is_page() ) {

		$barcelona_post_option = get_post_meta( get_the_ID(), 'barcelona_'. $field, true );

		if ( ! empty( $barcelona_post_option ) ) {
			return $barcelona_post_option;
		}

	}

	if ( isset( $barcelona_cat_id ) ) {

		$barcelona_cat_option = get_option( '_barcelona_category_'. $barcelona_cat_id );
		$barcelona_f = preg_replace( '#__category$#', '', $field );

		if ( is_array( $barcelona_cat_option ) ) {

			if ( $barcelona_f == 'custom_background' && $barcelona_cat_option['set_background'] == 'custom' ) {

				return $barcelona_cat_option['background'];

			} else if ( in_array( $barcelona_f, array( 'header_ad_1', 'header_ad_2' ) ) ) {

				if ( $barcelona_cat_option['add_header_ad'] == 'custom' ) {
					return $barcelona_cat_option[ $barcelona_f ];
				}

			} else if ( isset( $barcelona_cat_option[ $barcelona_f ] ) ) {

				return $barcelona_cat_option[ $barcelona_f ];

			}

		} else if ( in_array( $barcelona_f, array( 'add_header_ad', 'set_background' ) ) ) {
			return 'inherit';
		}

	}

	/* Whether to check the field more spesific or not */
	$barcelona_check_affix = true;
	if ( in_array( $field, array( 'header_ad_1', 'header_ad_2' ) ) && barcelona_get_option( 'add_header_ad' ) == 'inherit' ) {
		$barcelona_check_affix = false;
	}

	if ( $barcelona_check_affix && isset( $barcelona_affix ) && ! preg_match( '#_' . $barcelona_affix . '$#is', $field ) ) {

		$barcelona_field = 'barcelona_' . $field . '_' . $barcelona_affix;
		$barcelona_opt = barcelona_get_option( $barcelona_field, false, true );
		if ( $barcelona_opt ) {
			return $barcelona_opt;
		}

	}

	// Default values of theme options
	$barcelona_defaults = array(
		'header_custom_code'               => '',
		'footer_custom_code'               => '',
		'css_custom_code'                  => '',
		'show_header_logo_as_text'         => 'off',
		'header_logo_text'                 => get_bloginfo( 'name' ),
		'header_dark_logo_url'             => '',
		'header_dark_retina_logo_url'      => '',
		'header_light_logo_url'            => '',
		'header_light_retina_logo_url'     => '',
		'show_footer_logo_as_text'         => 'off',
		'footer_logo_text'                 => get_bloginfo( 'name' ),
		'footer_dark_logo_url'             => '',
		'footer_dark_retina_logo_url'      => '',
		'footer_light_logo_url'            => '',
		'footer_light_retina_logo_url'     => '',
		'favicon_url'                      => '',
		'apple_touch_icon_iphone'          => '',
		'apple_touch_icon_ipad'            => '',
		'apple_touch_icon_retina'          => '',
		'default_sidebar'                  => 'barcelona-default-sidebar',
		'sidebar_position'                 => 'right',
		'header_style'                     => 'a',
		'show_top_bar_menu'                => 'on',
		'show_header_social_icons'         => 'on',
		'show_footer_sidebars'             => 'on',
		'show_footer_logo'                 => 'on',
		'show_footer_menu'                 => 'on',
		'footer_copyright_text'            => '',
		'mm_orderby'                       => 'date',
		'mm_order'                         => 'desc',
		'show_tags_under_mm'               => 'on',
		'boxed_layout'                     => 'off',
		'sticky_nav_bar'                   => 'on',
		'sticky_sidebars'                  => 'on',
		'show_breadcrumb'                  => 'on',
		'show_cat_title'                   => 'on',
		'disqus_comments'                  => 'off',
		'disqus_sitename'                  => '',
		'posts_layout'                     => 'c',
		'posts_layout__archive'            => 'd',
		'posts_layout__author'             => 'd',
		'fp_style__category'               => 'none',
		'fp_max_number_of_posts__category' => 3,
		'fp_filter_tag__category'          => '',
		'fp_filter_post__category'         => '',
		'fp_orderby__category'             => 'date',
		'fp_order__category'               => 'desc',
		'featured_image_style'             => 'fw',
		'show_comments'                    => 'on',
		'show_comments__page'              => 'on',
		'show_tags'                        => 'on',
		'show_social_sharing'              => 'on',
		'show_social_sharing__page'        => 'off',
		'show_author_box'                  => 'on',
		'show_voting'                      => 'on',
		'show_voting__page'                => 'off',
		'voting_login_req'                 => 'on',
		'show_post_nav'                    => 'on',
		'show_related_posts'               => 'on',
		'related_posts_columns'            => '3',
		'related_posts_num'                => '9',
		'related_posts_meta'               => array( 'date' ),
		'post_meta_choices'                => array( 'date', 'views', 'likes', 'comments' ),
		'use_yt_video_cover'               => 'off',
		'sidebars'                         => '',
		'social_rss_feed_url'              => '',
		'social_facebook_url'              => '',
		'social_twitter_url'               => '',
		'social_google_plus_url'           => '',
		'social_linkedin_url'              => '',
		'social_youtube_url'               => '',
		'social_vimeo_url'                 => '',
		'social_vk_url'                    => '',
		'social_instagram_url'             => '',
		'social_pinterest_url'             => '',
		'social_github_url'                => '',
		'social_flickr_url'                => '',
		'facebook_app_id'                  => '',
		'add_facebook_og_tags'             => 'on',
		'add_facebook_sdk'                 => 'off',
		'twitter_access_token'             => '',
		'twitter_access_token_secret'      => '',
		'twitter_consumer_key'             => '',
		'twitter_consumer_secret'          => '',
		'font_headings'                    => 'Montserrat',
		'font_headings_custom'             => '',
		'font_general'                     => 'Montserrat',
		'font_general_custom'              => '',
		'font_latin_ext'                   => 'off',
		'font_cyrillic_ext'                => 'off',
		'font_greek_charset'               => 'off',
		'top_nav_color_scheme'             => 'dark',
		'footer_color_scheme'              => 'dark',
		'megamenu_color_scheme'            => 'dark',
		'selection_color'                  => '#f2132d',
		'add_header_ad'                    => 'inherit',
		'header_ad_1'                      => '',
		'header_ad_2'                      => '',
		'set_background'                   => 'inherit',
		'custom_background'                => '',
		'show_post_content_ad'             => 'off',
		'post_content_ad_1'                => '',
		'post_content_ad_2'                => '',
		'override_options'                 => 'off'
	);

	$barcelona_pattern = '#__(home|category|tag|author|search|archive|single|page)$#is';

	if ( ! array_key_exists( $field, $barcelona_defaults ) ) {

		if ( preg_match( $barcelona_pattern, $field ) ) {

			$barcelona_default_field = preg_replace( $barcelona_pattern, '', $field );

			if ( ! array_key_exists( $barcelona_default_field, $barcelona_defaults ) ) {
				return false;
			}

			$barcelona_default_option = $barcelona_defaults[ $barcelona_default_field ];

		} else {

			return false;

		}

	}

	if ( ! isset( $barcelona_default_option ) ) {

		$barcelona_default_option = $barcelona_defaults[ $field ];

	} else if ( isset( $barcelona_default_field ) && $is_default ) {

		return barcelona_get_option( $barcelona_default_field );

	}

	if ( function_exists( 'ot_get_option' ) ) {

		$barcelona_result = ot_get_option( 'barcelona_'. $field );
		if ( empty( $barcelona_result ) ) {
			return ( preg_match( $barcelona_pattern, $field ) && $affix_check ) ? false : $barcelona_default_option;
		}

		return $barcelona_result;

	}

	return $barcelona_default_option;

}

/*
 * Get options as an array
 */
function barcelona_get_options( $fields ) {

	$result = array();

	if ( ! is_array( $fields ) ) {
		$fields = array();
	}

	foreach ( $fields as $k ) {
		$result[ $k ] = barcelona_get_option( $k );
	}

	return $result;

}

/*
 * Get class attr
 */
function barcelona_class( $barcelona_cls, $classes ) {

	if ( ! is_array( $classes ) ) {
		$classes = array();
	}

	foreach ( $classes as $v ) {
		$barcelona_cls[] = $v;
	}

	return implode( ' ',  $barcelona_cls );

}

/*
 * Get nav class
 */
function barcelona_nav_class( $classes=array() ) {

	$barcelona_options = barcelona_get_options( array(
		'top_nav_color_scheme',
		'megamenu_color_scheme',
		'sticky_nav_bar',
		'header_style'
	) );

	$barcelona_options['has_nav'] = has_nav_menu( 'main' );

	$barcelona_cls = array(
		'navbar',
		'navbar-static-top',
		'navbar-'. sanitize_html_class( $barcelona_options['top_nav_color_scheme'] ),
		'mega-menu-'. sanitize_html_class( $barcelona_options['megamenu_color_scheme'] ),
		'header-style-'. sanitize_html_class( $barcelona_options['header_style'] )
	);

	if ( $barcelona_options['sticky_nav_bar'] == 'on' && $barcelona_options['has_nav'] ) {
		$barcelona_cls[] = 'navbar-sticky';
	}

	$barcelona_cls[] = $barcelona_options['has_nav'] ? 'has-nav-menu' : 'no-nav-menu';

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get single class
 */
function barcelona_single_class( $classes=array() ) {

	$barcelona_cls = array( 'container', 'single-container' );

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get row (main wrapper) class
 */
function barcelona_row_class( $classes=array() ) {

	$barcelona_sidebar_position = barcelona_get_option( 'sidebar_position' );

	$barcelona_cls = array( 'row-primary', 'sidebar-'. sanitize_html_class( $barcelona_sidebar_position ), 'clearfix' );

	if ( $barcelona_sidebar_position != 'none' ) {
		$barcelona_cls[] = 'has-sidebar';
	}

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get main (column) class
 */
function barcelona_main_class( $classes=array() ) {

	$barcelona_cls = array( 'main' );

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get sidebar class
 */
function barcelona_sidebar_class( $classes=array() ) {

	$barcelona_cls = array();
	if ( barcelona_get_option( 'sticky_sidebars' ) == 'on' ) {
		$barcelona_cls[] = 'sidebar-sticky';
	}

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get footer class
 */
function barcelona_footer_class( $classes=array() ) {

	$barcelona_cls = array(
		'footer',
		'footer-'. sanitize_html_class( barcelona_get_option( 'footer_color_scheme' ) )
	);

	return barcelona_class( $barcelona_cls, $classes );

}

/*
 * Get theme font
 */
function barcelona_get_font( $extra_fonts=FALSE ) {

	$barcelona_options = barcelona_get_options( array(
		'font_headings',
		'font_headings_custom',
		'font_general',
		'font_general_custom',
		'font_latin_ext',
		'font_cyrillic_ext',
		'font_greek_charset'
	) );

	foreach ( array( 'headings', 'general' ) as $k ) {

		$k = 'font_'. $k;

		if ( $barcelona_options[ $k ] == 'custom' && ! empty( $barcelona_options[ $k .'_custom' ] ) ) {
			$barcelona_options[ $k ] = preg_replace( '#\s+#', '+', $barcelona_options[ $k .'_custom' ] );
		}

	}

	$barcelona_font_names = array( $barcelona_options['font_general'] .':400,700,400italic' );
	if ( $barcelona_options['font_headings'] != $barcelona_options['font_general'] ) {
		$barcelona_font_names[] = $barcelona_options['font_headings'] .':400,700';
	}

	if ( is_array( $extra_fonts ) ) {
		$barcelona_font_names = array_merge( $barcelona_font_names, $extra_fonts );
	}

	$barcelona_font_subset = array( 'latin' );
	if ( $barcelona_options['font_cyrillic_ext'] == 'on' ) {
		$barcelona_font_subset[] = 'cyrillic,cyrillic-ext';
	}

	if ( $barcelona_options['font_latin_ext'] == 'on' ) {
		$barcelona_font_subset[] = 'latin-ext';
	}

	if ( $barcelona_options['font_greek_charset'] == 'on' ) {
		$barcelona_font_subset[] = 'greek,greek-ext';
	}

	$barcelona_font_href = barcelona_get_protocol() .'//fonts.googleapis.com/css?family='. implode( '|', $barcelona_font_names );
	if ( count( $barcelona_font_subset ) > 1 ) {
		$barcelona_font_href .= '&subset='. implode( ',', $barcelona_font_subset );
	}

	$result = array( esc_url( $barcelona_font_href ) );

	$barcelona_body_font_name = $barcelona_heading_font_name = strpos( $barcelona_options['font_general'], "+" ) > 0 ? "'". str_replace( "+", " ", $barcelona_options['font_general'] ) ."'" : $barcelona_options['font_general'];
	if ( $barcelona_options['font_headings'] != $barcelona_options['font_general'] ) {
		$barcelona_heading_font_name = strpos( $barcelona_options['font_headings'], "+" ) > 0 ? "'". str_replace( "+", " ", $barcelona_options['font_headings'] ) ."'" : $barcelona_options['font_headings'];
	}

	$result[] = "<style type=\"text/css\">\nbody { font-family: ". $barcelona_body_font_name .", sans-serif; }\nh1,h2,h3,h4,h5,h6 { font-family: ". $barcelona_heading_font_name .", sans-serif; }\n</style>";

	return $result;

}

/*
 * Get background style as css code
 */
function barcelona_get_background( $get_fixed=false ) {

	$output = '';

	$barcelona_bg = barcelona_get_option( 'custom_background' );

	if( isset( $barcelona_bg ) && is_array( $barcelona_bg ) ) {

		foreach ( $barcelona_bg as $k => $v ) {
			if ( ( $k != 'background-color' && empty( $barcelona_bg[ 'background-image' ] ) ) || empty( $v ) ) {
				unset( $barcelona_bg[ $k ] );
			}
		}

		if ( ! empty( $barcelona_bg ) ) {

			$barcelona_code = "body { background: ";

			foreach ( array( 'color', 'image', 'repeat', 'attachment', 'position' ) as $k ) {
				if ( ! empty( $barcelona_bg[ 'background-' . $k ] ) && $barcelona_bg[ 'background-' . $k ] != 'inherit' ) {
					$barcelona_code .= ( $k == 'image' ? 'url(' : '' ) . esc_html( $barcelona_bg[ 'background-' . $k ] ) . ( $k == 'image' ? ') ' : ' ' );
				}
			}

			$barcelona_code .= '!important; ';

			if ( ! empty( $barcelona_bg['background-size'] ) ) {
				$barcelona_code .= "background-size: " . sanitize_key( $barcelona_bg['background-size'] ) . '; ';
				$barcelona_code .= "webkit-background-size: " . sanitize_key( $barcelona_bg['background-size'] ) . '; ';
			}

			if ( ! array_key_exists( 'background-attachment', $barcelona_bg ) || $barcelona_bg['background-attachment'] != 'fixed' ) {
				$output .= $barcelona_code . " }";
			}

			if ( $get_fixed ) {

				if ( ! empty( $barcelona_bg['background-image'] ) && $barcelona_bg['background-attachment'] == 'fixed' ) {
					$output .= esc_url( $barcelona_bg['background-image'] );
				} else {
					$output = '';
				}

			}

		}

	}

	return $output;

}

/*
 * Get post format
 */
function barcelona_get_post_format( $post_id=NULL ) {

	$barcelona_post_format = get_post_format( $post_id );
	if ( false === $barcelona_post_format ) {
		$barcelona_post_format = 'standard';
	}

	return $barcelona_post_format;

}

/*
 * Get post views (void)
 */
function barcelona_post_views( $post_id=NULL ) {

	echo barcelona_get_post_views( $post_id );

}

/*
 * Get post views
 */
function barcelona_get_post_views( $post_id=NULL ) {

	if ( is_null( $post_id ) ) {
		$post_id = get_the_ID();
	}

	/*if ( function_exists( 'stats_get_csv' ) ) {

		$barcelona_stats_csv = stats_get_csv( 'postviews', 'days=-1&post_id='. $post_id );

		if ( is_array( $barcelona_stats_csv ) && ! empty( $barcelona_stats_csv ) ) {
			$barcelona_views = $barcelona_stats_csv[0]['views'];
			return print_r( $barcelona_stats_csv, true );
		}

	}*/

	if ( ! isset( $barcelona_views ) && function_exists( 'ev_get_post_view_count' ) ) {

		$barcelona_views = preg_replace( '#[^0-9]+#', '', ev_get_post_view_count( $post_id ) );

	}

	if ( ! isset( $barcelona_views ) || ! is_numeric( $barcelona_views ) ) {
		$barcelona_views = 0;
	}

	if ( is_single() ) {
		$barcelona_views++;
	}

	return number_format( intval( $barcelona_views ), 0, '.', ',' );

}

/*
 * Convert unix timestamp to 'date ago' format
 */
function barcelona_time_ago_format( $time ) {

	if ( ! is_numeric( $time ) ) {
		return false;
	}

	// passed time in seconds
	$pt = abs( time() - $time );

	$output = '';

	if ( $pt < 1 ) {

		$output = esc_html__( 'just now', 'barcelona' );

	} elseif ( $pt < 60 ) {

		$output = sprintf( esc_html__( '%s seconds ago', 'barcelona' ), $pt );

	} elseif ( $pt < 120 ) {

		$output = esc_html__( 'about a minute ago', 'barcelona' );

	} elseif ( $pt < ( 45 * 60 ) ) {

		$output = sprintf( esc_html__( 'about %s minutes ago', 'barcelona' ), round( $pt / 60 ) );

	} elseif ( $pt < ( 2 * 60 * 60 ) ) {

		$output = esc_html__( 'about an hour ago', 'barcelona' );

	} elseif ( $pt < ( 24 * 60 * 60 ) ) {

		$barcelona_hours = round( $pt / 3600 );
		$output = sprintf( esc_html( _n( 'about an hour ago', 'about %s hours ago', $barcelona_hours,  'barcelona' ) ), $barcelona_hours );

	} elseif ( $pt < ( 48 * 60 * 60 ) ) {

		$output = esc_html__( 'about a day ago', 'barcelona' );

	} elseif ( $pt > ( 48 * 60 * 60 ) && $pt < ( 24 * 60 * 60 * 30 ) ) {

		$barcelona_days = round( $pt / 86400 );
		$output = sprintf( esc_html( _n( 'about a day ago', 'about %s days ago', $barcelona_days, 'barcelona' ) ), $barcelona_days );

	} elseif ( $pt > ( 24 * 60 * 60 * 30 ) && $pt < ( 24 * 60 * 60 * 30 * 12 ) ) {

		$barcelona_months = round( $pt / 2592000 );
		$output = sprintf( esc_html( _n( 'about a month ago', 'about %s months ago', $barcelona_months, 'barcelona' ) ), $barcelona_months );

	} elseif ( $pt > ( 24 * 60 * 60 * 30 * 12 ) ) {

		$barcelona_years = round( $pt / 31104000 );
		$output = sprintf( esc_html( _n( 'about a year ago', 'about %s years ago', $barcelona_years, 'barcelona' ) ), $barcelona_years );

	}

	return $output;

}

/*
 * Get Featured Posts Query
 */
function barcelona_get_featured_posts_query( $id, $type ) {

	if ( $type == 'page' ) {

		$barcelona_opts = array(
			'style'   => get_post_meta( $id, 'barcelona_fp_style', true ),
			'number'  => get_post_meta( $id, 'barcelona_fp_max_number_of_posts', true ),
			'offset'  => get_post_meta( $id, 'barcelona_fp_posts_offset', true ),
			'cat'     => get_post_meta( $id, 'barcelona_fp_filter_category', true ),
			'tag'     => get_post_meta( $id, 'barcelona_fp_filter_tag', true ),
			'post'    => get_post_meta( $id, 'barcelona_fp_filter_post', true ),
			'orderby' => get_post_meta( $id, 'barcelona_fp_orderby', true ),
			'order'   => get_post_meta( $id, 'barcelona_fp_order', true )
		);

	} else if ( $type == 'category' ) {

		$barcelona_opts = array(
			'style'   => barcelona_get_option( 'fp_style__category_' . $id ),
			'number'  => barcelona_get_option( 'fp_max_number_of_posts__category_' . $id ),
			'offset'  => barcelona_get_option( 'fp_posts_offset__category_' . $id ),
			'cat'     => array( $id ),
			'tag'     => barcelona_get_option( 'fp_filter_tag__category_' . $id ),
			'post'    => barcelona_get_option( 'fp_filter_post__category_' . $id ),
			'orderby' => barcelona_get_option( 'fp_orderby__category_' . $id ),
			'order'   => barcelona_get_option( 'fp_order__category_' . $id )
		);

	}

	if ( ! isset( $barcelona_opts ) || $barcelona_opts['style'] == 'none' ) {
		return false;
	}

	$barcelona_params = array(
		'posts_per_page'        => $barcelona_opts['number'],
		'post_type'             => 'post',
		'post_status'           => 'publish',
		'ignore_sticky_posts'   => true,
		'no_found_rows'         => true
	);

	/*
	 * Posts Offset
	 */
	if ( is_numeric( $barcelona_opts['offset'] ) ) {
		$barcelona_params['offset'] = $barcelona_opts['offset'];
	}

	/*
	 * Filter Posts by Category
	 */
	if ( ! empty( $barcelona_opts['cat'] ) ) {
		$barcelona_params['category__in'] = array_values( $barcelona_opts['cat'] );
	}

	/*
	 * Filter Posts by Post IDs
	 */
	if ( ! empty( $barcelona_opts['post'] )  ) {

		$barcelona_params['post__in'] = array_values( array_filter( array_map( function ( $v ) {

			$v = trim( $v );
			if ( ! is_numeric( $v ) || $v <= 0 ) {
				$v = false;
			}

			return $v;

		}, explode( ',', $barcelona_opts['post'] ) ), function( $v ) { return is_numeric( $v ); } ) );

	}

	/*
	 * Filter Posts by Tag Name
	 */
	if ( ! empty( $barcelona_opts['tag'] ) ) {

		$barcelona_tag_names = array_filter( explode( ',', $barcelona_opts['tag'] ) );

		if ( ! empty( $barcelona_tag_names ) ) {

			foreach( $barcelona_tag_names as $barcelona_tag ) {

				$barcelona_tag_term = get_term_by( 'name', trim( $barcelona_tag ), 'post_tag' );
				if ( $barcelona_tag_term ) {
					$barcelona_params['tag__in'][] = $barcelona_tag_term->term_id;
				}

			}

		}

	}

	/*
	 * Posts Ordering
	 */
	switch ( $barcelona_opts['orderby'] ) {
		case 'views':
			$barcelona_params['orderby'] = 'meta_value_num';
			$barcelona_params['meta_key'] = '_barcelona_views';
			break;
		case 'comments':
			$barcelona_params['orderby'] = 'comment_count';
			break;
		case 'votes':
			$barcelona_params['orderby'] = 'meta_value_num';
			$barcelona_params['meta_key'] = '_barcelona_vote_up';
			break;
		case 'random':
			$barcelona_params['orderby'] = 'rand';
			break;
		case 'posts':
			$barcelona_params['orderby'] = 'post__in';
			break;
		default:
			$barcelona_params['orderby'] = 'date';
	}

	$barcelona_params['order'] = ( $barcelona_opts['order'] != 'asc' ) ? 'DESC' : 'ASC';

	$barcelona_query = new WP_Query( $barcelona_params );

	$barcelona_query->fp_style = $barcelona_opts['style'];

	return $barcelona_query;

}

/*
 * Get youtube video thumbnail
 */
function barcelona_get_youtube_video_thumbnail( $embed_code, $thumbnail_size = 'full' ) {

	if ( is_string( $embed_code ) && ! empty( $embed_code ) ) {

		preg_match( '#youtube\.com\/embed\/(.*?)\"#is', $embed_code, $barcelona_match );

		if( is_array( $barcelona_match ) && ! empty( $barcelona_match ) ) {

			$barcelona_match = explode( '?', end( $barcelona_match ) );

			return 'https://i.ytimg.com/vi/'. $barcelona_match[0] .'/maxresdefault.jpg';

		}

	}

	return null;

}