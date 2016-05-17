<?php
// This template is used for the raw space.

global $post, $wp_query;

if (!isset($barcelona_q)) $barcelona_q = $wp_query;

if (!isset($barcelona_async)) $barcelona_async = false;

if (!$barcelona_async) echo '<div class="posts-box posts-box-2" data-type="t2_0">';

if (isset($barcelona_mod_header)) echo $barcelona_mod_header;

if (!$barcelona_async && !is_author()) the_content();

if (!$barcelona_async) echo '<div class="posts-wrapper">';

while ($barcelona_q->have_posts()): $barcelona_q->the_post(); ?>

	<article class="post-summary post-format-<?=sanitize_html_class(barcelona_get_post_format())?> psum-labelled clearfix">

		<div class="post-image">

			<div class="post-date-label">
				<span class="month"><?php barcelona_the_month_abbrev(); ?></span>
				<span class="day"><?=intval(get_the_time('d')); ?></span>
			</div>

			<a href="<?=esc_url( get_the_permalink() )?>" title="<?=esc_attr(get_the_title())?>">
				<?php barcelona_psum_overlay(); barcelona_thumbnail('barcelona-sm'); ?>
			</a>

		</div>
		<!-- .post-image -->

		<div class="post-details">

			<h2 class="post-title">
				<a href="<?=esc_url(get_the_permalink())?>"><?=esc_html(get_the_title())?></a>
			</h2>

			<p class="post-excerpt">
				<?=esc_html(barcelona_get_excerpt(20))?>
			</p>

			<?php backfeed_post_meta(["date", "author", "score", "engagedrep"], false); ?>

		</div>
		<!-- .post-details -->

	</article>

<?php endwhile;
wp_reset_postdata();

if (!$barcelona_async) echo '</div></div>';