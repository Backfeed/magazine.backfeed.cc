<?php

class Cozmoslabs_Plugin_Import {

	protected $args_to_import;
	public $import_messages = array();
	private $j = '0';

	/**
	 * this will take custom options and posttypes that will be imported to database.
	 *
	 * @param array  $args_to_import  custom options and posttypes to import.
	 */
	function __construct( $args_to_import ) {
		$this->args_to_import = $args_to_import;
	}

	/**
	 * this will save imported json.
	 *
	 * @param string  $json_content  imported json.
	 */
	public function json_to_db( $json_content ) {
		/* decode and put json to array */
		$imported_array_from_json = json_decode( $json_content, true );
		if ( $imported_array_from_json !== NULL ) {
			$imported_options = $imported_array_from_json['options'];
			$imported_posts = $imported_array_from_json['posts'];

			/* import options to database */
			foreach( $imported_options as $key => $value ) {
				if( ! empty( $value ) ) {
					update_option( $key, $value );
				}
			}

			/* import custom posts to database */
			foreach( $this->args_to_import as $imported_post_type ) {
				$db_posts = get_posts( "post_type=$imported_post_type&posts_per_page=-1" );
				if( ! empty( $imported_posts[$imported_post_type] ) ) {
					foreach( $imported_posts[$imported_post_type] as $imported_post ) {
						foreach( $db_posts as $db_post ) {
							if( $imported_post["post_title"] == $db_post->post_title && $imported_post["post_name"] == $db_post->post_name ) {
								wp_delete_post( $db_post->ID, $force_delete = true );
							}
						}
						unset( $imported_post["ID"] );
						$imported_post_id = wp_insert_post( $imported_post );
						foreach( $imported_post["postmeta"] as $key => $value ) {
							foreach( $value as $value_key => $serialized_value ) {
								add_post_meta( $imported_post_id, $key, maybe_unserialize( $serialized_value ) );
							}
						}
					}
				}
			}
		} else {
			$this->import_messages[$this->j]['message'] = __( 'Uploaded file is not valid json!', 'pbie' );
			$this->import_messages[$this->j]['type'] = 'error';
			$this->j++;
		}
	}

	/* upload json file function */
	public function upload_json_file() {
		if( isset( $_POST['cozmos-import'] ) ) {
			if( ! empty( $_FILES['cozmos-upload']['tmp_name'] ) ) {
				$json_content = file_get_contents( $_FILES['cozmos-upload']['tmp_name'] );
				/* save uploaded file to server (for later versions).
				$target = dirname( plugin_dir_path( __FILE__ ) ) . '/upload/';
				$target = $target . basename( $_FILES['cozmos-upload']['name'] );
				move_uploaded_file( $_FILES['cozmos-upload']['tmp_name'], $target );
				*/
				$this->json_to_db( $json_content );
				if( empty( $this->pbie_import_messages ) ) {
					$this->import_messages[$this->j]['message'] = __( 'Import successfully!', 'pbie' );
					$this->import_messages[$this->j]['type'] = 'updated';
					$this->j++;
					flush_rewrite_rules( false );
				}
			} else {
				$this->import_messages[$this->j]['message'] = __( 'Please select a .json file to import!', 'pbie' );
				$this->import_messages[$this->j]['type'] = 'error';
				$this->j++;
			}
		}
	}

	/* messages return function */
	public function get_messages() {
		return $this->import_messages;
	}
}