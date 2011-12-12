<?php

class iWaK_Admin_Page {
    var $title;
    var $filename;
    var $is_child;
    var $links;
    var $groups;
    var $latest_version;
    var $cssclass;

    function __construct($args) {
        if(!is_array($args))
            return;
            
        extract($args);
        $this->title = $title;
        $this->links = $links;
        $this->filename = strtolower($filename);
        $this->is_child = isset($is_child) ? $is_child : true;
        $this->cssclass = $class;
        $this->template_name = empty($template) ? 'index.php' : $template;
        $this->groups = array();
        
        foreach((array)$groups as $group) {
            if(empty($group['type']))
                $group['type'] = 'option_table';
                
            if(empty($group['source'])) {
                preg_match('/^(\w+)/i', $this->filename, $matches);
                $group['source'] = strtolower($matches[1]);//showinfo($group['source']);
            }
            
            $class = 'iwak_'. $group['type'];
            $this->groups[] = new $class($group);
        }
            
        $this->links = $links;
        $this->latest_version = THEME_VERSION;
        $this->template_path = FRAMEWORK_PATH. '/templates/'. ADMIN_TEMPLATE;
        $this->template_url = FRAMEWORK_URL. '/templates/'. ADMIN_TEMPLATE;
        
        add_action('admin_menu', array(&$this, 'admin_menu'));
    }
    
    function admin_scripts() {
    
        echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>';
        echo '<script src="'.$this->template_url.'/js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>';
        
        echo '<script type="text/javascript" src="'.THEME_URL.'/js/cufon-yui.js"></script>';
        echo '<script type="text/javascript" src="'.THEME_URL.'/js/Quicksand_Book_400.font.js"></script>';
        
        echo '<link rel="stylesheet" media="screen" type="text/css" href="'.$this->template_url.'/colorpicker/css/colorpicker.css" />';
        echo '<script type="text/javascript" src="'.$this->template_url.'/colorpicker/js/colorpicker.js"></script>';
        
        echo '<script src="'.$this->template_url.'/js/jquery.selectbox-0.6.1.js" type="text/javascript"></script>';
        echo '<script src="'.$this->template_url.'/js/ajaxupload.js" type="text/javascript"></script>';
        echo '<script src="'.$this->template_url.'/js/main.js" type="text/javascript"></script>';

        foreach($this->groups as $group)
            if(method_exists($group, 'print_scripts'))
                $group->print_scripts();
                
        // Add new entry ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.iwak-add').click(function() {
                    var default_item  = jQuery(this).parent().prev('.default-item');
                    if(!default_item.length)
                        return;
                        
                    // Fetch index of the last item and plus 1
                    var index = 0;
                    var last_item = default_item.prev();
                    
                    if(last_item.length) {
                        var reg = /([^\[]+)\[(\d+)\]/;
                        var matches = reg.exec(jQuery(':input:first', last_item).attr('id'));
                        if(matches) {
                            index = ++ matches[2];
                            var newstr = matches[1] + '[' + index + ']';
                        }
                    }
                    
                    // Generate the new item
                    reg = /([^\[]+)\[.+\]/;
                    matches = reg.exec(jQuery(':input:first', default_item).attr('id'));
                    if(!newstr) {
                        newstr = matches[1] + 's[0]';
                    }
                    var pattern = new RegExp(RegExp.quote(matches[1]), 'ig');
                    var new_item = default_item.clone().removeClass('default-item').removeClass('hidden');
                    
                    // Replace option id&name, old implementation is to replace all match string found in whole html string
                    // Current logic is to find form inputs one by one and do replace only on attributes 'id' and 'name', better but performance probably down a bit
                    // Alternatively, update old way by a more complex pattern which match only 'id' and 'name', has the same effect and may has better performance, compare to current logic
                    // do a performance compare will help, anyway, sometime in future
                    
                    //new_item.html(new_item.html().replace(pattern, newstr));
                    jQuery(':input', new_item).each(function() {
                        jQuery(this).attr('id', jQuery(this).attr('id').replace(pattern, newstr));
                        jQuery(this).attr('name', jQuery(this).attr('name').replace(pattern, newstr));
                    });
                    default_item.before(new_item);
                    
                    // Replace selectbox
                    if(jQuery('select', new_item).length) {
                        jQuery('.jquery-selectbox', new_item).unselectbox();
                        jQuery('select:not(.hidden)', new_item).selectbox();
                    }
                    
                    // Make this new item draggable
                    if(default_item.hasClass('draggable-item'))
                        new_item.dragitem();                    
                });
            });
        </script>
        
        <?php // Ajax Upload ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                Cufon.replace('#iwak-admin h1', {fontFamily: 'Quicksand book'});  

                jQuery('.image_upload_button').each(function(){
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');	
                    var status = jQuery(this).next('div');
                    var loadingGif = '<img alt="Uploading..." src="<?php echo THEME_URL. '/images/ajax-loader.gif'; ?>" />';
                    new AjaxUpload(clickedID, {
                        action: '<?php echo admin_url("admin-ajax.php"); ?>',
                        name: clickedID, // File upload name
                        data: { // Additional data to send
                              action: 'iwak_ajax_action',
                              type: 'upload',
                              data: clickedID },
                        autoSubmit: true, // Submit file after selection
                        responseType: false,
                        onChange: function(file, extension){},
                        onSubmit: function(file, ext){
                          this.disable();
                           if (! (ext && /^(jpg|png|jpeg|gif|ico)$/.test(ext))){  
                                      // check for valid file extension  
                                      status.text('Only JPG, PNG, GIF or ICO files are allowed');  
                                      return false;  
                                  }  
                                  status.html(loadingGif);  
                        },
                        onComplete: function(file, response) {
                          status.text(' ');
                          this.enable(); // enable upload button
                          
                          // If there was an error
                          if(response.search('Upload Error') > -1){
                              status.text(response);
                          }
                          else{
                              clickedObject.prev('input').val(response);
                              clickedObject.parent().find('div.ipreview .inner').attr('src', response);
                          }
                        }
                    });
                });
                
                jQuery('.font_upload_button').each(function(){
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');	
                    var status = jQuery(this).next('div');
                    var loadingGif = '<img alt="Uploading..." src="<?php echo THEME_URL. '/images/ajax-loader.gif'; ?>" />';
                    new AjaxUpload(clickedID, {
                        action: '<?php echo admin_url("admin-ajax.php"); ?>',
                        name: clickedID, // File upload name
                        data: { // Additional data to send
                              action: 'iwak_ajax_action',
                              type: 'upload',
                              data: clickedID },
                        autoSubmit: true, // Submit file after selection
                        responseType: false,
                        onChange: function(file, extension){},
                        onSubmit: function(file, ext){
                          this.disable();
                           if (! (ext && /^(js)$/.test(ext))){  
                                      // check for valid file extension  
                                      status.text('Only JS files are allowed');  
                                      return false;  
                                  }  
                                  status.html(loadingGif);  
                        },
                        onComplete: function(file, response) {
                          status.text(' ');
                          this.enable(); // enable upload button
                          
                          // If there was an error
                          if(response.search('Upload Error') > -1){
                              status.text(response);
                          }
                          else{
                              clickedObject.prev('input').val(response);
                          }
                        }
                    });
                });
            });
        </script>

    <?php
    }
    
    function admin_menu() {
        $prefix = str_replace(' ', '-', strtolower(THEME_NAME));
        $this->slug = $this->is_child ? $prefix. '/'. $this->filename : $prefix. '/general';

        if(!$this->is_child) {
            if(function_exists('add_object_page'))
                add_object_page ('Page Title', THEME_NAME, 8, $prefix. '/general');
            else
                add_theme_page(THEME_NAME, THEME_NAME, 'Administrator', $prefix. '/general');
        }

        $admin_page = add_submenu_page($prefix. '/general', $this->title, $this->title, 8, $this->slug, array(&$this, 'render'));
            
        if( isset($_GET['page']) && $_GET['page'] === $this->slug ) {
                        //wp_deregister_script('jquery');

            //add_action('admin_print_scripts', array(&$this, 'admin_scripts'));
            add_action('admin_head', array(&$this, 'admin_scripts'));
            
            if ( 'save' == $_REQUEST['action'] ) {

                $options = iwak_get_options();
                $pagename = str_ireplace('.php', '', $this->filename);
                //showinfo($pagename);
                
                // Get rid of influence of PHP Magic Quotes
                //if ( function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ) {
                    $_POST      = array_map( 'stripslashes_deep', $_POST );
                    //$_GET       = array_map( 'stripslashes_deep', $_GET );
                    //$_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
                    //$_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
                //}

                foreach($_POST as $key=>$value) {
                    //echo $key. ' = '. $value. '<br>';
                    $options[$key] = $_POST[$key];
                }
                // Before 1.5
                // $options[$pagename] = $o;
                
                // Since 1.5, no longer organized options per options page, thus when no entries data posted, it could be either entries are deleted all or the POST is from other options page
                if(isset($_POST['testimonial_record']) && !isset($_POST['testimonial_records']))
                    $options['testimonial_records'] = array();
                if(isset($_POST['filter']) && !isset($_POST['filters']))
                    $options['filters'] = array();
                if(isset($_POST['featured_post']) && !isset($_POST['featured_posts']))
                    $options['featured_posts'] = array();
                if(isset($_POST['widget_area']) && !isset($_POST['widget_areas']))
                    $options['widget_areas'] = array();
                
                // Remove duplicate entries
                if($pagename === 'featured') {
                    $options['filters'] = iwak_array_unique($options['filters']);
                    $options['featured_posts'] = iwak_array_unique($options['featured_posts']);
                }
                
                update_option(IWAK_OPTIONS, $options);
                header("Location: admin.php?page=". $_GET['page']. "&saved=true");
                die;
                
            } else if( 'reset' == $_REQUEST['action'] ) {
            
                //update_option(OPTION_NAME, $this->default_options);
                //wp_redirect($_SERVER['REQUEST_URI']);
                exit;
            }
        }
        
        // Seperately add scripts for each theme options page, in favor of add the scripts only when the page is theme options page
        //add_action("admin_print_scripts-$admin_page", array(&$this, 'admin_scripts'));
    }

    function render() {
        
        $this->check_updates();
        
        $bricks = array();
        $bricks['class'] = $this->cssclass;
        $bricks['heading'] = $this->create_heading();        
        $bricks['links'] = $this->create_links();
        $bricks['navigation'] = $this->create_navigation();
        $bricks['message'] = $this->create_message();
        $bricks['tables'] = $this->create_option_tables();
        $bricks['submit'] = $this->create_submit_button();
        
        include_once($this->template_path. '/'. $this->template_name);
    }
        
    function check_updates() {
        $options = iwak_get_options();
        $raw_response = wp_remote_post('http://www.iwakthemes.com/demo/themes.php?theme='. THEME_NAME. '&version='. THEME_VERSION);
        if( !is_array($raw_response) || 200 != $raw_response['response']['code'] || !$options['general']['notification'])
            return;

        $this->message = $raw_response['body'];
    }

    function update_notify() {
        echo '<div class="iwak-message message-update"><p><strong>'. $this->message. '</strong></p></div>';
    }
    
    function create_submit_button() {
        ob_start();
        ?>
            <input class="button" name="save" type="submit" value="Save changes" />    
            <input type="hidden" name="action" value="save" />
        <?php
        return ob_get_clean();
    }
    
    function create_links() {
        return $this->links;
    }
    
    function create_heading() {
        ob_start();
        $words = explode(' ', $this->title);
        $title = '<span class="first-word">'. array_shift($words). '</span> '. implode(' ', $words);
        ?>
        <div class="iwak-heading">
            <div class="suptitle"><?php echo THEME_NAME; ?> ADMIN</div>
            <h1><?php echo $title ?></h1>
        </div>
        <?php
        return ob_get_clean();
    }
    
    function create_message() {
            
        if($_POST['action'] == 'save' || $_REQUEST['saved'])
            $msg .= '<div class="iwak-message message-saved"><p><strong>'. __("Settings saved.", THEME_NAME). '</strong></p></div>';
        
        if(!empty($this->message))
            $msg .= '<div class="iwak-message message-update"><p><strong>'. $this->message. '</strong></p></div>';
            
        return $msg;
    }
    
    function create_navigation() {
        $nav = '<ul class="nav">';
        foreach($this->groups as $group) {
            $i ++;
            $class = 'nav-panel nav-panel-'. $i;
            $class .= $i == 1 ? ' nav-panel-active' : '';
            $nav .= "<li class='$class'><h2>". $group->title. '</h2><p>'. $group->desc. '</p></li>';
        }
        $nav .= '</ul>';
        return $nav;
    }

    function create_option_tables() {
        $tables = array();
        
        foreach($this->groups as $group) {
            if($group->title)
                $tables[strtolower($group->title)] = $group->build();
            else
                $tables[] = $group->build();
        }
        
        return $tables;
    }

}