<?php
global $post;

function backfeed_post_meta( $barcelona_opt, $barcelona_sep=TRUE, $echo=TRUE ) {

    global $post;

    // TODO: Remove duplication of this code block
    if (function_exists('Backfeed\get_contribution')) {
        $contribution = Backfeed\get_contribution($post->ID);
        $backfeed_contribution_score = round($contribution->stats->score, 2);
        $backfeed_engaged_reputation = round($contribution->stats->engaged_reputation, 2).'%';
    } else {
        barcelona_post_meta($barcelona_opt, $barcelona_sep, $echo);
        return false;
    }

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
            $barcelona_html .= '<li class="backfeed-tooltip"><div class="backfeed-tooltip-content">Article Score</div><span class="fa fa-star"></span>'. $backfeed_contribution_score .'</li>';
        }

        if ( in_array( 'views', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-views"><span class="fa fa-eye"></span>'. barcelona_get_post_views() .'</li>';
        }

        if ( in_array( 'engagedrep', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="backfeed-tooltip"><div class="backfeed-tooltip-content"><div class="backfeed-tooltip-title">Reputation Invested</div> Indicates community engagement in ranking this article. The higher this score, the more reputed members participated in evaluating the quality of this article.</div><span class="fa fa-users"></span>'. $backfeed_engaged_reputation .'</li>';
        }

        if ( in_array( 'likes', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-likes"><span class="fa fa-thumbs-up"></span>'. barcelona_get_post_vote() .'</li>';
        }

        if ( in_array( 'comments', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-comments"><span class="fa fa-comments"></span>'. intval($post->comment_count) .'</li>';
        }

        if ( in_array( 'author', $barcelona_opt ) ) {
            $barcelona_html .= '<li><a href="'. get_author_posts_url( $post->post_author ) .'" rel="author"><span class="fa fa-user"></span>'. get_the_author_meta( 'display_name', $post->post_author ) .'</a>';
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
 * Only changed code next to $barcelona_meta
 */
function backfeed_featured_img( $barcelona_fimg_id=NULL ) {

    global $post;

    // TODO: Remove duplication of this code block
    if (function_exists('Backfeed\get_contribution')) {
        $contribution = Backfeed\get_contribution($post->ID);
        $backfeed_contribution_score = round($contribution->stats->score, 2);
        $backfeed_engaged_reputation = round($contribution->stats->engaged_reputation, 2).'%';
    } else {
        barcelona_featured_img($barcelona_fimg_id);
        return false;
    }

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
        'author' => array( 'user', $barcelona_author_html , get_author_posts_url( $post->post_author ) ),
//        'views' => array( 'eye', esc_html( barcelona_get_post_views() ) ),
        'score' => array( 'star', $backfeed_contribution_score, '', 'Article Score' ),
//        'likes' => array( 'thumbs-up', '<span class="post_vote_up_val">'. esc_html( barcelona_get_post_vote( $post->ID ) ) .'</span>' ),
        'engagedrep' => array( 'users', $backfeed_engaged_reputation, '', '<div class="backfeed-tooltip-title">Reputation Invested</div> Indicates community engagement in ranking this article. The higher this score, the more reputed members participated in evaluating the quality of this article.' ),
        'comments' => array( 'comments', intval( get_comments_number() ) ),
        'categories' => array( 'bars', $barcelona_categories_html )
    );

//    $barcelona_post_meta_choices = barcelona_get_option( 'post_meta_choices' );
    $barcelona_post_meta_choices = ["date", "author", "score", "engagedrep", "comments"];

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
            $barcelona_post_meta_class = 'post-' . sanitize_html_class($k);
            if (isset($v[3])) $barcelona_post_meta_class .= ' backfeed-tooltip';
            $barcelona_post_meta .= '<li class="'.$barcelona_post_meta_class.'">';

            if (isset($v[2]) && $v[2]) $barcelona_post_meta .= '<a href="'. esc_url_raw($v[2]) .'">';
            if (isset($v[3])) $barcelona_post_meta .= '<div class="backfeed-tooltip-content">'. $v[3] .'</div>';
            $barcelona_post_meta .= '<span class="fa fa-'.sanitize_html_class($v[0]).'"></span>';
            $barcelona_post_meta .= '<span class="post-meta-value">'. $v[1] .'</span>';
            if (isset($v[2]) && $v[2]) $barcelona_post_meta .= '</a>';

            $barcelona_post_meta .= '</li>';
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

/*
 * Social Icons
 */
function backfeed_social_icons( $items=array() ) {

    $output = '';

    if ( ! is_array( $items ) || empty( $items ) ) {
        $items = false;
    }

    $barcelona_social_links = barcelona_get_social_links();

    $barcelona_social_links['slack'] = [
        'title' => 'Slack',
        'href' => 'http://slackinvite.backfeed.cc/',
        'icon' => 'slack'
    ];

    if ( ! empty( $barcelona_social_links ) ) {

        $output = '<ul class="social-icons">';

        foreach ( $barcelona_social_links as $k => $v ) {
            $output .= '<li><a target="_blank" href="'. esc_url( $v['href'] ) .'" title="'. esc_attr( $v['title'] ) .'"><span class="fa fa-'. sanitize_html_class( $v['icon'] ) .'"></span></a></li>';
        }

        $output .= '</ul>';

    }

    return $output;

}