<?php
use Backfeed\Api;

remove_action('wp_ajax_barcelona_pb', 'barcelona_get_module_posts');
remove_action('wp_ajax_nopriv_barcelona_pb', 'barcelona_get_module_posts');
add_action('wp_ajax_barcelona_pb', 'backfeed_get_module_posts');
add_action('wp_ajax_nopriv_barcelona_pb', 'backfeed_get_module_posts');

function backfeed_get_module_posts() {
    // Override the parent theme's function only on the front page
    if (!in_array($_POST['barcelona_page_id'], ['78', '569'])) {
        barcelona_get_module_posts();
    } else {
        header('content-type:text/html; charset=utf-8');
        $barcelona_async = true;
        $paged = (array_key_exists('barcelona_paged', $_POST) && is_numeric($_POST['barcelona_paged'])) ? intval($_POST['barcelona_paged']) : 1;

        switch ($_POST['barcelona_page_id']) {
            case '78':
                if (function_exists('Backfeed\front_page_query')) $barcelona_q = Backfeed\front_page_query($paged);
                include(locate_template('includes/modules/module-c.php'));
                break;
            case '569':
                if (function_exists('Backfeed\raw_space_query')) $barcelona_q = Backfeed\raw_space_query($paged);
                include(locate_template('includes/modules/module-d.php'));
                break;
        }
    }

    wp_die();
}