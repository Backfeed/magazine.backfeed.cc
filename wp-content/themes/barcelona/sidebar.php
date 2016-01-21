<?php

if ( barcelona_get_option( 'sidebar_position' ) == 'none' ) {
	return;
}

$barcelona_sidebar = barcelona_get_option( 'default_sidebar' );

if( function_exists( 'buddypress' ) && is_buddypress() ) {
	$barcelona_sidebar = 'barcelona-buddypress-sidebar';
} else if ( function_exists( 'bbpress' ) && is_bbpress() ) {
	$barcelona_sidebar = 'barcelona-bbpress-sidebar';
}

?>
<aside id="sidebar" class="<?php echo esc_attr( barcelona_sidebar_class() ); ?>">

	<div class="sidebar-inner">

		<?php dynamic_sidebar( $barcelona_sidebar ); ?>

	</div><!-- .sidebar-inner -->

</aside>