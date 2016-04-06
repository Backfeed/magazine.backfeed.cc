<?php

/* include export class */
require_once 'inc/class-pbie-export.php';

/* add scripts */
add_action( 'admin_init', 'pbie_export_our_json' );

/* export class arguments and call */
function pbie_export_our_json() {
	if( isset( $_POST['cozmos-export'] ) ) {
		/* get Profile Builder version */
		if ( PROFILE_BUILDER == 'Profile Builder Pro' ) {
			$version = 'pro';
		} elseif( PROFILE_BUILDER == 'Profile Builder Hobbyist' ) {
			$version = 'hobbyist';
		}

		$pbie_args = array(
			'options' => array(
				'wppb_display_admin_settings',
				'wppb_general_settings',
				'wppb_profile_builder_'.$version.'_serial_status',
				'wppb_profile_builder_'.$version.'_serial',
				'wppb_manage_fields',
				'wppb_module_settings',
				'wppb_module_settings_description',
				'customRedirectSettings',
				'wppb_emailc_common_settings_from_name',
				'wppb_emailc_common_settings_from_reply_to_email',
				'wppb_admin_emailc_default_registration_email_subject',
				'wppb_admin_emailc_default_registration_email_content',
				'wppb_admin_emailc_registration_with_admin_approval_email_subject',
				'wppb_admin_emailc_registration_with_admin_approval_email_content',
				'wppb_user_emailc_default_registration_email_subject',
				'wppb_user_emailc_default_registration_email_content',
				'wppb_user_emailc_registr_w_email_confirm_email_subject',
				'wppb_user_emailc_registr_w_email_confirm_email_content',
				'wppb_user_emailc_registration_with_admin_approval_email_subject',
				'wppb_user_emailc_registration_with_admin_approval_email_content',
				'wppb_user_emailc_admin_approval_notif_approved_email_subject',
				'wppb_user_emailc_admin_approval_notif_approved_email_content',
				'wppb_user_emailc_admin_approval_notif_unapproved_email_subject',
				'wppb_user_emailc_admin_approval_notif_unapproved_email_content'
			),
			'cpts' => array(
				'wppb-ul-cpt',
				'wppb-rf-cpt',
				'wppb-epf-cpt'
			)
		);

		$pb_prefix = 'PB_';
		$pbie_json_export = new Cozmoslabs_Plugin_Export( $pbie_args );
		$pbie_json_export->download_to_json_format( $pb_prefix );
	}
}

/* Export tab content function */
function pbie_export() {
	?>
	<p><?php _e( 'Export Profile Builder options as a .json file. This allows you to easily import the configuration into another site.', 'pbie' ); ?></p>
	<div class="wrap">
		<form action="" method="post"><input class="button-secondary" type="submit" name="cozmos-export" value=<?php _e( 'Export', 'pbie' ); ?> id="cozmos-export" /></form>
	</div>
<?php
}