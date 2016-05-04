<?php
add_filter('wprss_ftp_converter_post_author', function($user, $source) {
    $user_data = [
        377 => [
            'first_name'	=>	'Michel',
            'last_name'		=>	'Bauwens',
            'user_email'    =>  'michel@p2pfoundation.net'
        ]
    ];
    if ($user == 1) {
        foreach ($user_data as $user_source => $user_info) {
            if ($user_source == $source && !get_user_by('email', $user_info['user_email'])) {
                return wp_insert_user($user_info);
            }
        }
    }
    return $user;

});