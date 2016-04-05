<?php
use Backfeed\Api;

get_header();

$barcelona_q = new WP_Query([
	'posts_per_page'        => 8,
	'post_type'             => 'post',
	'post_status'           => 'publish',
	'ignore_sticky_posts'   => true,
	'no_found_rows'         => false,
	'paged'                 => 1
]);

$contributions = Api::get_all_contributions();

if (is_array($contributions)) {
	foreach ($contributions as $contribution) {
		if ($contribution->id == '0cdd33a9-ec8c-4b1a-916a-4da7d0c86be9') $contribution->score = 1;
		if ($contribution->id == '0ef09b3a-24fc-44d1-943d-53e3b78a177e') $contribution->score = 2;
		if ($contribution->id == 'f726d77f-7142-4d41-884b-9db282b74a36') $contribution->score = 3;
		if ($contribution->id == '27f14e73-009b-4f94-bf9e-672ede83a4ec') $contribution->score = 4;
		if ($contribution->id == '153baa98-a091-4150-908d-3038228a823d') $contribution->score = 5;
	}
	usort($contributions, function($a, $b) { return $b->score - $a->score; });
	$contribution_ids = array_column($contributions, 'id');
	$barcelona_q->meta_query = [
		[
			'key' => 'backfeed_contribution_id',
			'value' => $contribution_ids,
			'compare' => 'IN'
		]
	];
}

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