<?php
/*
Plugin Name: Easy Registration/Authorization
Plugin URI: http://adventurebit.com/
Description: Easy Registration/Authorization in wordpress
Author: Selikhov Dmitry
Version: 1.0
Author URI: http://adventurebit.com/
*/
    
include_once 'modules/form.php';    
    
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
  
  $scriptUrl = plugins_url('js/json.js', __FILE__);
  $scriptFile = WP_PLUGIN_DIR . '/EasyInUp/js/json.js';
  if(file_exists($scriptFile)) {
    wp_register_script('json_js', $scriptUrl); 
    wp_enqueue_script('json_js');
  }
  
  $scriptUrl = plugins_url('js/effects.js', __FILE__);
  $scriptFile = WP_PLUGIN_DIR . '/EasyInUp/js/effects.js';
  if(file_exists($scriptFile)) {
    wp_register_script('effects_js', $scriptUrl); 
    wp_enqueue_script('effects_js');
  }

  ?>
  <script>
    var INOUT_PLUGIN_URL = '<?= plugins_url("EasyInUp/") ?>';
  </script>
  <?php

}
 
add_action('wp_footer', 'files'); 
 
function inout()
{
  if($_GET['confirmation']) {
    $confirmation = $_GET['confirmation'];
  }
  if($_GET['restoration']) {
    $restoration = $_GET['restoration'];
  }
}

function FORM_CONTENT() {
  if(is_user_logged_in()) {
    return EXIT_CONTENT();     
  } else {
    return AUTHORIZATION_CONTENT();  
  }
}

/* Shortcode
------------------------------------------------------------------------------*/ 

function inout_shortcode($args) {
  echo FORM_CONTENT();  
}

add_shortcode('easy_in_up', 'inout_shortcode');

/* Widget
------------------------------------------------------------------------------*/ 
 
function widget_inout_content($args) {

  $options = get_option('inout');
  extract($args);

  $title = $options['title'];  
     
  echo  $before_widget,
        $before_title,
        $title,
        $after_title,
        '<div class="inout_container">',
        '<div class="inout_screen"></div>',
        '<div class="inout_loading"></div>',
        '<div class="inout_content">',
        FORM_CONTENT(),
        '</div>',
        '<div class="inout_message"></div>',
        '</div>',
        $after_widget;
}

function widget_inout_control() {

  $options = get_option('inout', array(
    'title' => 'Easy Sign In/Up',
    'email' => get_option('admin_email'),
    'auth-redirect' => '',
    'reg-redirect' => '',
    'exit-redirect' => '',
    'conf-reg-line' => '{BLOGNAME} - registration confirmation',
    'rest-pwd-line' => '{BLOGNAME} - password restoration',
  ));
  
  if(isset($_POST['nonce'])) {
    $options['title'] = $_POST['title'];
    $options['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : get_option('admin_email');
    
    $options['confirm'] = $_POST['confirm'];
    
    $options['conf-reg-text'] = $_POST['conf-reg-text'];
    $options['rest-pwd-text'] = $_POST['rest-pwd-text'];
    
    $options['conf-reg-line'] = $_POST['conf-reg-line'];
    $options['rest-pwd-line'] = $_POST['rest-pwd-line'];

    $options['auth-redirect'] = $_POST['auth-redirect'];
    $options['reg-redirect'] = $_POST['reg-redirect'];
    $options['exit-redirect'] = $_POST['exit-redirect'];
  }  

  update_option('inout', $options);
?>

<div>
  <label for="title">Title:</label>
</div>
<input class="widefat" type="text" id="title" name="title" maxlength="30" value="<?= $options['title'] ?>" />

<p>
  <div>
    Email:
  </div>   
  <input class="widefat" type="text" name="email" maxlength="100" value="<?= $options['email'] ?>" /> 
</p>

<p>       
  <input type="checkbox" name="confirm" <?= $options['confirm'] == 'on' ? 'checked' : '' ?> /> 
  Confirm registration?  
</p>

<p>
  <div>
    Authorization redirect link:
  </div>   
  <input class="widefat" type="text" name="auth-redirect" maxlength="100" value="<?= $options['auth-redirect'] ?>" /> 
</p>

<p>
  <div>
    Registration redirect link:
  </div>   
  <input class="widefat" type="text" name="reg-redirect" maxlength="100" value="<?= $options['reg-redirect'] ?>" /> 
</p>

<p>
  <div>
    Exit redirect link:
  </div>   
  <input class="widefat" type="text" name="exit-redirect" maxlength="100" value="<?= $options['exit-redirect'] ?>" /> 
</p>

<p> 
  <div>
    Confirmation registration text message:
  </div>   
  Subject Line: <input type="text" style="width: 150px;" name="conf-reg-line" maxlength="256" value="<?= $options['conf-reg-line'] ?>" /> 
  <textarea class="widefat" name="conf-reg-text" rows="5"><?= $options['conf-reg-text'] ?></textarea>
</p>

<p>
  <div>
    Restoration password text message:
  </div>   
  Subject Line: <input type="text" style="width: 150px;" name="rest-pwd-line" maxlength="256" value="<?= $options['rest-pwd-line'] ?>" />
  <textarea class="widefat" name="rest-pwd-text" rows="5"><?= $options['rest-pwd-text'] ?></textarea>
</p>

<p>
  <div>
    <i>Special tags:</i>
  </div>
  {BLOGNAME}
  {BLOGLINK}
  {TIME}
  {CODE}
  {CODELINK}
</p>

<input type="hidden" name="nonce" value="<?= wp_create_nonce('inout'); ?>" /> 
  
<?php
}
 
register_sidebar_widget(__('Easy Registration/Authorization'), 'widget_inout_content', 'widget_inout');   
register_widget_control(__('Easy Registration/Authorization'), 'widget_inout_control');   

add_action('plugins_loaded', 'inout');

?>