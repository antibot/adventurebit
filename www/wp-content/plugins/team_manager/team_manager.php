<?php
/*
Plugin Name: Team manager
Plugin URI: http://adventurebit.com/
Description: Team manager
Author: Selikhov Dmitry
Version: 1.0
Author URI: http://adventurebit.com/
*/

    
function team_manager_files() {
  $styleUrl = plugins_url('css/style.css', __FILE__);
  $styleFile = WP_PLUGIN_DIR . '/team_manager/css/style.css';
  if(file_exists($styleFile)) {
    wp_register_style('team_manager_css', $styleUrl);
    wp_enqueue_style('team_manager_css');
  }
  
  $scriptUrl = plugins_url('js/script.js', __FILE__);
  $scriptFile = WP_PLUGIN_DIR . '/team_manager/js/script.js';
  if(file_exists($scriptFile)) {
    wp_register_script('team_managert_js', $scriptUrl); 
    wp_enqueue_script('team_manager_js');
  } 
  
}
 
add_action('wp_footer', 'team_manager_files'); 

add_action('wp_print_footer_scripts', 'team_manager_files');



function team_manager_menu() {
  add_options_page('Team manager', 'Team manager', 'manage_options', 'team_manager', 'team_manager_menu_options');
}

add_action('admin_menu', 'team_manager_menu');

function team_manager()
{

}

function team_manager_menu_options() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));	
	}
	
  if(isset($_POST['team_manager_options_send'])) {
    if(wp_verify_nonce($_POST['_wpnonce'], 'team_manager_options')) {
      
      $url = $_POST['url'];
      $description = $_POST['description'];
      
      $size = count($url);
      
      $container = new stdClass();
      
      $container->url = $url;
      $container->description = $description;
      $container->size = $size;

      update_option('team_manager',json_encode($container));
      
    }
  }
	
	?>
	
	<div class="wrap team_manager_admin">       
    <div id="icon-options-general" class="icon32"></div>
    <h2>
      Team manager
      <a class="add-new-h2 add">Add New Member</a>
    </h2>
	 
    <p>
      <form method="post" id="team_manager_options">
      
        <?= wp_nonce_field('team_manager_options'); ?>
      
        <table class="wp-list-table widefat fixed pages">
        
        <thead>
          <tr>
            <th class="manage-column column-cb check-column">
              <input type="checkbox">
            </th>
            <th width="100">
              <a href="#">
                <span>Photo</span>
              </a>
            </th>
            <th width="300">
              <a href="#">
                <span>Photo URL</span>
                <span class="sorting-indicator"></span>
              </a>
            </th>
            <th>
              <a href="#">
                <span>Description</span>
                <span class="sorting-indicator"></span>
              </a>
            </th>
          </tr>
        </thead>
        
        <tbody> 
          <?php
            $option = get_option('team_manager');  
            $data = json_decode($option);
            
            $size = $data->size;   
            $url = $data->url;
            $description = $data->description;
            
            for($i = 0; $i < $size; ++$i) {
              ?> 
              <tr>
                <th class="check-column">
                  <input type="checkbox">
                </th>
                <th>
                  <img src="" />
                </th>
                <th>
                  <textarea name="url[]" class="large-text code url"><?= $url[$i] ?></textarea>
                </th>
                <th>
                  <textarea name="description[]" class="large-text code description"><?= $description[$i] ?></textarea>
                </th>
              </tr>
              <?php
            }
             
          ?> 
        </tbody>
        
        <tfoot>  
          <tr>
            <th class="manage-column column-cb check-column">
              <input type="checkbox">
            </th>
            <th width="100">
              <a href="#">
                <span>Photo</span>
              </a>
            </th>
            <th width="300">
              <a href="#">
                <span>Photo URL</span>
                <span class="sorting-indicator"></span>
              </a>
            </th>
            <th>
              <a href="#">
                <span>Description</span>
                <span class="sorting-indicator"></span>
              </a>
            </th>
          </tr>
        </tfoot>
        </table>
	 
        <p>
          <div class="alignleft actions">
            <select name="action2">
              <option value="save" selected="selected">Save</option>
              <option value="edit">Edit</option>
              <option value="remove">Remove</option>
            </select>
            <input type="submit" name="team_manager_options_send" id="doaction" class="button-secondary action" value="Apply">
          </div>
        </p>
        
      </form>
      
    </p>
	 
	</div>
	
	<script>
	
  (function($){
  
    $(document).ready(function(){
      
      'use strict';
      
      var TEAM_MANAGER = {};
      
      TEAM_MANAGER.add = $('.team_manager_admin .add');  
      TEAM_MANAGER.content = $('.team_manager_admin .wp-list-table tbody');

      TEAM_MANAGER.template = [
        '<tr>',
          '<th class="check-column">',
            '<input type="checkbox">',
          '</th>',
          '<th>',
            '',
          '</th>',
          '<th>',
            '<textarea name="url[]" class="large-text code url"></textarea>',
          '</th>',
          '<th>',
            '<textarea name="description[]" class="large-text code description"></textarea>',
          '</th>',
        '</tr>'
      ].join('');
      
      TEAM_MANAGER.add.click(function(){
        TEAM_MANAGER.content.append(TEAM_MANAGER.template);
      });
      
      TEAM_MANAGER.content.delegate('.url', 'change', function(){
        var elem = $(this);
      });
    
    });
  
  })(jQuery);
	
	</script>
	
	<?php
}

/* Shortcode
------------------------------------------------------------------------------*/ 

function team_manager_shortcode($args) {
  echo 123;  
}

add_shortcode('team_manager', 'team_manager_shortcode');

/* Widget
------------------------------------------------------------------------------*/ 
 
function widget_team_manager_content($args) {

  extract($args);
    
  $options = get_option('team_manager'); 
  $title = $options['title'];  
     
  echo  $before_widget,
        $before_title,
        $title,
        $after_title,
        '<div class="team_manager">',
        '',
        '</div>',
        $after_widget;
}

function widget_team_manager_control() {

}
 
register_sidebar_widget(__('Team manager'), 'widget_team_manager_content', 'widget_team_manager');   
register_widget_control(__('Team manager'), 'widget_team_manager_control');   

add_action('plugins_loaded', 'team_manager');

?>