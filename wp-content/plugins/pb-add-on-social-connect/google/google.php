<?php
add_filter( 'wppb_sc_process_google_response', 'wppb_sc_google_response' );
function wppb_sc_google_response( $platform_response ) {
    $platform_response['first_name'] = $platform_response['name']['givenName'];
    $platform_response['last_name'] = $platform_response['name']['familyName'];

    return $platform_response;
}

/* Generate the Google button */
function wppb_sc_generate_google_button( $form_ID ) {
	global $social_connect_instance;

	$class = 'wppb-sc-google-login wppb-sc-button';
	global $pagenow;
	if( $pagenow == 'wp-login.php' ) {
		$class .= '-wp-default';
	}

	if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		$class .= '-text';
	}

	$button = '';
	if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		$button = '<div class="wppb-sc-buttons-text-div">';
	}
	$check_if_linked = get_user_meta( get_current_user_id(), '_wppb_google_connect_id' );
	if( isset( $social_connect_instance->forms_type ) && $social_connect_instance->forms_type == 'edit_profile' && ! empty( $check_if_linked ) ) {
		$class .= ' wppb-sc-disabled-btn';
	}
    $button .= '<a class="' . $class . '" href="#" onclick="return wppbGPLogin(\'' . $form_ID . '\')">';
    $button .= '<i class="wppb-sc-icon-google-plus wppb-sc-icon"></i>';
    if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		if( isset( $social_connect_instance->forms_type ) && $social_connect_instance->forms_type == 'edit_profile' ) {
			if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['google-button-text-ep'] ) ) {
				$button .= $social_connect_instance->wppb_social_connect_settings[0]['google-button-text-ep'];
			} else {
				$button .= __( 'Link with Google+', 'profile-builder-social-connect-add-on' );
			}
		} else {
			if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['google-button-text'] ) ) {
				$button .= $social_connect_instance->wppb_social_connect_settings[0]['google-button-text'];
			} else {
				$button .= __( 'Sign in with Google+', 'profile-builder-social-connect-add-on' );
			}
		}
    }

    $button .= '</a>';
	if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		$button .= '</div>';
	}

    return $button;
}