<?php
get_header();

if (function_exists('Backfeed\front_page_query')) $barcelona_q = Backfeed\front_page_query();

$barcelona_async = false;
?>

<div class="container backfeed-featured-section-container">
	<div class="row backfeed-featured-section">
<!--		<i class="bf-fa bf-fa-close"></i>-->
		<div class="backfeed-featured-section-titles">
			<h2 class="backfeed-featured-section-title">Be a Content Miner</h2>
			<h3 class="backfeed-featured-section-subtitle">Write, Rate, Share and get Tokens</h3>
		</div>
		<button id="backfeed-featured-section-btn" class="btn">Take me on a Tour</button>
	</div>
</div>

<div class="<?=esc_attr(barcelona_single_class())?>">
	<div class="<?=esc_attr(barcelona_row_class())?>">

		<main id="main" class="<?=esc_attr(barcelona_main_class())?>">
			<?php include(locate_template('includes/modules/module-c.php')); ?>
			<?php barcelona_pagination('infinite', $barcelona_q); ?>
		</main>

		<?php get_sidebar(); ?>

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();