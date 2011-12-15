<?php
/*
Plugin Name: Easy Registration/Authorization
Plugin URI: http://adventurebit.com/
Description: Easy Registration/Authorization in wordpress
Author: Selikhov Dmitry
Version: 1.0
Author URI: http://adventurebit.com/
*/

function files() {

  $styleUrl = plugins_url('css/style.css', __FILE__);
  $styleFile = WP_PLUGIN_DIR . '/EasyInUp/css/style.css';
  if(file_exists($styleFile)) {
    wp_register_style('inout_css', $styleUrl);
    wp_enqueue_style('inout_css');
  }
   
  $scriptUrl = plugins_url('js/script.js', __FILE__);
  $scriptFile = WP_PLUGIN_DIR . '/EasyInUp/js/script.js';
  if(file_exists($scriptFile)) {
    wp_register_script('inout_js', $scriptUrl); 
    wp_enqueue_script('inout_js');
  } 

}
 
add_action('wp_footer', 'files'); 
 
function inout()
{
  return '';  
}
 
function widget_inout($args) {
  extract($args);
  
  $options = get_option('inout');
  $title = $options['title'];  
     
  echo $before_widget;
  echo $before_title;
  echo $title;
  echo $after_title;
  echo inout();
  echo $after_widget;
}

function widget_inout_control() {

  $options = get_option('inout', array(
    'title' => 'Easy Sign In/Up',
    'registration' => 'on',
    'authorization' => 'on',
    'exit' => 'on',
    'type' => 'dialog'
  ));
  
  if(isset($_POST['inout-nonce'])) {
    $options['title'] = $_POST['inout-title'];
    
    $options['registration'] = $_POST['inout-registration'];
    $options['authorization'] = $_POST['inout-authorization'];
    $options['exit'] = $_POST['inout-exit'];
    
    $options['type'] = $_POST['inout-type']; 
  }      

  update_option('inout', $options);
?>

<div>
  <label for="inout-title">Title:</label>
</div>
<input class="widefat" type="text" id="inout-title" name="inout-title" maxlength="30" value="<?= $options['title'] ?>" />

<p>
  <div>
    <label>
      <input type="checkbox" name="inout-registration" <?= $options['registration']=='on'?'checked':'' ?> />
      Registration
    </label>
  </div>
</p>

<p>
  <div>
    <label>
      <input type="checkbox" name="inout-authorization" checked />
      Authorization
    </label>
  </div>
</p>

<p>
  <div>
    <label>
      <input type="checkbox" name="inout-exit" checked />
      Exit
    </label>
  </div>
</p>

<p>
  <div>
    <label>
      Type:  
    </label>
  </div>
  <select name="inout-type">
    <option value="dialog"  <?= $options['type']=='dialog'?'selected':'' ?>>Dialog</option>
    <option value="sidebar" <?= $options['type']=='sidebar'?'selected':'' ?>>Sidebar</option>
    <option value="list" <?= $options['type']=='list'?'selected':'' ?>>List</option>
  </select>
</p>

<input type="hidden" name="inout-nonce" value="<?= wp_create_nonce('inout-nonce'); ?>" /> 
  
<?php
}
 
register_sidebar_widget(__('Easy Registration/Authorization'), 'widget_inout', 'inout');   
register_widget_control(__('Easy Registration/Authorization'), 'widget_inout_control');   

add_action('plugins_loaded', 'inout');

?>