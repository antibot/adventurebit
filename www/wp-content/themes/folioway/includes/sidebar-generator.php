<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars. 
Version: 1.1.0
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

/*
Copyright (C) 2009 Kyle Robert Getson, kylegetson.com and getson.info

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class iwak_sidebar_generator {
	
	function iwak_sidebar_generator(){
		add_action('init',array('iwak_sidebar_generator','init'));
		// add_action('admin_menu',array('iwak_sidebar_generator','admin_menu'));
		// add_action('admin_print_scripts', array('iwak_sidebar_generator','admin_print_scripts'));
		// add_action('wp_ajax_add_sidebar', array('iwak_sidebar_generator','add_sidebar') );
		// add_action('wp_ajax_remove_sidebar', array('iwak_sidebar_generator','remove_sidebar') );
			
		// //edit posts/pages
		 add_action('edit_form_advanced', array('iwak_sidebar_generator', 'edit_form'));
		 add_action('edit_page_form', array('iwak_sidebar_generator', 'edit_form'));
		
		// //save posts/pages
		 add_action('edit_post', array('iwak_sidebar_generator', 'save_form'));
		 add_action('publish_post', array('iwak_sidebar_generator', 'save_form'));
		 add_action('save_post', array('iwak_sidebar_generator', 'save_form'));
		 add_action('edit_page_form', array('iwak_sidebar_generator', 'save_form'));

	}
	
	function init(){
		//go through each sidebar and register it
	    $sidebars = iwak_sidebar_generator::get_sidebars();
	    
	    if(is_array($sidebars)){
			foreach($sidebars as $sidebar){
				$sidebar_class = iwak_sidebar_generator::name_to_class($sidebar);
				register_sidebar(array(
					'name'=>$sidebar['name'],
					'description'=>$sidebar['description'],
			    	'before_widget' => '<div id="%1$s" class="widget '.$sidebar_class.' %2$s widget-number">',
                    'after_widget'   => '</div>',
                    'before_title'   => '<h2 class="widget-title"><span class="widget-icon"></span>',
                    'after_title'    => '</h2>'
		    	));
			}
		}
	}
		
	/**
	 * for saving the pages/post
	*/
	function save_form($post_id){
		$is_saving = $_POST['edit'];
		if(!empty($is_saving)){
			delete_post_meta($post_id, 'selected_sidebar');
			add_post_meta($post_id, 'selected_sidebar', $_POST['sidebar_generator']);
			delete_post_meta($post_id, 'auto_formatting');
			add_post_meta($post_id, 'auto_formatting', $_POST['auto_formatting']);
			delete_post_meta($post_id, 'video_link');
			add_post_meta($post_id, 'video_link', $_POST['video_link']);
			delete_post_meta($post_id, 'is_video');
			add_post_meta($post_id, 'is_video', $_POST['is_video']);
		}		
	}
	
	function edit_form(){
	    global $post;
	    $post_id = $post;
	    if (is_object($post_id)) {
	    	$post_id = $post_id->ID;
	    }
	    $selected_sidebar = get_post_meta($post_id, 'selected_sidebar', true);
	    $auto_formatting = get_post_meta($post_id, 'auto_formatting', true);
	    $video_link = get_post_meta($post_id, 'video_link', true);
	    $is_video = get_post_meta($post_id, 'is_video', true);
        if($is_video == '') $is_video = 1;
		?>
	 
	<div class='meta-box-sortables'>
		<div id="iwak-post-options" class="postbox " >
			<div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span><?php echo THEME_NAME; ?> General Post Options</span></h3>
			<div class="inside">
                
                <div class="iwak-post-option">
					<p>
						<strong>Lightbox Media</strong>
					</p>
                    
                    <p>
                        <input type="text" class="large-text" id="video_link" name="video_link" value="<?php echo $video_link; ?>">
                    </p>
                    <p>Is this a video or an image?</p>
                    <p>
                        <label><input type="radio" <?php if($is_video) echo 'checked="checked"'; ?> value="1" class="selector" id="is_video_2" name="is_video"> Video</label><br>
                        <label><input type="radio" <?php if(!$is_video) echo 'checked="checked"'; ?> value="0" class="selector" id="is_video" name="is_video"> Image</label>
                    </p>
                            
					<p class="help">
						Media here will be displayed or played in the opened lightbox when post thumbnial is clicked, you may specify a video or a different image here.
					</p>
                </div>

                <div class="iwak-post-option">
					<p>
						<strong>Custom Sidebar</strong>
					</p>
					<?php 
					//global $wp_registered_sidebars;
					//var_dump($wp_registered_sidebars);		
 ?>
							<select name="sidebar_generator">
								<option value="0"<?php if($selected_sidebar == ''){ echo " selected";} ?>>Select A Sidebar</option>
							<?php
							$sidebars = iwak_sidebar_generator::get_sidebars();//$wp_registered_sidebars;
							if(is_array($sidebars) && !empty($sidebars)){
								foreach($sidebars as $sidebar){
									if($selected_sidebar == $sidebar['name']){
										echo "<option value='{$sidebar['name']}' selected>{$sidebar['name']}</option>\n";
									}else{
										echo "<option value='{$sidebar['name']}'>{$sidebar['name']}</option>\n";
									}
								}
							}
							?>
							</select> 
                            
					<p class="help">
						Select the sidebar you wish to display on this page.
					</p>
				</div>
                
                <div class="iwak-post-option">
					<p>
						<strong>Not to Touch My Code</strong>
					</p>
                    
                    <p>
                        <label><input type="radio" <?php if($auto_formatting == 'off') echo 'checked="checked"'; ?> value="off" class="selector" id="auto_formatting_2" name="auto_formatting"> On</label><br>
                        <label><input type="radio" <?php if($auto_formatting == 'on' || $auto_formatting == '' ) echo 'checked="checked"'; ?> value="on" class="selector" id="auto_formatting" name="auto_formatting"> Off</label>
                    </p>
                    
					<p class="help">
						Choose 'on' to disable wordpress auto formatting on this post/page. (note, this feature won't prevent Visual Editor changing your code )
					</p>
                </div>
                
                <input name="edit" type="hidden" value="edit" />
			</div>
		</div>
	</div>

		<?php
	}
	
	/**
	 * called by the action get_sidebar. this is what places this into the theme
	*/
	function get_sidebar($name=null, $default=true){
		/*if(!is_singular()){
			if($name != "0"){
				dynamic_sidebar($name);
			}else{
				dynamic_sidebar();
			}
			return;//dont do anything
		}*/
		global $wp_query;
		$post = $wp_query->get_queried_object();
		$selected_sidebar = get_post_meta($post->ID, 'selected_sidebar', true);
		if($selected_sidebar != '' && $selected_sidebar != "0"){
			echo "\n\n<!-- begin generated sidebar -->\n";
			dynamic_sidebar($selected_sidebar);
			echo "\n<!-- end generated sidebar -->\n\n";
		} else {                
			if ( function_exists('dynamic_sidebar') ) {
                if( !dynamic_sidebar($name) && $default )
                    if( !dynamic_sidebar('General Sidebar') )
                        get_sidebar();
			}
		}
	}
	
	/**
	 * replaces array of sidebar names
	*/
	function update_sidebars($sidebar_array){
		$sidebars = update_option('sidebars',$sidebar_array);
	}	
	
	/**
	 * gets the generated sidebars
	*/
	function get_sidebars(){
		$sidebars = iwak_get_list('widget_areas');
		return $sidebars;
	}
    
	function name_to_class($name){
		$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);
		return $class;
	}
	
}
global $iks;
$iks = new iwak_sidebar_generator;

function generated_dynamic_sidebar($name='0'){
	sidebar_generator::get_sidebar($name);	
	return true;
}
?>