<?php

$barcelona_is_pw_req = post_password_required();

get_header();

barcelona_breadcrumb();

barcelona_featured_img();

?>
<div class="<?php echo esc_attr( barcelona_single_class() ); ?>">

	<div class="<?php echo esc_attr( barcelona_row_class() ); ?>">

		<main id="main" class="<?php echo esc_attr( barcelona_main_class() ); ?>">

			<?php if ( have_posts() ): while( have_posts() ): the_post(); ?>

			<article id="post-<?php echo intval( get_the_ID() ); ?>" <?php post_class(); ?>>

				<?php barcelona_featured_img(); ?>

				<section class="post-content">
				<?php

					the_content();

					if ( ! $barcelona_is_pw_req ) {
						wp_link_pages( array(
							'before'   => '<div class="pagination"><span class="page-numbers title">' . esc_html__( 'Pages:', 'barcelona' ) . '</span>',
							'after'    => '</div>',
							'pagelink' => '%'
						) );
					}

				?>
				</section><!-- .post-content -->

				<?php if ( ! $barcelona_is_pw_req ): ?>
				<footer class="post-footer">

					<?php if ( barcelona_get_option( 'show_tags' ) == 'on' && $barcelona_post_tags = get_the_tags() ): ?>
					<div class="post-tags">
						<?php the_tags( '<strong class="title">'. esc_html__( 'Tags:', 'barcelona' ) .'</strong> ' ); ?>
					</div><!-- .post-tags -->
					<?php endif; ?>

					<?php if ( barcelona_get_option( 'show_voting' ) == 'on' ): ?>
					<div class="post-vote row<?php if ( $barcelona_voted = barcelona_is_voted_post() ) { echo ' post-vote-disabled'; } ?>">

						<div class="col col-left col-xs-6">
							<button class="btn btn-vote btn-vote-up<?php echo ( $barcelona_voted == 'up' ) ? ' btn-voted' : ''; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'barcelona-post-vote' ) ); ?>" data-type="up" data-vote-type="post">
								<span class="fa fa-thumbs-up"></span><?php esc_html_e( 'Vote Up', 'barcelona' ); ?>
							</button>
						</div>

						<div class="col col-right col-xs-6">
							<button class="btn btn-vote btn-vote-down<?php echo ( $barcelona_voted == 'down' ) ? ' btn-voted' : ''; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'barcelona-post-vote' ) ); ?>" data-type="down" data-vote-type="post">
								<span class="fa fa-thumbs-down"></span><?php esc_html_e( 'Vote Down', 'barcelona' ); ?>
							</button>
						</div>

					</div><!-- .post-vote -->
					<?php endif; ?>

					<?php if ( barcelona_get_option( 'show_social_sharing' ) == 'on' ): ?>
					<div class="post-sharing">

						<ul class="list-inline text-center">
							<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ); ?>" target="_blank" title="<?php printf( esc_html__( 'Share on %s', 'barcelona' ), 'Facebook' ); ?>"><span class="fa fa-facebook"></span></a></li>
							<li><a href="https://twitter.com/home?status=<?php echo urlencode( get_the_title() .' - '. get_the_permalink() ); ?>" target="_blank" title="<?php printf( esc_html__( 'Share on %s', 'barcelona' ), 'Twitter' ); ?>"><span class="fa fa-twitter"></span></a></li>
							<li><a href="https://plus.google.com/share?url=<?php echo urlencode( get_the_permalink() ); ?>" target="_blank" title="<?php printf( esc_html__( 'Share on %s', 'barcelona' ), 'Google+' ); ?>"><span class="fa fa-google-plus"></span></a></li>
							<li><a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_the_permalink() ); ?>&amp;media=<?php barcelona_thumbnail_url( 'barcelona-lg' ); ?>&amp;description=<?php echo urlencode( get_the_title() ); ?>" target="_blank" title="<?php printf( esc_html__( 'Share on %s', 'barcelona' ), 'Pinterest' ); ?>"><span class="fa fa-pinterest"></span></a></li>
							<li><a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode( get_the_permalink() ); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>&amp;summary=<?php echo urlencode( get_the_excerpt() ); ?>&amp;source=<?php esc_attr( get_bloginfo( 'name' ) ) ?>" target="_blank" title="<?php printf( esc_html__( 'Share on %s', 'barcelona' ), 'Linkedin' ); ?>"><span class="fa fa-linkedin"></span></a></li>
						</ul>

					</div><!-- .post-sharing -->
					<?php endif;

					barcelona_author_box();

					barcelona_page_nav();

					barcelona_post_ad();

					comments_template();

					?>

				</footer><!-- .post-footer -->
				<?php endif; ?>

			</article>

			<?php endwhile; endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</div><!-- .row -->

	<?php barcelona_related_posts(); ?>

</div><!-- .container -->
<?php

get_footer();