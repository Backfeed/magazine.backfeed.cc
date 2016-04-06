<?php

/* include import class */
require_once 'inc/class-pbie-import.php';

/* Import tab content function */
function pbie_import() {
	if( isset( $_POST['cozmos-import'] ) ) {
		if( $_FILES['cozmos-upload'] ) {
			$pbie_cpts = array(
				'wppb-ul-cpt',
				'wppb-rf-cpt',
				'wppb-epf-cpt'
			);

			$pbie_json_upload = new Cozmoslabs_Plugin_Import( $pbie_cpts );
			$pbie_json_upload->upload_json_file();
			/* show error/success messages */
			$pbie_messages = $pbie_json_upload->get_messages();
			foreach ( $pbie_messages as $pbie_message ) {
				echo '<div id="message" class=';
				echo $pbie_message['type'];
				echo '>';
				echo '<p>';
				echo $pbie_message['message'];
				echo '</p>';
				echo '</div>';
			}
		}
	}
	?>
	<p><?php _e( 'Import Profile Builder options from a .json file. This allows you to easily import the configuration from another site. ', 'pbie' ); ?></p>
	<form name="cozmos-upload" method="post" action="" enctype= "multipart/form-data">
		<div class="wrap">
			<input type="file" name="cozmos-upload" value="cozmos-upload" id="cozmos-upload" />
		</div>
		<div class="wrap">
			<input class="button-secondary" type="submit" name="cozmos-import" value=<?php _e( 'Import', 'pbie' ); ?> id="cozmos-import" onclick="return confirm( '<?php _e( 'This will overwrite your old PB settings! Are you sure you want to continue?', 'pbie' ); ?>' )" />
		</div>
	</form>
<?php
}