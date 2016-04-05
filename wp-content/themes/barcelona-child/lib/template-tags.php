<?php
use Backfeed\Api;

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
            $barcelona_html .= '<li><span class="fa fa-star"></span>'. Api::get_contribution($post->id)->score .'</li>';
        }

        if ( in_array( 'views', $barcelona_opt ) ) {
            $barcelona_html .= '<li class="post-views"><span class="fa fa-eye"></span>'. barcelona_get_post_views() .'</li>';
        }

        if ( in_array( 'votedrep', $barcelona_opt ) ) {
            $barcelona_html .= '<li><span class="fa fa-user"></span>'. Api::get_contribution($post->id)->engagedRepPercentage .'</li>';
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