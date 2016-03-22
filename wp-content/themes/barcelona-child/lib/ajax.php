<?php

remove_action('wp_ajax_barcelona_pb', 'barcelona_get_module_posts');
remove_action('wp_ajax_nopriv_barcelona_pb', 'barcelona_get_module_posts');

add_action('wp_ajax_barcelona_pb', 'backfeed_get_module_posts');
add_action('wp_ajax_nopriv_barcelona_pb', 'backfeed_get_module_posts');

function backfeed_get_module_posts() {

    header('content-type:text/html; charset=utf-8');

    $barcelona_async = true;
    $barcelona_mod_post_meta = array( 'date' );


    if ( array_key_exists( 'barcelona_paged', $_POST ) && is_numeric( $_POST['barcelona_paged'] ) ) {
        $paged = $_POST['barcelona_paged'];
    } else {
        $paged = 1;
    }

    if (get_post_meta($_POST['barcelona_page_id'], 'backfeed_barcelona_mod', true))
        $barcelona_mod = get_post_meta($_POST['barcelona_page_id'], 'backfeed_barcelona_mod', true);
    else
        return;

    $barcelona_q_params = [
        'posts_per_page'        => 8,
        'post_type'             => 'post',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => true,
        'no_found_rows'         => false,
        'paged'                 => $paged
    ];

    /*
     * Posts Ordering
     */
    switch ( $barcelona_mod['orderby'] ) {
        case 'views':
            $barcelona_q_params['orderby'] = 'meta_value_num';
            $barcelona_q_params['meta_key'] = '_barcelona_views';
            break;
        case 'comments':
            $barcelona_q_params['orderby'] = 'comment_count';
            break;
        case 'votes':
            $barcelona_q_params['orderby'] = 'meta_value_num';
            $barcelona_q_params['meta_key'] = '_barcelona_vote_up';
            break;
        case 'random':
            $barcelona_q_params['orderby'] = 'rand';
            break;
        case 'posts':
            $barcelona_q_params['orderby'] = 'post__in';
            break;
        default:
            $barcelona_q_params['orderby'] = 'date';
    }

    $barcelona_q_params['order'] = ( $barcelona_mod['order'] != 'asc' ) ? 'DESC' : 'ASC';


    if ( isset( $barcelona_q_params ) ) {
        $barcelona_q = new WP_Query( $barcelona_q_params );

        if ( isset( $barcelona_mod ) ) {
            if ( ! $barcelona_q->have_posts() ) {
                $barcelona_mod['module_layout'] = 'none';
            }

            if ( array_key_exists( 'post_meta_choices', $barcelona_mod ) ) {
                $barcelona_mod_post_meta = $barcelona_mod['post_meta_choices'];
            }

            $barcelona_module_layout = $barcelona_mod['module_layout'];
        }

        if (isset($barcelona_module_layout)) {
            include(locate_template('homepage-module.php'));
        }
    }

    exit;
}