<?php
use Backfeed;

function backfeed_post_meta( $barcelona_opt, $barcelona_sep=TRUE, $echo=TRUE ) {

    global $post;

    $barcelona_cls = array( 'post-meta clearfix' );

    if ( ! $barcelona_sep ) {
        $barcelona_cls[] = 'no-sep';
    }

    $barcelona_html = '';

    if ( is_array( $barcelona_opt ) && ! empty( $barcelona_opt ) ) {

        $barcelona_html = '<ul class="'. implode( ' ', $barcelona_cls ) .'">';

        if ( in_array( 'date', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-date"><span class="fa fa-clock-o"></span>'. esc_html( get_the_time( BARCELONA_DATE_FORMAT ) ) .'</li>';
        }

        if ( in_array( 'score', $barcelona_opt ) ) {
            $barcelona_html .= '<li><span class="fa fa-star"></span>'. Backfeed\get_contribution_field($post->ID, 'score') .'</li>';
        }

        if ( in_array( 'views', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-views"><span class="fa fa-eye"></span>'. barcelona_get_post_views() .'</li>';
        }

        if ( in_array( 'votedrep', $barcelona_opt ) ) {
            $barcelona_html .= '<li><span class="fa fa-user"></span>'. Backfeed\get_contribution_field($post->ID, 'engagedRepPercentage') .'</li>';
        }

        if ( in_array( 'likes', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-likes"><span class="fa fa-thumbs-up"></span>'. barcelona_get_post_vote() .'</li>';
        }

        if ( in_array( 'comments', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-comments"><span class="fa fa-comments"></span>'. intval( $post->comment_count ) .'</li>';
        }

        $barcelona_html .= '</ul>';

    }

    if ( $echo ) {
        echo $barcelona_html;
    }

    return $barcelona_html;

}

/*
 * Featured image
 */
function backfeed_featured_img( $barcelona_fimg_id=NULL ) {

    global $post;

    $barcelona_in_loop = in_the_loop();
    $barcelona_post_format = barcelona_get_post_format();
    $barcelona_is_media = in_array( $barcelona_post_format, array( 'audio', 'gallery', 'video' ) );
    $barcelona_post_type = get_post_type();
    $barcelona_display = 'full';
    $barcelona_fimg_disabled = false;

    if ( is_null( $barcelona_fimg_id ) ) {
        $barcelona_fimg_id = sanitize_key( barcelona_get_option( 'featured_image_style' ) );
    }

    if ( $barcelona_fimg_id == 'none' ) {
        $barcelona_fimg_id = 'cl';
        $barcelona_fimg_disabled = true;
    }

    if ( $barcelona_is_media && in_array( $barcelona_fimg_id, array( 'sp', 'fp', 'fs' ) ) ) {
        $barcelona_fimg_id = 'sw';
    }

    if ( in_array( $barcelona_post_format, array( 'gallery', 'video' ) ) && $barcelona_fimg_id != 'cl' ) {
        $barcelona_fimg_id = 'fw';
    }

    if ( $barcelona_in_loop && $barcelona_is_media && $barcelona_fimg_id != 'cl' ) {
        $barcelona_display = 'title';
        $barcelona_fimg_id = 'cl';
    }

    // Post title
    $barcelona_post_title = '<h1 class="post-title">'. esc_html( $post->post_title ) .'</h1>';
    if ( is_attachment() && ! empty( $post->post_excerpt ) ) {
        $barcelona_post_title .= '<h3 class="post-excerpt">'. esc_html( $post->post_excerpt ) .'</h3>';
    }

    if ( barcelona_get_option( 'show_title' ) == 'off' ) {
        $barcelona_post_title = '';
    }

    // Post meta

    $barcelona_categories_html = '<ul class="list-inline">';
    if ( $barcelona_post_type == 'post' ) {
        $barcelona_categories = get_the_category();
        foreach ( $barcelona_categories as $c ) {
            $barcelona_categories_html .= '<li><a href="'. esc_url( get_category_link( $c ) ) .'">'. esc_html( $c->name ) .'</a></li>';
        }
    }
    $barcelona_categories_html .= '</ul>';

    $barcelona_author_html = '<a href="'. get_author_posts_url( $post->post_author ) .'" rel="author">'. get_the_author_meta( 'display_name', $post->post_author ) .'</a>';

    $barcelona_meta = array(
        'date' => array( 'clock-o', esc_html( get_the_date() ) ),
        'author' => array( 'user', $barcelona_author_html ),
        'views' => array( 'eye', esc_html( barcelona_get_post_views() ) ),
        'likes' => array( 'thumbs-up', '<span class="post_vote_up_val">'. esc_html( barcelona_get_post_vote( $post->ID ) ) .'</span>' ),
        'comments' => array( 'comments', intval( get_comments_number() ) ),
        'categories' => array( 'bars', $barcelona_categories_html )
    );

    $barcelona_post_meta_choices = barcelona_get_option( 'post_meta_choices' );

    if ( ! is_array( $barcelona_post_meta_choices ) ) {
        $barcelona_post_meta_choices = array();
    }

    foreach ( $barcelona_meta as $k => $v ) {
        if ( ! in_array( $k, $barcelona_post_meta_choices ) ) {
            unset( $barcelona_meta[ $k ] );
        }
    }

    $barcelona_post_meta = '';
    if ( ! empty( $barcelona_meta ) ) {
        $barcelona_post_meta = '<ul class="post-meta">';
        foreach ( $barcelona_meta as $k => $v ) {
            $barcelona_post_meta .= '<li class="post-'. sanitize_html_class( $k ) .'"><span class="fa fa-'. sanitize_html_class( $v[0] ) .'"></span>'. $v[1] .'</li>';
        }
        $barcelona_post_meta .= '</ul>';
    }

    $barcelona_media_output = '';
    if ( $barcelona_post_format == 'gallery' ) {

        $barcelona_gallery = get_post_meta( get_the_ID(), 'barcelona_format_gallery', true );

        if ( ! empty( $barcelona_gallery ) ) {
            $barcelona_size = ( $barcelona_fimg_id == 'fw' ) ? 'barcelona-lg' : 'barcelona-md';
            $barcelona_media_output = do_shortcode( '[gallery ids="'. esc_attr( $barcelona_gallery ) .'" size="'. esc_attr( $barcelona_size ) .'" type="featured"]' );
        }

    } else if ( in_array( $barcelona_post_format, array( 'audio', 'video' ) ) ) {

        $barcelona_media_format_type = barcelona_get_option( 'format_'. $barcelona_post_format .'_type' );

        if ( $barcelona_media_format_type == 'internal' ) {
            $barcelona_media_output = barcelona_get_option( 'format_'. $barcelona_post_format .'_url' );
        } else if ( $barcelona_media_format_type == 'external' ) {
            $barcelona_media_output = barcelona_get_option( 'format_'. $barcelona_post_format .'_embed' );
        }

        if ( ! empty( $barcelona_media_output ) ) {

            $barcelona_media_output = hybrid_media_grabber( array(
                'split_media'   => true,
                'content'       => $barcelona_media_output
            ) );

        }

    }

    $barcelona_featured_image_url = $barcelona_fimg_disabled ? false : barcelona_get_thumbnail_url( ( $barcelona_fimg_id == 'cl' ? 'barcelona-md' : 'barcelona-full' ), NULL, false );

    $barcelona_featured_image_credit = barcelona_get_option( 'featured_image_credit' );

    $barcelona_fimg_classes = array(
        'fimg-wrapper',
        'fimg-'. $barcelona_fimg_id
    );

    if ( empty( $barcelona_post_meta ) ) {
        $barcelona_fimg_classes[] = 'fimg-no-meta';
    }

    if ( $barcelona_is_media ) {

        $barcelona_fimg_classes = array_merge( $barcelona_fimg_classes, array(
            'fimg-media',
            'fimg-media-'. $barcelona_post_format
        ));

        if ( isset( $barcelona_media_format_type ) ) {
            $barcelona_fimg_classes[] = 'fimg-media-'. $barcelona_media_format_type;
        }

    }

    if ( ! $barcelona_featured_image_url || $barcelona_is_media ) {

        if ( empty( $barcelona_post_title ) ) {
            return false;
        }

        $barcelona_fimg_classes[] = 'fimg-no-thumb';
        $barcelona_featured_image_credit = '';

    }

    if ( $barcelona_in_loop && $barcelona_fimg_id == 'cl' ) { ?>
        <header class="post-image">

            <?php if ( $barcelona_featured_image_url && ! $barcelona_is_media ): ?>
                <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
            <?php endif; ?>
                <script>jQuery(document).ready(function($){ $('.fimg-inner').backstretch('<?php echo esc_url( $barcelona_featured_image_url[0] ); ?>', {fade: 600}); });</script>
            <?php endif; ?>

            <div class="<?php echo implode( ' ', array_unique( $barcelona_fimg_classes ) ); ?>">

                <?php
                if ( $barcelona_post_format == 'video' && $barcelona_display != 'title' ) {
                    echo $barcelona_media_output;
                    $barcelona_display = 'title';
                }
                ?>

                <div class="featured-image">
                    <div class="fimg-inner">
                        <div class="vm-wrapper">
                            <div class="vm-middle">
                                <?php echo ( $barcelona_display == 'title' ? '' : $barcelona_media_output ) . $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- .fimg-wrapper -->

        </header>

    <?php } elseif ( ! $barcelona_in_loop && $barcelona_fimg_id != 'cl' ) {

        $barcelona_fimg_classes[] = 'container'. ( $barcelona_fimg_id != 'fw' ? '-fluid' : '' );

        if ( in_array( $barcelona_fimg_id, array( 'fw', 'sw' ) ) && $barcelona_featured_image_url && ! $barcelona_is_media ) { ?>
            <script>jQuery(document).ready(function($){ $('.fimg-inner').backstretch('<?php echo esc_url( $barcelona_featured_image_url[0] ); ?>', {fade: 600}); });</script>
        <?php }

        echo '<div class="'. implode( ' ', array_unique( $barcelona_fimg_classes ) ) .'">';

        if ( $barcelona_fimg_id == 'fw' ): ?>

            <div class="featured-image">
                <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                    <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
                <?php endif; ?>
                <div class="fimg-inner">
                    <div class="vm-wrapper">
                        <div class="vm-middle">
                            <?php echo $barcelona_is_media ? $barcelona_media_output : $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ( $barcelona_fimg_id == 'sw' ): ?>

            <div class="featured-image">

                <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                    <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
                <?php endif; ?>

                <div class="fimg-inner">

                    <?php if ( $barcelona_post_format == 'audio' ) { ?>
                        <div class="vm-wrapper">
                            <div class="vm-middle">
                                <?php echo $barcelona_media_output; ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="container">
                            <div class="vm-wrapper">
                                <div class="vm-middle">
                                    <?php echo $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>

            </div>

        <?php elseif ( $barcelona_fimg_id == 'sp' ): ?>

            <div class="featured-image">

                <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                    <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
                <?php endif; ?>

                <div class="container">
                    <div class="fimg-inner">

                        <div class="vm-wrapper">
                            <div class="vm-middle">
                                <?php echo $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <?php if ( $barcelona_featured_image_url ): ?>
                    <div class="barcelona-parallax-wrapper">
                        <div class="barcelona-parallax-inner">
                            <img src="<?php echo esc_url( $barcelona_featured_image_url[0] ); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>" />
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        <?php elseif ( $barcelona_fimg_id == 'fs' ): ?>

            <div class="featured-image">

                <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                    <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
                <?php endif; ?>

                <div class="container">
                    <div class="fimg-inner">

                        <div class="vm-wrapper">
                            <div class="vm-middle">
                                <?php echo $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <?php if ( $barcelona_featured_image_url ): ?>
                    <div class="barcelona-parallax-wrapper">
                        <div class="barcelona-parallax-inner">
                            <img src="<?php echo esc_url( $barcelona_featured_image_url[0] ); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>" />
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        <?php elseif ( $barcelona_fimg_id == 'fp' ): ?>

            <div class="featured-image">

                <div class="container">
                    <div class="fimg-inner">

                        <div class="vm-wrapper">
                            <div class="vm-middle">
                                <?php echo $barcelona_post_title ."\n". $barcelona_post_meta; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <?php if ( $barcelona_featured_image_url ): ?>
                    <div class="barcelona-parallax-wrapper">

                        <?php if ( ! empty( $barcelona_featured_image_credit ) ): ?>
                            <span class="featured-image-credit"><?php echo esc_html( $barcelona_featured_image_credit ); ?></span>
                        <?php endif; ?>

                        <div class="barcelona-parallax-inner">
                            <img src="<?php echo esc_url( $barcelona_featured_image_url[0] ); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>" />
                        </div>

                    </div>
                <?php endif; ?>

            </div>

        <?php endif;

        echo '</div>';

    }

}