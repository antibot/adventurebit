<?php

/*  Greg's Options Handler
	
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

if (!function_exists ('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
	}

class gtcnOptionsHandler {

	var $replacements = array(); // set of replacements to perform during parsing
	var $pages = array();        // set of pages we're expecting, as an array with page name, page title, page menu entry
	var $notices = array();      // any notices to display at top of page
	var $problems = array();     // any plugin conflicts we'd like to check for
	var $domain;                 // our text translation domain
	var $plugin_prefix;          // prefix for each option name
	var $settings_prefix;        // prefix for each distinct set of options registered, used by WP's settings_fields
	var $oursettings;            // holds full set of parsed settings
	var $ourextra;               // holds extra material to be displayed at top of settings page
	var $page_title;             // indicates main title for page, derived from array passed in
	var $instructions;           // indicates whether we're handling an instructions page (i.e., no submit button)
	var $path;                   // where are we?
	var $submenu;                // plain name of submenu we're displaying
	var $thispage;               // name of this page, from keys in var $pages
	var $boxed_set = array();    // boxed set of sections to deliver
	var $box_hook;               // keeping track of our boxes and box states
	var $consolidate;            // whether to consolidate options into single array
	
	function gtcnOptionsHandler($args) {
		$this->__construct($args);
		return;
	} 
	
	function __construct($args) {
		extract($args);
		$this->replacements = (array)$replacements;
		$this->domain = $plugin_prefix . '-plugin';
		$this->plugin_prefix = $plugin_prefix . '_';
		$this->settings_prefix = $plugin_prefix . '_options_';
		if (!empty($option_style)) $this->consolidate = ('consolidate' == $option_style) ? true : false;
		else $this->consolidate = true;
		$this->pages = (array)$pages;
		$this->notices = (array)$notices;
		$this->problems = (array)$problems;
		$this->box_hook = $plugin_prefix . 'optionboxes_';
		$dir = str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); // get plugin folder name
		$base = str_replace("-functions.php","",basename( __FILE__)); // get this file's name without extension, assuming it ends with '-functions.php'
		$this->path = $dir . $base;
		if (!isset($subdir)) $subdir = 'options-set';
		$subdir .= ($subdir != '') ? '/' : '';
		$root = WP_PLUGIN_DIR . '/' . $dir . $subdir; // this is where we're looking for our options files
		$sub = isset ($_GET['submenu']) ? $_GET['submenu'] : '';
		$filetail = ($sub != '') ? "-$sub" : ''; // options file corresponding to this submenu
		$this->submenu = $sub;
		$this->box_hook .= $sub; // need to keep track of box states for each separate sub-page
		$this->instructions = ($sub == $instname) ? true : false; // we'll do less work for the instructions page
		$extraload = $root . '/extra/' . $base . $filetail . '.txt'; // set up for grabbing extra options page content
		$this->ourextra = (file_exists($extraload)) ? file_get_contents($extraload) : '';
		$mainload = $root . $base . $filetail . '.ini'; // set up for grabbing main options page content
		$this->oursettings = $this->prep_settings($mainload,$instname);
		if (!($this->instructions)) $this->oursettings = array_map(array($this,'do_option_replacements'),$this->oursettings);
		return;
	} // end constructor
	
	function prep_settings($toload = '',$instname='instructions') { // grab and parse a settings page into an array
		$ourpages = $this->pages;
		$sub = $this->submenu;
		if (file_exists($toload)) {
			$this->thispage = ($sub != '') ? $sub : 'default';
			if (array_key_exists($sub,$ourpages))
				$this->page_title = wptexturize(__($ourpages[$sub][0],$this->domain));
				else ($sub == '') ? $this->page_title = wptexturize(__($ourpages['default'][0],$this->domain)) : $this->page_title = '';
			if ($this->instructions) $settings = file_get_contents($toload);
			elseif (PHP_VERSION >= 5)
				// If you want to use the PHP5 function when available, uncommment the following line and comment out the line after
		//		$settings = parse_ini_file($toload);
				$settings = $this->parse_ini_file_php4($toload);
			else $settings = $this->parse_ini_file_php4($toload);
		} // end action if corresponding ini file exists
		else $settings = array();
		return $settings;
	} // end prepping settings
	
	function parse_ini_file_php4 ($file) {
		// quick and clean replacement because PHP 4.4.7 fails to load arrays properly
		$file_handle = fopen($file, "rb");
		while (!feof($file_handle) ) {
				$line_of_text = trim(fgets($file_handle),"\r\n ");
				if (strstr($line_of_text,';')) {
				$temp = explode(';',$line_of_text);
				$line_of_text = $temp[0];
			} // end handling comments
			$firstchar = substr($line_of_text,0,1);
			if (!(($line_of_text == '') || ($firstchar == '['))) { // ignore sections and blanks
				$parts = explode('=', $line_of_text);
				$parts[0] = trim($parts[0],'[] ');
				$parts[1] = trim($parts[1],' "');
				$output[$parts[0]][]=$parts[1];
			} // end handling only non-sections
		}
		fclose($file_handle);
		return $output;
	}
	
	function adjust_setting_name($setting='') { // we like a prefix or an array name on our settings
		if (!$this->consolidate) return $this->plugin_prefix . $setting;
		else return $this->plugin_prefix . "settings[$setting]";
	} // end adjusting setting name
	
	function get_setting_value($setting='') {
		// handle atomic setting retrieval
		if (!$this->consolidate) return get_option($this->plugin_prefix . $setting);
		// handle consolidated setting retrieval
		$settings = get_option($this->plugin_prefix . 'settings');
		if (isset($settings[$setting]))
			return $settings[$setting];
		else return null;
	}
	
	function do_option_replacements($content='') { // we may have some values to swap out
		$content = str_replace(array_keys($this->replacements),array_values($this->replacements),$content);
		return $content;
	}
	
	function do_save_button($buttontext='Save Changes') { // make our save button
		$button = __($buttontext, $this->domain);
		if ($this->instructions) $save = '';
		else $save = "
			<table class='form-table'>
			<tr valign='top'>
			<th scope='row'></th>
			<td><p class='submit'>
			<input type='submit' name='Submit' class='button-primary' value='{$button}' />
			</p>
			</td>
			</tr>
			</table>
			";
		return $save;
	} // end creating save button
	
	function do_pagemenu() { // make a simple list menu of all our options pages
		$output = '';
		$ourpages = $this->pages;
		if (count($ourpages) > 1) {
			$output = "<div class='" . $this->plugin_prefix . "menu'>\n<ul>\n";
			foreach ($ourpages as $page=>$details) {
				$menutitle = wptexturize(__($details[1],$this->domain));
				$menutitle = str_replace(' ','&nbsp;',$menutitle);
				if ( $this->thispage == $page )
					$output .= "<li><strong>{$menutitle}</strong> | </li>";
				else { // do a link
					$submenu = ($page == 'default') ? "" : "&amp;submenu={$page}";
					$output .= "<li><a href=\"options-general.php?page={$this->path}.php{$submenu}\">{$menutitle}</a> | </li>";
				} // end doing an actual link
			} // end loop over pages
			$output = substr($output,0,strlen($output)-8) . '</li>'; // snip off the last ' | ' inside the <li>
			$output .= "</ul>\n</div>\n";
		} // end check for array with just one page
		return $output;
	} // end creating page menu
	
	function conflict_check($problemapps=array(),$name='') { // are other plugins running which could conflict with this one? if so, construct a message to that effect
		$domain = $this->domain;
		$conflict = '';
		foreach ($problemapps as $problemapp) {
			$test = (array_key_exists('class',$problemapp)) ? 'class' : 'function';
			$testfx = $test . '_exists';
			if ($testfx($problemapp[$test])) {
				$conflict = $problemapp['name'];
				$warning = $problemapp['warning'];
				if (array_key_exists('remedy',$problemapp)) $remedy = $problemapp['remedy'];
				else $remedy = '';
			} // end testing for problem apps
		} // end loop over problem apps
		if ('' == $conflict) $message = array();
		else {
			$warningprefix = __('Warning: Possible conflict with', $domain);
			$warningend = ($remedy != '') ? $remedy : __('For best results, please disable the interfering plugin',$domain);
			$message = "
				<p><strong><em>{$warningprefix} '{$conflict}'</em></strong></p>
				<p>{$warning} <em>{$name}</em>.</p>
				<p>{$warningend} '{$conflict}'</strong>.</p>
				";
			$message = array('warning',wptexturize($message));
		} // end generating conflict message
		return $message;
	} // end conflict check
	
	// put together a whole page of options from body, title, menu, save button, etc.
	function display_options($name='') {
		// check whether to do full descriptions: unitialized value will yield true, show full options
		$dofull = ($this->get_setting_value('abbreviate_options')) ? false : true;
		// check for donation, so we can display a thank you if so
		$donated = $this->get_setting_value('donated');
		$body = $this->do_options($dofull,false);
		$save = $this->do_save_button();
		$menu = $this->do_pagemenu();
		$thispage = $this->thispage;
		$domain = $this->domain;
		$plugin_prefix = $this->plugin_prefix;
		// if consolidated options, let our sanitisation function know what page we're currently handling
		$current_page = ($this->consolidate) ? "<input type='hidden' name='{$plugin_prefix}settings[current_page]' value='{$thispage}' />" : '';
		$thankspre = __("Thank you for recognizing the value of this plugin with a direct financial contribution or with a link to:",$domain);
		$thankspost = __("I really appreciate your support!",$domain);
		$donation = ($donated) ? wptexturize("<div class='{$plugin_prefix}thanks'><p>{$thankspre} {$name}. {$thankspost}</p></div>") : $this->ourextra;
		$notices = (array)$this->notices;
		$notices[] = $this->conflict_check($this->problems,$name);
		$topper = '';
		if (!empty($notices)) {
			foreach ($notices as $notice) {
				if (!empty($notice)) {
					if ('error' == $notice[0]) $class = "error fade";
					elseif ('warning' == $notice[0]) $class = "{$plugin_prefix}warning";
					else $class = "{$plugin_prefix}info";
					$topper .= "<div class='{$class}'>{$notice[1]}</div>";
				}
			}
		}
		$displaytop = "
			<div class='wrap'>
			<form method='post' action='options.php'>
			{$current_page}
			";
		$displaybot = "
			<h2>{$this->page_title}</h2>
			{$menu}
			{$donation}
			{$topper}
			{$body}
			";
		$displayfoot = "
			{$save}
			</form>
			</div>
			";
		echo $displaytop;
		$settings_id = ($this->consolidate)? 'settings' : $thispage;
		if (!$this->instructions) settings_fields($this->settings_prefix . $settings_id);
		screen_icon();
		echo $displaybot;
		if (!$this->instructions) {
			// NOTE: if we've disabled boxed output at end of do_options, then everything will already be in $body anyway, and no boxes prepared
			echo '<div id="poststuff" class="metabox-holder">';
			$this->do_meta_boxes_simple($this->box_hook, 'normal', $this->boxed_set);
			echo '</div>';
		}
		echo $displayfoot;
		return;
	} // end displaying options
	
	function do_options($full=true,$echo=true) { // meat & potatoes: further process the array which we got by parsing the ini file
		$settings = $this->oursettings;
		$domain = $this->domain;
		if (!is_array($settings)) return wptexturize(__($settings,$domain));
		$output = '';
		$elements = count($settings['setting']);
		$stepper = '0';
		
		while ($stepper < $elements) {
		
			$header = wptexturize(__($settings['header'][$stepper], $domain));
			$preface = wptexturize(__($settings['preface'][$stepper], $domain));

			$properties = explode(',', $settings['type'][$stepper]);
			
			if ($header != '')
				$output .= "<!--secstart--><h3>{$header}</h3>\n";
			if (($preface != '') && $full)
				$output .= "<p>$preface</p>\n";
			else if (($preface != '') && ($properties[0] == 'extra_desc')) // allow description to go through untouched for 'extra_desc' type
				$output .= $preface;
			if (($header != '') || ($preface != ''))
				$output .= '<table class="form-table ' . $this->plugin_prefix . 'table">';
			
			$output .=  '<tr valign="top"><th scope="row">' . $settings['label'][$stepper] . "</th>\n<td>\n";
						
			// get current setting value and adjusted setting name
			$setting_value = $this->get_setting_value($settings['setting'][$stepper]);
			$setting_name = $this->adjust_setting_name($settings['setting'][$stepper]);
			
			if ($properties[0] == 'text') {
				// we use wp_specialchars_decode first in case this field has htmlspecialchars set as its callback filter with register_settings
				// have to use wp_specialchars_decode TWICE because WP is double-specialcharring it
				$echosetting = htmlspecialchars(stripslashes(wp_specialchars_decode(wp_specialchars_decode($setting_value, ENT_QUOTES), ENT_QUOTES)));
				$echodescription = wptexturize(__($settings['description'][$stepper], $domain));
				$output .= "<input type='text' size='{$properties[1]}' name='{$setting_name}' value=\"{$echosetting}\" />\n<br />{$echodescription}";
			} // end handling text
			
			elseif ($properties[0] == 'textarea') {
				// we use wp_specialchars_decode first in case this field has htmlspecialchars set as its callback filter with register_settings
				// have to use wp_specialchars_decode TWICE because WP is double-specialcharring it
				$echotext = htmlspecialchars(stripslashes(wp_specialchars_decode(wp_specialchars_decode($setting_value, ENT_QUOTES), ENT_QUOTES)));
				$output .= "\n<textarea cols='{$properties[1]}' rows='{$properties[2]}' name='{$setting_name}'>{$echotext}</textarea>\n";
				$description = wptexturize(__($settings['description'][$stepper], $domain));
				if ($description != '')
					$output .=  "<br />$description";
			} // end handling textarea
			
			elseif (($properties[0] == 'checkbox') || ($properties[0] == 'radio')) {
				$nowcounter = 0;
				$nowsettings = explode(',',$settings['setting'][$stepper]);
				$nowvalues = explode(',',$settings['value'][$stepper]);
				$nowdescriptions = explode('|',$settings['description'][$stepper]);
				$output .= "<ul>\n";
				while ($nowcounter < $properties[1]) {
					($properties[0] == 'checkbox') ?
						$testcheck = $nowcounter : $testcheck = 0; // if radio button, only look at setting 0 in following test, because there is only one, otherwise step through the settings
					
					// need fresh values when stepping through multiple
					$setting_value = $this->get_setting_value($nowsettings[$testcheck]);
					
					($setting_value == $nowvalues[$nowcounter]) ?
						$checked = ' checked="checked"' : $checked = '';
					$echodescription = wptexturize(__($nowdescriptions[$nowcounter],$domain));
				
					if ($properties[0] == 'checkbox') {
						// need fresh names when stepping through multiple checkboxes, which are separate settings
						$setting_name = $this->adjust_setting_name($nowsettings[$nowcounter]);
						$output .= "<li><label for='{$setting_name}'><input name='{$setting_name}' type='checkbox' id='{$setting_name}' value=\"{$nowvalues[$nowcounter]}\"{$checked} />&nbsp;{$echodescription}</label></li>\n";
					}
					else {
						$output .= "<li><input type='radio' name='{$setting_name}' value=\"{$nowvalues[$nowcounter]}\"{$checked} />&nbsp;{$echodescription}</li>\n";
					}
					$nowcounter ++;
				} // end loop over number of boxes or buttons
				
				$output .= "</ul>\n";
			} // end handling checkbox or radio
			
			elseif ($properties[0] == 'select') {
				$nowcounter = 0;
				$nowvalues = explode(',',$settings['value'][$stepper]);
				$nowdescriptions = explode('|',$settings['description'][$stepper]);
				//$settings['setting'][$stepper] = $this->adjust_setting_name($settings['setting'][$stepper]);
				$output .= "<select name='{$setting_name}' size='1'>";
				while ($nowcounter < $properties[1]) {
					($setting_value == $nowvalues[$nowcounter]) ?
						$selected = ' selected="selected"' : $selected = '';
					$output .=  "<option value='{$nowvalues[$nowcounter]}'{$selected}>{$nowdescriptions[$nowcounter]}</option>\n";
					$nowcounter ++;
				} // end loop over select values
				
				$output .= "</select>\n";
			} // end handling select
			
			elseif ($properties[0] == 'extra')
				$output .= wptexturize(__($settings['description'][$stepper], $domain));
			
			$output .= "\n</td>\n</tr>\n";
			
			if (($stepper + 1 == $elements) || ($settings['header'][$stepper + 1] != '') || ($settings['preface'][$stepper + 1] != '')) {
				$output .= '</table>';
			}
			
			$stepper ++;
		} // end loop over headings
		
		if ($echo)
			echo $output;
		// NOTE: Have now retrofitted to put our output in meta boxes
		// NOTE: Don't like the boxed output? Then just return it...
		//else return $output;
		else $this->boxit($output);
		return null;
	
	} // end function which outputs options
	
	function boxit($output) {
	$boxes = explode('<!--secstart-->', $output);
	foreach ($boxes as $box) {
		$titleclose = strpos($box,'</h3>');
		$title = substr($box,0,$titleclose+5);
		$title = strip_tags($title);
		$body = substr($box, $titleclose+5, strlen($box) - ($titleclose+5));
		$this->add_meta_box_simple($body,$title,$this->box_hook);
		} // end loop over sections
	return;
	}
	
	function add_meta_box_simple($data = null, $title, $page, $context = 'normal', $priority = 'high') {
		// set up a metabox with a simple callback which takes an array as a parameter and echoes the value it finds for the array key corresponding to its own ID
		$id = $this->plugin_prefix . sanitize_title_with_dashes($title);
		$this->boxed_set[$id] = $data;
		add_meta_box($id, $title, create_function('$a', "echo \$a['$id'];"), $page, $context, $priority);
		return;
	}
	
	function do_meta_boxes_simple($hook, $context = 'normal', $data = null) {
		wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
		do_meta_boxes($hook, $context, $data);
		$this->postbox_js(); // echo the JS that will initialize our postboxes for us
		return;
	}
	
	function postbox_js() {
		$page = $this->box_hook;
		// note the line about closing postboxes that should be closed no longer seems to be needed
		$js = "
			<script type='text/javascript'>
				//<![CDATA[
				jQuery(document).ready( function($) {
					// close postboxes that should be closed
					$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
					// postboxes setup
					postboxes.add_postbox_toggles('{$page}');
				});
				//]]>
			</script>
			";
		echo $js;
		return;
	}
	

} // end class definition


?>