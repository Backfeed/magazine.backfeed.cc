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
            foreach ($contributions as $contribution) {
                if ($contribution->id == '0cdd33a9-ec8c-4b1a-916a-4da7d0c86be9') $contribution->score = 1;
                if ($contribution->id == '0ef09b3a-24fc-44d1-943d-53e3b78a177e') $contribution->score = 2;
                if ($contribution->id == 'f726d77f-7142-4d41-884b-9db282b74a36') $contribution->score = 3;
                if ($contribution->id == '27f14e73-009b-4f94-bf9e-672ede83a4ec') $contribution->score = 4;
                if ($contribution->id == '153baa98-a091-4150-908d-3038228a823d') $contribution->score = 5;
            }
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