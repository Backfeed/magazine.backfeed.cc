<?php

class Cozmoslabs_Plugin_Export {

	protected $args_to_export;

	/**
	 * this will take custom options and posttypes that will be exported from database.
	 *
	 * @param array  $args_to_export  custom options and posttypes to export.
	 */
	function __construct( $args_to_export ) {
		$this->args_to_export = $args_to_export;
	}

	/* function to export from database */
	public function export_array() {
		/* export options from database */
		$option_values = array();
		foreach( $this->args_to_export['options'] as $option ) {
			$option_values[$option] = get_option( $option );
		}

		/* export custom posts from database */
		$all_custom_posts = array();
		foreach( $this->args_to_export['cpts'] as $post_type ) {
			$all_custom_posts[$post_type] = get_posts( "post_type=$post_type&posts_per_page=-1" );
			foreach( $all_custom_posts[$post_type] as $key => $value ) {
				$all_custom_posts[$post_type][$key]->postmeta = get_post_custom( $value->ID );
			}
		}

		/* create and return array for export */
		$all_for_export = array(
			"options" => $option_values,
			"posts" => $all_custom_posts
		);

		return $all_for_export;
	}

	/* export to json file */
	public function download_to_json_format( $prefix ) {
		$all_for_export = $this->export_array();
		if( isset( $_POST['cozmos-export'] ) ) {
			$json = json_encode( $all_for_export );
			$filename = $prefix . date( 'Y-m-d_h.i.s', time() );
			$filename .= '.json';
			header( "Content-Disposition: attachment; filename=$filename" );
			header( 'Content-type: application/json' );
			header( 'Content-Length: ' . mb_strlen( $json ) );
			header( 'Connection: close' );
			echo $json;
			exit;
		}
	}
}
