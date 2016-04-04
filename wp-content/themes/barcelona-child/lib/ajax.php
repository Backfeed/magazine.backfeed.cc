<?php
use Backfeed\Api;

remove_action('wp_ajax_barcelona_pb', 'barcelona_get_module_posts');
remove_action('wp_ajax_nopriv_barcelona_pb', 'barcelona_get_module_posts');
add_action('wp_ajax_barcelona_pb', 'backfeed_get_module_posts');
add_action('wp_ajax_nopriv_barcelona_pb', 'backfeed_get_module_posts');

function backfeed_get_module_posts() {
    // Override the parent theme's function only on the front page
    if ($_REQUEST['barcelona_page_id'] !== "78") {
        barcelona_get_module_posts();
    } else {
        header('content-type:text/html; charset=utf-8');

        $barcelona_async = true;

        $paged = (array_key_exists('barcelona_paged', $_POST) && is_numeric($_POST['barcelona_paged'])) ? $_POST['barcelona_paged'] : 1;

        $barcelona_q = new WP_Query([
            'posts_per_page'        => 8,
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => true,
            'no_found_rows'         => false,
            'paged'                 => $paged
        ]);

        $contributions = Api::get_all_contributions();

        if (is_array($contributions)) {
            usort($contributions, function($a, $b) { return $b->score - $a->score; });
            $contribution_ids = array_column($contributions, 'id');
            $barcelona_q->meta_query = [
                [
                    'key' => 'backfeed_contribution_id',
                    'value' => $contribution_ids,
                    'compare' => 'IN'
                ]
            ];
        }

        include(locate_template('bunch-of-articles.php'));
    }

    wp_die();
}