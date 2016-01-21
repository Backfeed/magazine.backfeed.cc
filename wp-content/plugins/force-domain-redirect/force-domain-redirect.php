<?php
/**
 * Plugin Name: Force Domain Redirect
 * Plugin URI: http://nimbus.agency
 * Description: Forces the use of the main WP domain
 * Version: 0.2
 * Author: Mark Williams
 * Author URI: http://nimbus.agency
 * License: GPL2
 */
	 /*
	 Copyright 2014  Mark Williams  (email : wordpress.org@tracemail.co.uk)

	 This program is free software; you can redistribute it and/or modify
	 it under the terms of the GNU General Public License, version 2, as 
	 published by the Free Software Foundation.

	 This program is distributed in the hope that it will be useful,
	 but WITHOUT ANY WARRANTY; without even the implied warranty of
	 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 GNU General Public License for more details.

	 You should have received a copy of the GNU General Public License
	 along with this program; if not, write to the Free Software
	 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	 */
	 

	 function redirect_URL(){
		$uri=$_SERVER['REQUEST_URI'];
		$domain=$_SERVER['HTTP_HOST'];
		$wpDomain=get_site_url();
		if($wpDomain=="http://".$domain || $wpDomain=="https://".$domain){
			//echo "You are at the correct domain";
		} else {
			Header( "HTTP/1.1 301 Moved Permanently" );
			Header( "Location: ".$wpDomain.$uri );
			echo $domain." isn't the right domain: ".$wpDomain;
			die();
			
		}
	 }
add_action('init', 'redirect_URL');
?>