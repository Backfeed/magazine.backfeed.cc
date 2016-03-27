<?php
global $post, $wp_query;

if (!isset($barcelona_q)) $barcelona_q = $wp_query;

if (!isset($barcelona_async)) $barcelona_async = false;

if (!$barcelona_async) echo '<div class="posts-box posts-box-6" data-type="t2_0"><div class="posts-wrapper row">';

while ($barcelona_q->have_posts()): $barcelona_q->the_post(); ?>

	<div class="col col-sm-6">

		<article class="post-summary post-format-<?=sanitize_html_class(barcelona_get_post_format())?> clearfix">

			<div class="post-image">

				<a href="<?=esc_url(get_the_permalink())?>" title="<?=esc_attr(get_the_title())?>">
					<?php barcelona_psum_overlay(); barcelona_thumbnail( 'barcelona-sm' ); ?>
				</a>

			</div><!-- .post-image -->

			<div class="post-details">

				<h2 class="post-title">
					<a href="<?=esc_url(get_the_permalink())?>"><?=esc_html(get_the_title())?></a>
				</h2>

				<p class="post-excerpt">
					<?=esc_html(barcelona_get_excerpt(20))?>
				</p>

				<?php barcelona_post_meta(['date', 'views', 'likes', 'comments']); ?>

			</div><!-- .post-details -->

		</article>

	</div>

<?php endwhile;
wp_reset_postdata();

if (!$barcelona_async) echo '</div></div>';