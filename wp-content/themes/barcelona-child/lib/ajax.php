<?php

remove_action('wp_ajax_barcelona_pb', 'barcelona_get_module_posts');
remove_action('wp_ajax_nopriv_barcelona_pb', 'barcelona_get_module_posts');

add_action('wp_ajax_barcelona_pb', 'backfeed_get_module_posts');
add_action('wp_ajax_nopriv_barcelona_pb', 'backfeed_get_module_posts');

function backfeed_get_module_posts() {

    header('content-type:text/html; charset=utf-8');

    $barcelona_async = true;
    $barcelona_mod_post_meta = ['date',	'views', 'likes', 'comments'];


    $paged = (array_key_exists('barcelona_paged', $_POST) && is_numeric($_POST['barcelona_paged'])) ? $_POST['barcelona_paged'] : 1;

    if (get_post_meta($_POST['barcelona_page_id'], 'backfeed_barcelona_mod', true))
        $barcelona_mod = get_post_meta($_POST['barcelona_page_id'], 'backfeed_barcelona_mod', true);
    else
        return;

    $barcelona_q = new WP_Query([
        'posts_per_page'        => 8,
        'post_type'             => 'post',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => true,
        'no_found_rows'         => false,
        'paged'                 => $paged,
        'orderby'               => 'meta_value_num',
        'meta_key'              => 'backfeed_contribution_score'
    ]);

    if (!$barcelona_q->have_posts()) {
        $barcelona_mod['module_layout'] = 'none';
    }

    $barcelona_mod_post_meta = ['date',	'views', 'likes', 'comments'];

    $barcelona_module_layout = $barcelona_mod['module_layout'];

    include(locate_template('homepage-module.php'));

    exit;
}