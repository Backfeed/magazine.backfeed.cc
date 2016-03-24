<?php
remove_action('wp_ajax_barcelona_pb', 'barcelona_get_module_posts');
remove_action('wp_ajax_nopriv_barcelona_pb', 'barcelona_get_module_posts');
add_action('wp_ajax_barcelona_pb', 'backfeed_get_module_posts');
add_action('wp_ajax_nopriv_barcelona_pb', 'backfeed_get_module_posts');

function backfeed_get_module_posts() {
    header('content-type:text/html; charset=utf-8');

    $barcelona_async = true;
    
    $paged = (array_key_exists('barcelona_paged', $_POST) && is_numeric($_POST['barcelona_paged'])) ? $_POST['barcelona_paged'] : 1;

    $barcelona_q = new WP_Query([
        'posts_per_page'        => 8,
        'post_type'             => 'post',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => true,
        'no_found_rows'         => false,
        'paged'                 => $paged,
        //'orderby'               => 'meta_value_num',
        //'meta_key'              => 'backfeed_contribution_score'
    ]);
    
    include(locate_template('bunch-of-articles.php'));

    exit;
}