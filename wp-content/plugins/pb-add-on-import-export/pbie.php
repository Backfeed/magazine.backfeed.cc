<?php

/*
    Plugin Name: Profile Builder - Import and Export Add-On
    Plugin URI: http://www.cozmoslabs.com/
	Description: This plugin adds a PB subpage where you can Import and Export settings of Profile Builder.
	Author: Cozmoslabs, Cristophor Hurduban
	Version: 1.1
	Author URI: http://www.cozmoslabs.com
	License: GPL2


    == Copyright ==
    Copyright 2014 Cozmoslabs (www.cozmoslabs.com)

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

/* define plugin directory */
define( 'PBIE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) );

/* include content of Import and Export tabs */
require_once 'pbie-import.php';
require_once 'pbie-export.php';

/* add submenu page */
add_action( 'admin_menu', 'pbie_register_submenu_page', 18 );

function pbie_register_submenu_page() {
	add_submenu_page( 'profile-builder', __( 'Import and Export', 'pbie' ), __( 'Import and Export', 'pbie' ), 'manage_options', 'pbie-import-export', 'pbie_submenu_page_callback' );
}

function pbie_submenu_page_callback() {
	pbie_page();
}

/**
 * adds Import and Export tab
 *
 * @param string  $current  tab to display. default 'import'.
 */
function pbie_tabs( $current = 'import' ) {
	$tabs = array(
		'import' => __( 'Import', 'pbie' ),
		'export' => __( 'Export', 'pbie' )
	);

	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=pbie-import-export&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}

/* PB Import and Export subpage content function */
function pbie_page() {
	global $pagenow;

	?>
	<div class="wrap">
		<?php
		echo '<h2>';
		_e( 'Import and Export', 'pbie' );
		echo '</h2>';

		if( isset ( $_GET['tab'] ) ) pbie_tabs( $_GET['tab'] );
		else pbie_tabs( 'import' );
		?>

		<form method="post" action="<?php admin_url( 'admin.php?page=pbie-import-export' ); ?>" enctype= "multipart/form-data">
			<?php
			if( $pagenow == 'admin.php' && $_GET['page'] == 'pbie-import-export' ) {
				if( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
				else $tab = 'import';

				switch ( $tab ) {
					case 'export' :
						pbie_export();
						break;
					case 'import' :
						pbie_import();
						break;
				}
			}
			?>
		</form>
	</div>
<?php
}