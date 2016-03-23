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

	<div class="container backfeed-featured-section">
		<i class="bf-fa bf-fa-close"></i>
		<div class="backfeed-featured-section-titles">
			<h2 class="backfeed-featured-section-title">Be a Content Miner</h2>
			<h3 class="backfeed-featured-section-subtitle">Write, Rate, Share and get Tokens</h3>
		</div>
		<button class="btn">Take me on a Tour</button>
	</div>

<div class="<?=esc_attr(barcelona_single_class())?>">

	<div class="<?=esc_attr(barcelona_row_class())?>">

		<main id="main" class="<?=esc_attr(barcelona_main_class())?>">

			<?php

			$barcelona_q = new WP_Query([
				'posts_per_page'        => 8,
				'post_type'             => 'post',
				'post_status'           => 'publish',
				'ignore_sticky_posts'   => true,
				'no_found_rows'         => false,
				'paged'                 => 1,
				'orderby'               => 'meta_value_num',
				'meta_key'              => 'backfeed_contribution_score'
			]);
			
			$barcelona_async = false;

			$barcelona_mod_attr_data = ['type' => 't2_0'];
			$barcelona_mod_post_meta = ['date',	'views', 'likes', 'comments'];

			include(locate_template('homepage-module.php'));

			barcelona_pagination($barcelona_mod['pagination'], $barcelona_q);

			?>
		</main>

		<?php get_sidebar(); ?>

	</div><!-- .row -->

</div><!-- .container -->

<?php

get_footer();