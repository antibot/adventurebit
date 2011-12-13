<?php

/*  Greg's Uninstaller
	
	Copyright (c) 2009-2011 Greg Mulhauser
	http://gregsplugins.com
	
	Released under the GPL license
	http://www.opensource.org/licenses/gpl-license.php
	
	**********************************************************************
	This program is distributed in the hope that it will be useful, but
	WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	*****************************************************************
*/

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

grm_delete_and_go();

function grm_delete_and_go() {
	// first figure out our prefix
	$path = WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	$files = glob($path . '*-setup-functions.php');
	$plugin_prefix = str_replace($path, '', $files[0]);
	$plugin_prefix = str_replace('-setup-functions.php', '', $plugin_prefix);
	if ('' == $plugin_prefix) return; // something went wrong getting prefix, so don't do anything
	// now carry on with uninstall
	$options_set = array();
	if (is_array(get_option($plugin_prefix . '_private'))) $options_set = array(array('private'));
	if (is_array(get_option($plugin_prefix . '_settings'))) $options_set[] = array('settings');
	else { // if no _settings array, then we have discrete options to collect
		if (!class_exists($plugin_prefix . 'SetupHandler')) include ($plugin_prefix . '-setup-functions.php');
		// now we use a workaround enabling a static call to a method in a class whose name is in a variable
		$discrete_options = call_user_func(array($plugin_prefix . 'SetupHandler', 'grab_settings'), 'flat');
		$options_set = array_merge($options_set, $discrete_options);
	}
	if (!empty($options_set) && current_user_can('delete_plugins')) {
		foreach ($options_set as $option) {
			delete_option($plugin_prefix . '_' . $option[0]);
		} // end loop over options
	}
	return;
} // end of deletion function

?>