<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class GF_Field_Post_Content extends GF_Field {

	public $type = 'post_content';

	public function get_form_editor_field_title() {
		return esc_attr__( 'Body', 'gravityforms' );
	}

	function get_form_editor_field_settings() {
		return array(
			'post_content_template_setting',
			'post_status_setting',
			'post_category_setting',
			'post_author_setting',
			'post_format_setting',
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'size_setting',
			'maxlen_setting',
			'rules_setting',
			'visibility_setting',
			'default_value_textarea_setting',
			'placeholder_textarea_setting',
			'description_setting',
			'css_class_setting',
			'rich_text_editor_setting',
		);
	}

	public function is_conditional_logic_supported() {
		return true;
	}

	public function enqueue_rich_text_editor_scripts(){
		//have to print scripts/styles to footer for the editor to work on the preview page
		wp_print_footer_scripts();
	}

	public function get_field_input( $form, $value = '', $entry = null ) {

		$field = new GF_Field_Textarea( clone $this );

		return $field->get_field_input( $form, $value, $entry );
	}

	public function allow_html() {
		return true;
	}

	/**
	 * Format the entry value for when the field/input merge tag is processed. Not called for the {all_fields} merge tag.
	 * Return a value that is safe for the context specified by $format.
	 *
	 * @param string|array $value The field value. Depending on the location the merge tag is being used the following functions may have already been applied to the value: esc_html, nl2br, and urlencode.
	 * @param string $input_id The field or input ID from the merge tag currently being processed.
	 * @param array $entry The Entry Object currently being processed.
	 * @param array $form The Form Object currently being processed.
	 * @param string $modifier The merge tag modifier. e.g. value
	 * @param string|array $raw_value The raw field value from before any formatting was applied to $value.
	 * @param bool $url_encode Indicates if the urlencode function may have been applied to the $value.
	 * @param bool $esc_html Indicates if the esc_html function may have been applied to the $value.
	 * @param string $format The format requested for the location the merge is being used. Possible values: html, text or url.
	 * @param bool $nl2br Indicates if the nl2br function may have been applied to the $value.
	 *
	 * @return string
	 */
	public function get_value_merge_tag( $value, $input_id, $entry, $form, $modifier, $raw_value, $url_encode, $esc_html, $format, $nl2br ) {

		if ( $format === 'html' ) {
			$form_id        = absint( $form['id'] );
			$allowable_tags = $this->get_allowable_tags( $form_id );

			if ( $allowable_tags === false ) {
				// The value is unsafe so encode the value.
				$return = esc_html( $value );
			} else {
				// The value contains HTML but the value was sanitized before saving.
				$return = $value;
			}

			// If $nl2br is true nl2br() may have already been run in GFCommon::format_variable_value().
			if ( ! $nl2br ) {
				// Run nl2br() to preserve line breaks when auto-formatting is disabled on notifications/confirmations.
				$return = nl2br( $return );
			}
		} else {
			$return = $value;
		}

		return $return;
	}
}

GF_Fields::register( new GF_Field_Post_Content() );