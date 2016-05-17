<?php

    /*
    Plugin Name: Profile Builder - Social Connect Add-On
    Plugin URI: http://www.cozmoslabs.com/wordpress-profile-builder/
    Description: Extends the functionality of Profile Builder by adding the Social Connect capabilities
    Version: 1.0.5
    Author: Cozmoslabs, Madalin Ungureanu, Cristophor Hurduban
    Author URI: http://www.cozmoslabs.com/
    License: GPL2

    == Copyright ==
    Copyright 2015 Cozmoslabs (www.cozmoslabs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
    */

class WPPB_Social_Connect {

	var $available_platforms = array();
	var $platforms = array();
    var $wppb_social_connect_settings;
	var $forms_type;

    function __construct() {
		/* check and use only platforms that are enabled in settings page */
		$this->wppb_social_connect_settings = get_option( 'wppb_social_connect_settings', 'not_found' );

		/* add default settings to db */
		if( $this->wppb_social_connect_settings == 'not_found' ) {
			$wppb_sc_default_options = array(
				array(
					'display-on-the-following-forms'		=> 'pb-login, pb-register, pb-edit-profile, default-login, default-register',
					'buttons-re-order'						=> ', facebook, google, twitter',
					'buttons-location'						=> 'after',
					'buttons-style' 						=> 'icon',
					'facebook-button-text' 					=> 'Sign in with Facebook',
					'google-button-text' 					=> 'Sign in with Google+',
					'twitter-button-text'			 		=> 'Sign in with Twitter',
					'facebook-button-text-ep' 				=> 'Link with Facebook',
					'google-button-text-ep' 				=> 'Link with Google+',
					'twitter-button-text-ep'			 	=> 'Link with Twitter',
					'heading-before-reg-buttons' 			=> 'Sign in with',
					'heading-before-ep-buttons' 			=> 'Link your account with',
					'sc-default-css' 						=> 'yes'
				)
			);

			update_option( 'wppb_social_connect_settings', $wppb_sc_default_options );
		}

		/* array with currently available social platforms */
		$this->available_platforms = array(
			'facebook',
			'google',
			'twitter'
		);

		// arrange buttons if buttons-re-order option exists, else use default order
		if( ! empty( $this->wppb_social_connect_settings[0]['buttons-re-order'] ) ) {
			$ordered_platforms = explode( ', ', strtolower( $this->wppb_social_connect_settings[0]['buttons-re-order'] ) );
			$ordered_platforms = array_filter( $ordered_platforms );

			foreach( $ordered_platforms as $platform ) {
				if( ! empty( $this->wppb_social_connect_settings[0][$platform.'-login'] ) ) {
					$this->platforms[] = $platform;
				}
			}
		} else {
			foreach( $this->available_platforms as $platform ) {
				if( ! empty( $this->wppb_social_connect_settings[0][$platform.'-login'] ) ) {
					$this->platforms[] = $platform;
				}
			}
		}

		/* add Social Connect submenu page */
		add_action( 'init', array( $this, 'wppb_sc_submenu_page' ), 25 );

		/* modify the side info meta-box */
		add_action( 'wck_metabox_content_wppb_social_connect_info', array( $this, 'wppb_social_connect_add_content_before_info' ) );

		/* add a metabox to each Register and Edit Profile multiple forms */
		add_action( 'add_meta_boxes', array( $this, 'wppb_sc_add_meta_boxes' ) );

		/* save metabox option from Register and Edit Profile forms */
		add_action( 'save_post', array( $this, 'wppb_sc_save_meta_boxes_option' ) );

        /* include necessary files  */
        foreach( $this->platforms as $platform ) {
            if( file_exists( plugin_dir_path( __FILE__ ) . $platform . '/' . $platform . '.php' ) ) {
                include_once( plugin_dir_path( __FILE__ ) . $platform . '/' . $platform . '.php' );
            }
        }

        /* enqueue the necessary scripts */
        add_action( 'wp_enqueue_scripts', array( $this, 'wppb_sc_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'wppb_sc_admin_scripts' ) );
		if( ( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'default-login' ) !== false ) || ( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'default-register' ) !== false ) ) {
			add_action( 'login_enqueue_scripts', array( $this, 'wppb_sc_scripts' ) );
		}

		/* enqueue the necessary styles */
		add_action( 'admin_print_styles', array( $this, 'wppb_sc_styles' ) );
		if( isset( $this->wppb_social_connect_settings[0]['sc-default-css'] ) && $this->wppb_social_connect_settings[0]['sc-default-css'] == 'yes' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'wppb_sc_frontend_styles' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'wppb_sc_frontend_styles' ) );
		}


        /* add the buttons on the login, register and edit-profile forms here */
        if( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'pb-login' ) !== false ) {
            add_filter( 'wppb_login_form_bottom', array( $this, 'wppb_sc_login_form_bottom' ) );
        }

        if( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'pb-register' ) !== false ) {
			add_action( 'wppb_' . ( $this->wppb_social_connect_settings[0]['buttons-location'] == 'before' ? 'before' : 'after' ) . '_register_fields', array( $this, 'wppb_sc_display_on_forms' ), 10, 3 );
		}

        if( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'pb-edit-profile' ) !== false ) {
			add_action( 'wppb_' . ( $this->wppb_social_connect_settings[0]['buttons-location'] == 'before' ? 'before' : 'after' ) . '_edit_profile_fields', array( $this, 'wppb_sc_display_on_forms' ), 10, 3 );
		}

        /* we save an internal security token and platform name */
        add_action( 'wp_ajax_wppb_sc_save_cookies', array( $this, 'wppb_sc_save_cookies' ) );
        add_action( 'wp_ajax_nopriv_wppb_sc_save_cookies', array( $this, 'wppb_sc_save_cookies' ) );

        /* we need to delete the token on logout */
        add_action( 'wp_logout', array( $this, 'wppb_sc_delete_security_token' ) );

        /* general login click function */
        add_action( 'wp_ajax_wppb_sc_handle_login_click', array( $this, 'wppb_sc_handle_login_click' ) );
        add_action( 'wp_ajax_nopriv_wppb_sc_handle_login_click', array( $this, 'wppb_sc_handle_login_click' ) );

		/* check platform id in database function */
		add_action( 'wp_ajax_wppb_sc_handle_platform_id_check', array( $this, 'wppb_sc_handle_platform_id_check' ) );
		add_action( 'wp_ajax_nopriv_wppb_sc_handle_platform_id_check', array( $this, 'wppb_sc_handle_platform_id_check' ) );

        /* hook when user is activated when email confirmation is on */
        add_action( 'wppb_add_other_meta_on_user_activation', array( $this, 'wppb_sc_add_social_platform_meta_id_on_user_activation' ), 10, 2 );

		/* add headings in Social Connect settings page */
		add_action( 'wck_before_add_form_wppb_social_connect_settings_element_0', array( $this, 'wppb_sc_settings_page_heading_general_settings' ) );
		add_action( 'wck_before_add_form_wppb_social_connect_settings_element_1', array( $this, 'wppb_sc_settings_page_heading_application_settings' ) );
		add_action( 'wck_before_add_form_wppb_social_connect_settings_element_8', array( $this, 'wppb_sc_settings_page_heading_appearance_settings' ) );

		/* add Social Connect buttons to WordPress default forms */
		if( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && ( strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'default-login' ) !== false ) ) {
			add_action( 'login_form', array( $this, 'wppb_sc_buttons_for_display_on_forms' ) );
		}
		if( isset( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'] ) && ( strpos( $this->wppb_social_connect_settings[0]['display-on-the-following-forms'], 'default-register' ) !== false ) ) {
			add_action( 'register_form', array( $this, 'wppb_sc_buttons_for_display_on_forms' ) );
		}

		/* unlink account function */
		if( isset( $this->wppb_social_connect_settings[0]['unlink-accounts'] ) && $this->wppb_social_connect_settings[0]['unlink-accounts'] == 'yes' ) {
			add_action( 'wp_ajax_wppb_sc_unlink_account', array( $this, 'wppb_sc_unlink_account' ) );
			add_action( 'wp_ajax_nopriv_wppb_sc_unlink_account', array( $this, 'wppb_sc_unlink_account' ) );
		}
    }


	/**
	 * Function that adds content to the Social Connect sub_menu page
	 *
	 * @since v.1.0.0
	 */
	function wppb_sc_submenu_page() {
		// create a sub_menu page which holds the data for the add-on settings
		$args = array(
			'menu_title' 	=> __( 'Social Connect', 'profile-builder-social-connect-add-on' ),
			'page_title' 	=> __( 'Social Connect', 'profile-builder-social-connect-add-on' ),
			'menu_slug'		=> 'wppb-social-connect',
			'page_type'		=> 'submenu_page',
			'capability'	=> 'manage_options',
			'priority'		=> 12,
			'parent_slug'	=> 'profile-builder'
		);
		if( class_exists( 'WCK_Page_Creator_PB' ) ) {
			new WCK_Page_Creator_PB( $args );
		}

		// make each platform first character uppercase (for Buttons order option)
		foreach( $this->available_platforms as $platform ) {
			$platform_order[] = ucfirst( $platform );
		}

		// settings page fields
		$fields = array(
			array( 'type' => 'checkbox', 'slug' => 'display-on-the-following-forms', 'title' => __( 'Display on the Following Forms', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Profile Builder Login%pb-login', '%Profile Builder Register%pb-register', '%Profile Builder Edit Profile%pb-edit-profile', '%Default WordPress Login%default-login', '%Default WordPress Register%default-register' ) ),
			array( 'type' => 'checkbox', 'slug' => 'facebook-login', 'title' => __( 'Facebook Login', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Enable%yes' ) ),
			array( 'type' => 'text', 'slug' => 'facebook-app-id', 'title' => __( 'Facebook App ID', 'profile-builder-social-connect-add-on' ), 'description' => 'Documentation: <a href="http://www.cozmoslabs.com/docs/profile-builder-2/add-ons/social-connect/create-facebook-app-social-connect" target="_blank">how to create and use a Facebook app</a>' ),
			array( 'type' => 'checkbox', 'slug' => 'google-login', 'title' => __( 'Google+ Login', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Enable%yes' ) ),
			array( 'type' => 'text', 'slug' => 'google-client-id', 'title' => __( 'Google+ Client ID', 'profile-builder-social-connect-add-on' ), 'description' => 'Documentation: <a href="http://www.cozmoslabs.com/docs/profile-builder-2/add-ons/social-connect/create-google-app-social-connect" target="_blank">how to create and use a Google+ app</a>' ),
			array( 'type' => 'checkbox', 'slug' => 'twitter-login', 'title' => __( 'Twitter Login', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Enable%yes' ) ),
			array( 'type' => 'text', 'slug' => 'twitter-api-key', 'title' => __( 'Twitter API Key', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'twitter-api-secret', 'title' => __( 'Twitter API Secret', 'profile-builder-social-connect-add-on' ), 'description' => 'Documentation: <a href="http://www.cozmoslabs.com/docs/profile-builder-2/add-ons/social-connect/create-twitter-app-social-connect" target="_blank">how to create and use a Twitter app</a>' ),
			array( 'type' => 'checkbox', 'slug' => 'buttons-order', 'title' => __( 'Buttons Order', 'profile-builder-social-connect-add-on' ), 'options' => $platform_order, 'description' => __( "Drag and drop the dots to re-order.", 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'buttons-re-order', 'title' => __( 'Buttons Re-Order', 'profile-builder-social-connect-add-on' ), 'description' => __( "Save the buttons order from the buttons order checkboxes", 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'select', 'slug' => 'buttons-location', 'title' => __( 'Buttons Location', 'profile-builder-social-connect-add-on' ), 'options' => array( '%After form fields%after', '%Before form fields%before' ), 'description' => __( 'Only for Profile Builder Register and Edit Profile forms.', 'profile-builder-social-connect-add-on' )  ),
			array( 'type' => 'select', 'slug' => 'buttons-style', 'title' => __( 'Buttons Style', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Small - only icons%icon', '%Large - icons and text%text' ) ),
			array( 'type' => 'text', 'slug' => 'heading-before-reg-buttons', 'title' => __( 'Heading Before Buttons (Login/Register)', 'profile-builder-social-connect-add-on' ), 'description' => __( 'Empty field will remove the heading.', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'heading-before-ep-buttons', 'title' => __( 'Heading Before Buttons (Edit Profile)', 'profile-builder-social-connect-add-on' ), 'description' => __( 'Empty field will remove the heading.', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'facebook-button-text', 'title' => __( 'Facebook Button Text (Login/Register)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'google-button-text', 'title' => __( 'Google+ Button Text (Login/Register)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'twitter-button-text', 'title' => __( 'Twitter Button Text (Login/Register)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'facebook-button-text-ep', 'title' => __( 'Facebook Button Text (Edit Profile)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'google-button-text-ep', 'title' => __( 'Google+ Button Text (Edit Profile)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'text', 'slug' => 'twitter-button-text-ep', 'title' => __( 'Twitter Button Text (Edit Profile)', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'checkbox', 'slug' => 'unlink-accounts', 'title' => __( 'Unlink Accounts (Edit Profile)', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Enable%yes' ), 'description' => __( 'This option will display linked social platforms to users accounts and will allow to easily unlink them in Edit Profile page.', 'profile-builder-social-connect-add-on' ) ),
			array( 'type' => 'checkbox', 'slug' => 'sc-default-css', 'title' => __( 'Default Social Connect CSS in the Front-end', 'profile-builder-social-connect-add-on' ), 'options' => array( '%Enable%yes' ), 'description' => 'You can disable this to use your own CSS file.<br>You can find our default CSS here: <a href="' . plugin_dir_url( __FILE__ ) . 'assets/css/wppb_sc_main_frontend.css" target="_blank">assets/css/wppb_sc_main_frontend.css</a>'  ),
		);

		// create the settings meta-box
		$args = array(
			'metabox_id' 	=> 'wppb-social-connect-id',
			'metabox_title' => __( 'Settings', 'profile-builder-social-connect-add-on' ),
			'post_type' 	=> 'wppb-social-connect',
			'meta_name' 	=> 'wppb_social_connect_settings',
			'meta_array' 	=> $fields,
			'context'		=> 'option',
			'single'		=> 'true'
		);
		if( class_exists( 'Wordpress_Creation_Kit_PB' ) ) {
			new Wordpress_Creation_Kit_PB( $args );
		}

		// create the info side meta-box
		$args = array(
			'metabox_id' 	=> 'wppb-social-connect-info-id',
			'metabox_title' => __( 'Help', 'profile-builder-social-connect-add-on' ),
			'post_type' 	=> 'wppb-social-connect',
			'meta_name' 	=> 'wppb_social_connect_info',
			'meta_array' 	=> '',
			'context'		=> 'option',
			'mb_context'    => 'side'
		);
		if( class_exists( 'Wordpress_Creation_Kit_PB' ) ) {
			new Wordpress_Creation_Kit_PB( $args );
		}
	}


	/**
	 * Add contextual help to the side of Social Connect add-on page
	 *
	 * @since v.1.0.0
	 */
	function wppb_social_connect_add_content_before_info() {
		?>
		<h4>Multiple Register & Edit Profile forms</h4>
		<p>You can manually disable Social Connect buttons for each custom Registration or Edit Profile form.</p>
		<ol>
			<li>Go to your <strong>Registration Forms</strong> or <strong>Edit-profile Forms</strong> submenu page from Profile Builder menu.</li>
			<li>Now go to the desired form.</li>
			<li>In the right Social Connect meta box select <strong>Yes (to enable)</strong> or <strong>No (to disable)</strong> buttons for that form.</li>
		</ol>
	<?php
	}

	/**
	 * Function that adds a Meta Box on each Register and Edit-Profile forms when Multiple Forms are active
	 *
	 * @since v.1.0.0
	 */
	function wppb_sc_add_meta_boxes() {
		$wppb_sc_moduleSettings = get_option( 'wppb_module_settings', 'not_found' );

		if( $wppb_sc_moduleSettings != 'not_found' ) {

			if( $wppb_sc_moduleSettings['wppb_multipleRegistrationForms'] == 'show' ) {
				add_meta_box( 'wppb-sc-rf-side', __( 'Social Connect', 'profile-builder-social-connect-add-on' ), array( $this, 'wppb_sc_meta_boxes_content' ), 'wppb-rf-cpt', 'side', 'low' );
			}

			if( $wppb_sc_moduleSettings['wppb_multipleEditProfileForms'] == 'show' ) {
				add_meta_box( 'wppb-sc-epf-side', __( 'Social Connect', 'profile-builder-social-connect-add-on' ), array( $this, 'wppb_sc_meta_boxes_content' ), 'wppb-epf-cpt', 'side', 'low' );
			}

		}
	}

	/**
	 * Function that adds content to Meta Boxes on each edit Register and Edit-Profile forms
	 *
	 * @since v.1.0.0
	 *
	 * @param object		$post		Contain the post data
	 */
	function wppb_sc_meta_boxes_content( $post ) {
		$wppb_sc_rf_epf_value = get_post_meta( $post->ID, 'wppb_sc_rf_epf_active', true );

		if( empty( $wppb_sc_rf_epf_value ) ) {
			update_post_meta( $post->ID, 'wppb_sc_rf_epf_active', 'yes' );
		}

		?>
		<div class="wrap">
			<p>
				<label for="wppb_sc_rf_epf_active" ><?php _e( 'Display Social Connect buttons:', 'profile-builder-social-connect-add-on' ) ?></label>
			</p>
			<select name="wppb_sc_rf_epf_active" id="wppb_sc_rf_epf_active" class="mb-select">
				<option value="yes" <?php selected( $wppb_sc_rf_epf_value, 'yes' ); ?>><?php _e( 'Yes', 'profile-builder-social-connect-add-on' ) ?></option>
				<option value="no" <?php selected( $wppb_sc_rf_epf_value, 'no' ); ?>><?php _e( 'No', 'profile-builder-social-connect-add-on' ) ?></option>
			</select>
		</div>
	<?php
	}

	/**
	 * Function that saves the Meta Box option
	 *
	 * @since v.1.0.0
	 */
	function wppb_sc_save_meta_boxes_option() {
		global $post;

		if( isset( $_POST['wppb_sc_rf_epf_active'] ) ) {
			$wppb_sc_rf_epf_value = $_POST['wppb_sc_rf_epf_active'];

			update_post_meta( $post->ID, 'wppb_sc_rf_epf_active', $wppb_sc_rf_epf_value );
		}
	}

    /**
     * Function that enqueues the necessary scripts in the admin area
     *
     * @since v.1.0.0
     */
    function wppb_sc_admin_scripts( $hook ){
		if( $hook == 'profile-builder_page_wppb-social-connect' ) {
			wp_enqueue_script( 'wppb-sc-script', plugin_dir_url( __FILE__ ) . 'assets/js/wppb_sc_admin_main.js', array( 'jquery' ) );
		}
    }

    /**
     * Function that enqueues the necessary scripts
     *
     * @since v.1.0.0
     */
    function wppb_sc_scripts(){
		// add thickbox script
		wp_enqueue_script( 'thickbox' );

        wp_enqueue_script( 'wppb-sc-script', plugin_dir_url( __FILE__ ) . 'assets/js/wppb_sc_main.js', array( 'jquery' ) );

		$main_vars_array = array(
			'ajaxUrl'						=>	admin_url( 'admin-ajax.php' ),
			'edit_profile_success_unlink' 	=>	__( "You have successfully unlinked your profile.", 'profile-builder-social-connect-add-on' ),
		);

		wp_localize_script( 'wppb-sc-script', 'wppb_sc_data', $main_vars_array );

		foreach( $this->platforms as $platform ) {
            wp_enqueue_script( 'wppb-sc-' . $platform . '-' . 'script', plugin_dir_url( __FILE__ ) . $platform . '/assets/js/wppb_sc_' . $platform . '.js', array( 'wppb-sc-script', 'jquery' ) );

            $vars_array = array(
                'ajaxUrl' 				        => admin_url( 'admin-ajax.php' ),
                'account_exists_text' 	        => __( "An account with this email address already exists.<br> Do you want to connect it?", 'profile-builder-social-connect-add-on' ),
                'password_text' 		        => __( "Please enter your website account password", 'profile-builder-social-connect-add-on' ),
                'new_email_text' 		        => __( "Please enter a new email", 'profile-builder-social-connect-add-on' ),
                'edit_profile_success_linked' 	=> __( "You have successfully linked your profile.", 'profile-builder-social-connect-add-on' ),
                'error_message' 	            => __( "Something went wrong. Please try again later!", 'profile-builder-social-connect-add-on' ),
				'email_confirmation_on'			=> __( "Before you can access your account you need to confirm your email address. Please check your inbox and click the activation link.", 'profile-builder-social-connect-add-on' ),
            	'email_confirmation_error' 		=> __( "<strong>ERROR</strong>: You need to confirm your email address before you can log in.", 'profile-builder-social-connect-add-on' ),
				'admin_approval_on' 			=> __( "Before you can access your account an administrator has to approve it. You will be notified via email.", 'profile-builder-social-connect-add-on' ),
				'admin_approval_error'			=> __( "<strong>ERROR</strong>: Your account has to be confirmed by an administrator before you can log in.", 'profile-builder-social-connect-add-on' ),
			);

            if( $platform == 'facebook' ) {
                if( ! empty( $this->wppb_social_connect_settings[0]['facebook-app-id'] ) ) {
					$vars_array['appId'] = trim( $this->wppb_social_connect_settings[0]['facebook-app-id'] );
					$vars_array['enter_facebook_email_text'] = __( "Please enter your Facebook email", 'profile-builder-social-connect-add-on' );
					$vars_array['facebook_invalid_email_text'] = __( "Please enter your Facebook email!", 'profile-builder-social-connect-add-on' );
				}
            }

            if( $platform == 'google' ) {
                if( ! empty( $this->wppb_social_connect_settings[0]['google-client-id'] ) ) {
					$vars_array['client_id'] = trim( $this->wppb_social_connect_settings[0]['google-client-id'] );
				}
            }

            if( $platform == 'twitter' ) {
				if( ! empty( $this->wppb_social_connect_settings[0]['twitter-api-key'] ) && ! empty( $this->wppb_social_connect_settings[0]['twitter-api-secret'] ) ) {
					$vars_array['twitter_popup_text'] = __( 'Waiting for Twitter...', 'profile-builder-social-connect-add-on' );
					$vars_array['enter_twitter_email_text'] = __( "Please enter your Twitter email", 'profile-builder-social-connect-add-on' );
					$vars_array['twitter_invalid_email_text'] = __( "Please enter your Twitter email!", 'profile-builder-social-connect-add-on' );
				}
            }

            wp_localize_script( 'wppb-sc-' . $platform . '-' . 'script', 'wppb_sc_'. $platform . '_data', $vars_array );
        }
    }

	/**
	 * Function that enqueues the necessary styles for backend
	 *
	 * @since v.1.0.0
	 */
	function wppb_sc_styles() {
		wp_enqueue_style( 'wppb-sc-style', plugin_dir_url( __FILE__ ) . 'assets/css/wppb_sc_main.css', false );
	}

	/**
	 * Function that enqueues the necessary styles for frontend
	 *
	 * @since v.1.0.0
	 */
	function wppb_sc_frontend_styles() {
		// add thickbox stylesheet
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_style( 'wppb-sc-frontend-style', plugin_dir_url( __FILE__ ) . 'assets/css/wppb_sc_main_frontend.css', false );
	}

    function wppb_sc_save_cookies() {
        $token = $_POST['wppb_sc_security_token'];

		if( is_array( $token ) ) {
			$token = json_encode( $token );
		}

        setcookie( 'wppb_sc_security_token', $token, time() + ( 60 * 60 * 24 ), "/" );

		if( isset( $_POST['wppb_sc_platform_name'] ) && ! empty( $_POST['wppb_sc_platform_name'] ) ) {
			$platform_name = $_POST['wppb_sc_platform_name'];

			setcookie( 'wppb_sc_platform_name', $platform_name, time() + ( 60 * 60 * 24 ), "/" );
		}
    }

    function wppb_sc_delete_security_token() {
        if( ! empty( $_COOKIE['wppb_sc_security_token'] ) ) {
            unset( $_COOKIE['wppb_sc_security_token'] );
        }
    }

	/**
	 * AJAX Function that checks platform id in database
	 */
	function wppb_sc_handle_platform_id_check() {
		global $wpdb;

		if( ! empty( $_POST['platform_id'] ) ) {
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = %s AND meta_value = %s", '_wppb_' . $_POST['platform'] . '_connect_id', $_POST['platform_id'] ) );

			if( empty( $result ) ) {
				wp_die( 'new_account' );
			}
		}
	}

    /**
     * AJAX Function that handles what happens when we click on the social login button. It is a "recursive function" because in some cases it calls itself with new parameters
     */
    function wppb_sc_handle_login_click() {
		global $wpdb;

        /* we need a response array from the platform that contains the user data */
        if( ! empty( $_POST['platform_response'] ) ) {
            /*  we filter the data here to turn the array from the indices that come from the platform to the ones we need in this function
                we need: 'id', 'email', 'first_name', 'last_name'
            */

            /* this should always be set and is the social platform slug */
            $platform = $_POST['platform'];
            $platform_data = apply_filters( 'wppb_sc_process_'. $platform .'_response', $_POST['platform_response'] );

            if( ! empty( $_POST['wppb_sc_security_token'] ) ) {
                $received_token = $_POST['wppb_sc_security_token'];

                if( ! isset( $_COOKIE['wppb_sc_security_token'] ) || wp_unslash( $_COOKIE['wppb_sc_security_token'] ) != urldecode( $received_token ) ) {
					wp_die( 'failed' );
                }

                /* if we are already logged in the link the existing account with the social platform */
                if( is_user_logged_in() ){
                    update_user_meta( get_current_user_id(), '_wppb_' . $platform . '_connect_id', $platform_data['id'] );
                    wp_die( 'linked_successful' );
                }

                /* if we have $_POST['new_email'] set then there already is an account with the email that came from the platform and the user was prompted to enter a new one */
                if( ! empty( $_POST['new_email'] ) ) {
                    $email = $_POST['new_email'];
                } else {
                    if( !empty( $platform_data['email'] ) )
					    $email = $platform_data['email'];
                    else
                        $email = '';
				}

                /* get the general settings from PB */
                $wppb_general_settings = get_option( 'wppb_general_settings' );

                /* $_POST['existing_user_id'] is only set when the user wants to tie an already existing account with the social platform */
                if( empty( $_POST['existing_user_id'] ) ) {
                    $user_query = new WP_User_Query( array( 'meta_key' => '_wppb_' . $platform . '_connect_id', 'meta_value' => $platform_data['id'] ) );

                    if( ! empty( $user_query->results[0]->data->ID ) ) {
                        $user = $user_query->results[0];
                    } else {
                        /* let's see if there is an account with the email */
                        $user = get_user_by( 'email', $email );
                        if( $user != false ) {
                            /* we have an existing account so exit and prompt the user what he wants to do */
                            die( json_encode( array( 'action' => 'wppb_sc_existing_account_prompt', 'wppb_sc_security_token' => $received_token, 'existing_user_id' => $user->data->ID, 'platform_response' => $_POST['platform_response'] ) ) );
                        }
                    }
                } else {
                    /* when the user wants to tie an existing account to a social platform he was prompted to give the password */
                    if( ! empty( $_POST['password'] ) ) {
                        $check_user = get_user_by( 'id', $_POST['existing_user_id'] );
                        if ( $check_user && isset( $check_user->data->user_pass ) && wp_check_password( $_POST['password'], $check_user->data->user_pass, $check_user->ID ) ) {
                            /* set the user object to the existing user */
                            $user = $check_user;
                            /* we have an existing account verified by password so we can link the social account to it */
                            update_user_meta( $user->ID, '_wppb_' . $platform . '_connect_id', $platform_data['id'] );
                        } else {
							die( json_encode( array( 'error' => 'wppb_sc_wrong_password' ) ) );
						}
                    }
                }

                /* if we don't have a $user object by now register a new user here */
                if( empty( $user ) ) {
					/* if user email is unconfirmed display an error message */
					if( $wppb_general_settings && ! empty( $wppb_general_settings['emailConfirmation'] ) && $wppb_general_settings['emailConfirmation'] == 'yes' ) {
						$user_signup = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->base_prefix . "signups WHERE user_email = %s AND active=0", $email ) );

						if( ! empty( $user_signup ) ) {
							wp_die( 'email_confirmation_error' );
						}
					}

					$user = $this->wppb_sc_register_user( $platform, $platform_data, $email, $wppb_general_settings, $_POST['wppb_sc_form_ID'] );

					/* check if Email Confirmation or Admin Approval is on */
					if( $wppb_general_settings ) {
						if( ! empty( $wppb_general_settings['emailConfirmation'] ) && $wppb_general_settings['emailConfirmation'] == 'yes' ) {
							$account_management_settings = 'ec-yes';
						} elseif( ! empty( $wppb_general_settings['adminApproval'] ) && $wppb_general_settings['adminApproval'] == 'yes' ) {
							$account_management_settings = 'aa-yes';
						} else {
							$account_management_settings = 'no';
						}
					} else {
						$account_management_settings = 'no';
					}

					/* display messages for Email Confirmation or Admin Approval */
					switch ( $account_management_settings ) {
						case 'ec-yes':
							wp_die( 'email_confirmation_on' );
							break;
						case 'aa-yes':
							wp_die( 'admin_approval_on' );
							break;
					}
                }

				/* if user is unapproved display an error message */
				if( $wppb_general_settings && ! empty( $wppb_general_settings['adminApproval'] ) && $wppb_general_settings['adminApproval'] == 'yes' ) {
					if( wp_get_object_terms( $user->ID, 'user_status' ) ) {
						wp_die( 'admin_approval_error' );
					}
				}

                /* if we have a valid user object here finally call the login */
                if( ! empty( $user ) ) {
					if( isset( $_POST['wppb_sc_form_ID'] ) ) {
						$this->wppb_sc_login( $user, $wppb_general_settings, $_POST['wppb_sc_form_ID'] );
					} else {
						$this->wppb_sc_login( $user, $wppb_general_settings );
					}
                }
            }
        }
        wp_die();
    }

    /**
     * @param $platform string the social platform slug
     * @param $platform_data array of data returned from the social platform
     * @param $email string email either returned prom the social platform or entered by the user in a prompt
     * @return false|WP_User object
     */
    function wppb_sc_register_user( $platform, $platform_data, $email, $wppb_general_settings, $wppb_sc_form_ID ) {

        /* we need Profile Builder Activated and the Class Profile_Builder_Form_Creator */
        if( defined( 'WPPB_PLUGIN_DIR' ) ) {
			include_once( WPPB_PLUGIN_DIR . '/front-end/class-formbuilder.php' );
		} else {
			wp_die( __( 'Profile Builder not active!', 'profile-builder-social-connect-add-on' ) );
		}

        $user = null;
        $user_id = null;
        $new_user_signup = false;

        /* create account here */
        $args = array(
            'form_type' 	=> 'register',
            'form_fields' 	=> array(),
        );

        $pb_form = new Profile_Builder_Form_Creator( $args );
        $global_request = array();

        $user_login = Wordpress_Creation_Kit_PB::wck_generate_slug( trim( $platform_data[ 'first_name' ].$platform_data[ 'last_name' ] ) );

		if( username_exists( $user_login ) ) {
			$user_login = Wordpress_Creation_Kit_PB::wck_generate_slug( trim( $email ) );
		}

        $userdata = array(
			'user_login' 							=> $user_login,
            'user_pass' 							=> wp_generate_password(),
            'user_email' 							=> $email,
            'first_name' 							=> $platform_data[ 'first_name' ],
            'last_name'  							=> $platform_data[ 'last_name' ],
            'role' 									=> get_option( 'default_role' ),
            '_wppb_' . $platform . '_connect_id' 	=> $platform_data['id']
        );

		if( ! empty( $wppb_sc_form_ID ) ) {
			$wppb_sc_user_role = get_post_meta( $wppb_sc_form_ID, 'wppb_rf_page_settings', true );

			if( $wppb_sc_user_role != false && ! empty( $wppb_sc_user_role ) && $wppb_sc_user_role[0]['set-role'] != 'default role' ) {
				$userdata['role'] = $wppb_sc_user_role[0]['set-role'];
			}
		}

        $result = $pb_form->wppb_register_user( $global_request, $userdata );

        if( ! empty( $result['user_id'] ) ) {
            $user_id = $result['user_id'];
            $user = get_user_by( 'id', $result['user_id'] );
        }

        if( ! empty( $result['userdata'] ) )
            $userdata = $result['userdata'];

        if( ! empty( $result['new_user_signup'] ) )
            $new_user_signup = $result['new_user_signup'];

        if( ! $new_user_signup ) {
            if( ! is_wp_error( $user_id ) ) {

                /* we have a valid user_id so we can link the account to the social platform  */
                update_user_meta( $user_id, '_wppb_' . $platform . '_connect_id', $platform_data['id'] );

                $send_credentials_via_email = 'sending';
                wppb_notify_user_registration_email( get_bloginfo( 'name' ), ( isset( $userdata['user_login'] ) ? trim( $userdata['user_login'] ) : trim( $userdata['user_email'] ) ), trim( $userdata['user_email'] ), $send_credentials_via_email, trim( $userdata['user_pass'] ), ( isset( $wppb_general_settings['adminApproval'] ) ? $wppb_general_settings['adminApproval'] : 'no' ) );
            }
        }

        return $user;
    }

    /**
     * Function that logs in a user
     */
    function wppb_sc_login( $user, $wppb_general_settings, $wppb_sc_form_ID = '' ) {
        /* if we have admin approval check the user status here */
        if( isset( $wppb_general_settings['adminApproval'] ) && $wppb_general_settings['adminApproval'] == 'yes' ) {
            $approved = wppb_unapproved_user_admin_error_message_handler( $user, '' );
            if( is_wp_error( $approved ) )
                die( $approved->get_error_message() );
        }

        wp_set_current_user( $user->data->ID, $user->data->user_login );
        wp_set_auth_cookie( $user->data->ID );
        do_action( 'wp_login', $user->data->user_login, $user );

        $redirect_to = $_SERVER['HTTP_REFERER'];

		// check if default WordPress login or register. If true, update redirect with dashboard url
		if( ! empty( $redirect_to ) ) {
			$redirect_to = ( strpos( esc_url( $redirect_to ), 'wp-login.php' ) ? admin_url() : $_SERVER['HTTP_REFERER'] );
		}

        // check if Custom Redirects is enabled and take custom redirect link from db
        if( PROFILE_BUILDER == 'Profile Builder Pro' ) {
            $wppb_module_settings = get_option( 'wppb_module_settings' );

            if( isset( $wppb_module_settings['wppb_customRedirect'] ) && $wppb_module_settings['wppb_customRedirect'] == 'show' /*&& $_POST['wppb_redirect_priority'] != 'top'*/ && function_exists( 'wppb_custom_redirect_url' ) ) {
                $redirect_url = wppb_custom_redirect_url( 'after_login', $redirect_to, $user->data->user_login );

                if( ! empty( $redirect_url ) ) {
                    $redirect_to = $redirect_url;
                }
            }
        }

		if( ! empty( $wppb_sc_form_ID ) ) {
			$wppb_sc_rf_redirect = get_post_meta( $wppb_sc_form_ID, 'wppb_rf_page_settings', true );

			if( $wppb_sc_rf_redirect != false && ! empty( $wppb_sc_rf_redirect ) && $wppb_sc_rf_redirect[0]['redirect'] == 'Yes' ) {
				$redirect_to = $wppb_sc_rf_redirect[0]['url'];
			}
		}

        $redirect_to = apply_filters( 'wppb_after_login_redirect_url', $redirect_to );

        die( json_encode( array( 'redirect_to' => $redirect_to ) ) );
    }

    /**
     * Function that hooks into when the user is activated from Email Confirmation
     * @param $user_id the user id
     * @param $meta the meta stored in wp_signups
     */
    function wppb_sc_add_social_platform_meta_id_on_user_activation( $user_id, $meta ){
        if( ! empty( $this->platforms ) ) {
            foreach( $this->platforms as $platform ) {
                if( ! empty( $meta['_wppb_'. $platform .'_connect_id'] ) ) {
                    update_user_meta( $user_id, '_wppb_'. $platform .'_connect_id', $meta['_wppb_'. $platform .'_connect_id'] );
                }
            }
        }
    }

	// add headings in Social Connect settings page
	function wppb_sc_settings_page_heading_general_settings() {
		echo '<li><h3>' . __( 'General Settings', 'profile-builder-social-connect-add-on' ) . '</h3></li>';
	}

	function wppb_sc_settings_page_heading_application_settings() {
		echo '<li><h3>' . __( 'Application Settings', 'profile-builder-social-connect-add-on' ) . '</h3></li>';
	}

	function wppb_sc_settings_page_heading_appearance_settings() {
		echo '<li><h3>' . __( 'Appearance Settings', 'profile-builder-social-connect-add-on' ) . '</h3></li>';
	}


    function wppb_sc_login_form_bottom( $form_bottom ){

        $form_bottom .= '<div class="wppb-sc-buttons-container">';

        if( $this->wppb_social_connect_settings[0]['buttons-style'] == 'icon' && ! empty( $this->wppb_social_connect_settings[0]['heading-before-reg-buttons'] ) )
            $form_bottom .= $this->wppb_sc_heading_before_small_buttons();

        foreach( $this->platforms as $platform ) {
            if( function_exists( 'wppb_sc_generate_' . $platform . '_button' ) ) {
                $call_function = 'wppb_sc_generate_' . $platform . '_button';
                $form_bottom .= $call_function( '' );
            }
        }

        $form_bottom .= '</div>';

        return $form_bottom;
    }

    function wppb_sc_display_on_forms( $form_name, $form_ID, $form_type ) {
		$this->forms_type = $form_type;

		$wppb_sc_rf_epf_value = get_post_meta( $form_ID, 'wppb_sc_rf_epf_active', true );

		// TODO: unquote if notice appears cause $wppb_sc_rf_epf_value is not set or something
		/*if( empty( $wppb_sc_rf_epf_value ) ) {
			$wppb_sc_rf_epf_value = 'yes';
		}*/

		if( ( $form_name != 'unspecified' && $wppb_sc_rf_epf_value != 'no' ) || $form_name == 'unspecified' ) {
			$this->wppb_sc_buttons_for_display_on_forms( $form_ID );
		}
    }

	function wppb_sc_buttons_for_display_on_forms( $form_ID ) {
		echo '<div class="wppb-sc-buttons-container">';

		if( $this->wppb_social_connect_settings[0]['buttons-style'] == 'icon' && ! empty( $this->wppb_social_connect_settings[0]['heading-before-reg-buttons'] ) )
			echo $this->wppb_sc_heading_before_small_buttons();

		foreach( $this->platforms as $platform ) {
			if( function_exists( 'wppb_sc_generate_'. $platform .'_button' ) ) {
				$call_function = 'wppb_sc_generate_' . $platform . '_button';
				echo $call_function( $form_ID );
			}
		}

		// display option to unlink social accounts in edit profile page, only if you have at least one account linked
		if( isset( $this->wppb_social_connect_settings[0]['unlink-accounts'] ) && $this->wppb_social_connect_settings[0]['unlink-accounts'] == 'yes' ) {
			$linked_platforms = '';
			foreach( $this->platforms as $platform ) {
				$linked_platform = get_user_meta( get_current_user_id(), '_wppb_' . $platform . '_connect_id' );
				if( isset( $linked_platform[0] ) ) {
					$linked_platforms .= '<span class="wppb_sc_unlink_pre_' . $platform . '">';
					$linked_platforms .= '<strong>' . ucfirst( $platform ) . '</strong>';
					$linked_platforms .= ' (<a class="wppb_sc_unlink_account" id="wppb_sc_unlink_' . $platform . '" href="#">' . __( 'Unlink', 'profile-builder-social-connect-add-on' ) . '</a>)';
					$linked_platforms .= '</span>';
					$linked_platforms .= '<span class="wppb-sc-separator">, </span>';
				}
			}

			if( ! empty( $linked_platforms ) ) {
				$linked_accounts_text = __( 'Your account is linked with:', 'profile-builder-social-connect-add-on' );
				$linked_accounts_text = apply_filters( 'wppb_sc_linked_accounts_text', $linked_accounts_text );

				echo '<div class="wppb-sc-linked-accounts-text">';
				echo $linked_accounts_text . ' ';
				echo rtrim( $linked_platforms, '<span class="wppb-sc-separator">, </span>' );
				echo '</div>';
			}
		}

		echo '</div>';
	}

	function wppb_sc_unlink_account() {
		$platform_unlink_id = $_POST['wppb_sc_unlink_platform_id'];

		switch( $platform_unlink_id ) {
			case 'wppb_sc_unlink_facebook' :
				delete_user_meta( get_current_user_id(), '_wppb_facebook_connect_id' );
				wp_die( 'successful_unlink' );
				break;
			case 'wppb_sc_unlink_google' :
				delete_user_meta( get_current_user_id(), '_wppb_google_connect_id' );
				wp_die( 'successful_unlink' );
				break;
			case 'wppb_sc_unlink_twitter' :
				delete_user_meta( get_current_user_id(), '_wppb_twitter_connect_id' );
				wp_die( 'successful_unlink' );
				break;
		}
	}

	// add heading before Social Connect small buttons in Profile Builder forms
	function wppb_sc_heading_before_small_buttons() {
        global $pagenow;

        if( $pagenow == 'wp-login.php' ) {
			$heading = '<div class="wppb-sc-heading-before-reg-buttons-wp-default"><h2>' . $this->wppb_social_connect_settings[0]['heading-before-reg-buttons'] . '</h2></div>';
		} elseif( isset( $this->forms_type ) && $this->forms_type == 'edit_profile' ) {
			$heading = '<div class="wppb-sc-heading-before-reg-buttons"><h3>' . $this->wppb_social_connect_settings[0]['heading-before-ep-buttons'] . '</h3></div>';
		} else {
			$heading = '<div class="wppb-sc-heading-before-reg-buttons"><h3>' . $this->wppb_social_connect_settings[0]['heading-before-reg-buttons'] . '</h3></div>';
		}

		return $heading;
	}
}


$social_connect_instance = new WPPB_Social_Connect();


/**
 * Initialize the translation for the Plugin.
 *
 * @since v.1.0
 *
 * @return null
 */
function wppb_sc_init_translation() {
	$current_theme = wp_get_theme();
	if( ! empty( $current_theme->stylesheet ) && file_exists( get_theme_root() . '/' . $current_theme->stylesheet . '/local_pb_lang' ) )
		load_plugin_textdomain( 'profile-builder-social-connect-add-on', false, basename( dirname( __FILE__ ) ) . '/../../themes/' . $current_theme->stylesheet . '/local_pb_lang' );
	else
		load_plugin_textdomain( 'profile-builder-social-connect-add-on', false, basename( dirname( __FILE__ ) ) . '/translation/' );
}
add_action( 'init', 'wppb_sc_init_translation', 8 );


/*
* Check for updates
*
*/
if( file_exists( plugin_dir_path( __FILE__ ) . 'update/update-checker.php' ) ) {
    include_once( plugin_dir_path( __FILE__ ) . 'update/update-checker.php' );
    $wppb_woo_update = new wppb_PluginUpdateChecker('http://updatemetadata.cozmoslabs.com/?uniqueproduct=CLPBSC', __FILE__, 'wppb-social-connect-add-on' );
}
