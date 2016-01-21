<?php
/**
 * Module L (Custom Html)
 */

if ( isset( $barcelona_mod_header ) ) {
	echo $barcelona_mod_header;
}

?>
<div class="section-html post-content">
	<?php
	if ( isset( $barcelona_content ) ) {
		echo do_shortcode( $barcelona_content );
	}
	?>
</div>