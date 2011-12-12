<?php
/*
Plugin Name: Basic Google Maps Placemarks
Plugin URI: http://wordpress.org/extend/plugins/basic-google-maps-placemarks/
Description: Embeds a Google Map into your site and lets you add map markers with custom icons and information windows. Each marker can have a different icon.
Version: 1.6
Author: Ian Dunn
Author URI: http://iandunn.name
License: GPL2
*/

/*  
 * Copyright 2011 Ian Dunn (email : ian@iandunn.name)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die("Access denied.");

define( 'BGMP_NAME', 'Basic Google Maps Placemarks' );
define( 'BGMP_REQUIRED_PHP_VERSON', '5.2' );
define( 'BGMP_REQUIRED_WP_VERSION', '3.1' );

/**
 * Checks if the system requirements are met
 * @author Ian Dunn <ian@iandunn.name>
 * @return bool True if system requirements are met, false if not
 */
function bgmp_requirementsMet()
{
	global $wp_version;
	
	if( version_compare( PHP_VERSION, BGMP_REQUIRED_PHP_VERSON, '<') )
		return false;
	
	if( version_compare( $wp_version, BGMP_REQUIRED_WP_VERSION, "<") )
		return false;
	
	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * @author Ian Dunn <ian@iandunn.name>
 */
function bgmp_requirementsNotMet()
{
	global $wp_version;
	
	echo sprintf('
		<div id="message" class="error">
			<p>
				%s <strong>requires PHP %s</strong> and <strong>WordPress %s</strong> in order to work. You\'re running PHP %s and WordPress %s. You\'ll need to upgrade in order to use this plugin. If you\'re not sure how to <a href="http://codex.wordpress.org/Switching_to_PHP5">upgrade to PHP 5</a> you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to <a href="http://codex.wordpress.org/Upgrading_WordPress">the Codex</a>.
			</p>
		</div>',
		BGMP_NAME,
		BGMP_REQUIRED_PHP_VERSON,
		BGMP_REQUIRED_WP_VERSION,
		PHP_VERSION,
		esc_html( $wp_version )
	);
}

// Check requirements and instantiate
if( bgmp_requirementsMet() )
{
	require_once( dirname(__FILE__) . '/core.php' );
	
	if( class_exists('BasicGoogleMapsPlacemarks') )
		$bgmp = new BasicGoogleMapsPlacemarks();
}
else
	add_action( 'admin_notices', 'bgmp_requirementsNotMet' );

?>