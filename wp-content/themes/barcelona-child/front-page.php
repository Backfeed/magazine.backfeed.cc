<?php
get_header();

$barcelona_mod = [
	'title' => 'Recent Posts',
	'module_layout' => 'h',
	'g_show_overlay_always' => 'off',
	'g_is_autoplay' => 'off',
	'add_tabs' => 'off',
	'pagination' => 'infinite',
	'tab_type' => 't2',
	'max_number_of_posts' => '0',
	'posts_offset' => '0',
	'post_meta_choices' => [
		0 => 'date',
		1 => 'views',
		2 => 'likes',
		3 => 'comments',
	],
	'filter_tag' => '',
	'filter_post' => '',
	'orderby' => 'date',
	'order' => 'desc',
	'html' => '',
	'prevent_duplication' => 'on'
];

if (!get_post_meta(get_queried_object_id(), 'backfeed_barcelona_mod', true))
	add_post_meta(get_queried_object_id(), 'backfeed_barcelona_mod', $barcelona_mod, true);

//barcelona_featured_img();

//barcelona_featured_posts();

?>

<img style="width: 100%;" src="<?=get_stylesheet_directory_uri()?>/images/homepage-banner.jpg" />

<div class="<?php echo esc_attr( barcelona_single_class() ); ?>">

	<div class="<?php echo esc_attr( barcelona_row_class() ); ?>">

		<main id="main" class="<?php echo esc_attr( barcelona_main_class() ); ?>">

			<?php

			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}

			$barcelona_q_params = [
				'posts_per_page'        => 8,
				'post_type'             => 'post',
				'post_status'           => 'publish',
				'ignore_sticky_posts'   => true,
				'no_found_rows'         => false,
				'paged'                 => $paged
			];

			/*
             * Posts Ordering
             */
			switch ( $barcelona_mod['orderby'] ) {
				case 'views':
					$barcelona_q_params['orderby'] = 'meta_value_num';
					$barcelona_q_params['meta_key'] = 'backfeed_contribution_score';
					break;
				case 'comments':
					$barcelona_q_params['orderby'] = 'comment_count';
					break;
				case 'votes':
					$barcelona_q_params['orderby'] = 'meta_value_num';
					$barcelona_q_params['meta_key'] = '_barcelona_vote_up';
					break;
				case 'random':
					$barcelona_q_params['orderby'] = 'rand';
					break;
				case 'posts':
					$barcelona_q_params['orderby'] = 'post__in';
					break;
				default:
					$barcelona_q_params['orderby'] = 'date';
			}

			$barcelona_q_params['order'] = ( $barcelona_mod['order'] != 'asc' ) ? 'DESC' : 'ASC';

			$barcelona_q = new WP_Query( $barcelona_q_params );
			$barcelona_async = false;

			$barcelona_mod_attr_data = array();
			$barcelona_mod_attr_data['type'] = $barcelona_mod['tab_type'] . '_0';

			$barcelona_mod_post_meta = array();
			if (array_key_exists('post_meta_choices', $barcelona_mod)) {
				$barcelona_mod_post_meta = $barcelona_mod['post_meta_choices'];
			}

			include(locate_template('homepage-module.php'));

			barcelona_pagination( $barcelona_mod['pagination'], $barcelona_q );

			?>
		</main>

		<?php get_sidebar(); ?>

	</div><!-- .row -->

</div><!-- .container -->

<?php

get_footer();