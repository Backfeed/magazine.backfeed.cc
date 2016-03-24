<?php
get_header();

$barcelona_q = new WP_Query([
	'posts_per_page'        => 8,
	'post_type'             => 'post',
	'post_status'           => 'publish',
	'ignore_sticky_posts'   => true,
	'no_found_rows'         => false,
	'paged'                 => 1,
	//'orderby'               => 'meta_value_num',
	//'meta_key'              => 'backfeed_contribution_score'
]);

$barcelona_async = false;

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
			<?php include(locate_template('bunch-of-articles.php')); ?>
			<?php barcelona_pagination('infinite', $barcelona_q); ?>
		</main>

		<?php get_sidebar(); ?>

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();