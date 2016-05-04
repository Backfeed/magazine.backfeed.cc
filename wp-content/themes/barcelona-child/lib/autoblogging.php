<?php
add_filter('wprss_ftp_converter_post_author', function($user_id, $source) {
    $user_data = [
        867 => [
            'first_name'	=>	'Rajesh',
            'last_name'		=>	'Makwana',
            'user_email'    =>  'Rajesh@stwr.org'
        ],
        851 => [
            'first_name'	=>	'Danny',
            'last_name'		=>	'Spitzberg',
            'user_email'    =>  'stationaery@gmail.com '
        ],
        779  => [
            'first_name'	=>	'Kevin',
            'last_name'		=>	'Carson',
            'user_email'    =>  'free.market.anticapitalist@gmail.com '
        ],
        724  => [
            'first_name'	=>	'George',
            'last_name'		=>	'Dafermos',
            'user_email'    =>  'georgedafermos@gmail.com '
        ],
        723  => [
            'first_name'	=>	'Stacco',
            'last_name'		=>	'Troncoso',
            'user_email'    =>  'staccotroncoso@p2pfoundation.net'
        ],
        377 => [
            'first_name'	=>	'Michel',
            'last_name'		=>	'Bauwens',
            'user_email'    =>  'michel@p2pfoundation.net'
        ]
    ];

    foreach ($user_data as $user_source => $user_info) {
        if ($user_source == $source) {
            if ($user_id == 1) {
                return wp_insert_user($user_info);
            } else {
                $user = get_user_by('id', $user_id);

                if ($user->user_email !== $user_info['user_email']) {
                    wp_update_user($user_info);
                }
            }
        }
    }
    return $user_id;

});