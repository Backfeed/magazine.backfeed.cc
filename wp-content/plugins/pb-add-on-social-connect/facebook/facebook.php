<?php
/* Generate the Facebook button */
function wppb_sc_generate_facebook_button( $form_ID ) {
	global $social_connect_instance;

	$class = 'wppb-sc-facebook-login wppb-sc-button';

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
	$check_if_linked = get_user_meta( get_current_user_id(), '_wppb_facebook_connect_id' );
	if( isset( $social_connect_instance->forms_type ) && $social_connect_instance->forms_type == 'edit_profile' && ! empty( $check_if_linked ) ) {
		$class .= ' wppb-sc-disabled-btn';
	}
	$button .= '<a class="' . $class . '" href="#" onclick="return wppbFBLogIn(\'' . $form_ID . '\')">';
    $button .= '<i class="wppb-sc-icon-facebook wppb-sc-icon"></i>';
    if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		if( isset( $social_connect_instance->forms_type ) && $social_connect_instance->forms_type == 'edit_profile' ) {
			if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['facebook-button-text-ep'] ) ) {
				$button .= $social_connect_instance->wppb_social_connect_settings[0]['facebook-button-text-ep'];
			} else {
				$button .= __( 'Link with Facebook', 'profile-builder-social-connect-add-on' );
			}
		} else {
			if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['facebook-button-text'] ) ) {
				$button .= $social_connect_instance->wppb_social_connect_settings[0]['facebook-button-text'];
			} else {
				$button .= __( 'Sign in with Facebook', 'profile-builder-social-connect-add-on' );
			}
		}
    }

    $button .= '</a>';
	if( ! empty( $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] ) && $social_connect_instance->wppb_social_connect_settings[0]['buttons-style'] == 'text' ) {
		$button .= '</div>';
	}

    return $button;
}