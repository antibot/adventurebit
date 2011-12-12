<?php
/*-----------------------------------------------------------------------------------

CUSTOM FILTERS

    iwak_nav_menu_args
    iwak_image_sizes
    iwak_post_image_attributes
    iwak_post_link_attributes

-----------------------------------------------------------------------------------*/

if(!class_exists('iWaK_Template')) {

    class iWaK_Template {
        var $o;
        
        function __construct() {
        
            // Translate, if applicable
            load_theme_textdomain(THEME_NAME, THEME_PATH . '/languages');
            
            add_action('admin_head', array(&$this, 'admin_head')); 
            add_action('wp_ajax_iwak_ajax_action', array(&$this, 'ajax_callback'));
            add_action('wp_ajax_nopriv_iwak_ajax_action', array(&$this, 'ajax_callback_nopriv'));
            
            /*    Do an array merge with default options which is created in runtime thus always holds the correct or new options/option-structure
             *    Purpose: Introduce options change (if have) when install theme from an old version
             *
             *    [ PROBLEM BEING SOLVED HERE ]  
             * 
             *     When    -   a theme upgrade occurs or a fresh install but there is somehow already an iwak options entry exists in the database (say from another theme of mine),
             *     Consider -   there in the new version may got some options change such as new options added, some old ones removed, some name-changed and, structure somehow changed
             *     If          -   still no codes handle options change here, there are risks that theme function cannot find the new options, say for default values, 
             *     Thus     -   may cause an error message throwed and some weird behaviors or maybe find a wrong one, thus still run to problem but probably without an error message, 
             *                     which is even more difficult for trouble shooting
             *
             *    [ SOLUTION IMPLEMENTED HERE ]   
             * 
             *     Default options is created in runtime, a merge with it will get things right
             *
             *    [ DRAWBACK OF THIS SOLUTION ]
             * 
             *    Must point out, if there is an option name change as mentioned above, though the merge will get 
             *    theme run correctly on the new name option, old option value will be lost, write a special value assignment in upgrade codes may help, but 
             *    i don't think its a good idea and we need add other codes to ensure the old option is exactly from an older version of the theme, i guess 
             *    we'd best to avoid an option name change. Anyway, it is theme-relevant, not a core-relevant problem, i may think too much here, hehe.
             *    
             *    [ WARNING ]
             *
             *    Another important thing is this is a recursively merge, so we should avoid to put some default numeric entries (featured_posts, testimonial_records, and so on) 
             *    into the default options, the reason is ( all thinking below is around numeric entries ) :
             *    1. Apparently we should not make the logic appending, as default values are supposed to be overwrited by saved values
             *    2. But if we make the logic overwriting, when the number of default numeric entries is larger than saved ones, which means 
             *        part of default values will be merged to the final options which will still cause incorrect theme behaviors, like a default featured post appears in the slider, a default testimonial
             *        appears in the testimonial widget/list, and so on
             *    3. Even if we make sure put only one default entry per option in the default options, we still cannot use the overwriting log, as there may be some new options 
             *        in the numeric entry, overwrite it will lost the new option.  ( is this true? i'd better think a litter more on this point <-- to future me )
             */            
            $this->o = iwak_array_merge(iwak_default_options(), iwak_get_options());
            
            // Since WP 3.0
            if( WPVERSION >= 30 ) {
                add_theme_support( 'menus' );
                add_filter('nav_menu_css_class', array(&$this, 'nav_menu_css_class'));
                add_filter('wp_nav_menu_args', array(&$this, 'reset_global_vars'));
            }
            
        }
        
        function admin_head() {
            echo '<link href="'.ADMIN_URL.'/style.css" rel="stylesheet" type="text/css" />';
            echo '<script src="'.ADMIN_URL.'/js/widgets.js" type="text/javascript"></script>';
        }
        
        function ajax_callback() {
        
            if(!isset($_POST['type']))
                return;
                
            switch($_POST['type']) {
                case 'upload': 
                    $clickedID = $_POST['data']; // Acts as the name
                    $filename = $_FILES[$clickedID];
                    $override['test_form'] = false;
                    $override['action'] = 'wp_handle_upload';    
                    $uploaded_file = wp_handle_upload($filename,$override);
                     
                    // the Response
                    if(!empty($uploaded_file['error'])) {
                        echo 'Upload Error: ' . $uploaded_file['error'];
                    } else { 
                        echo $uploaded_file['url']; 
                        die;
                    } 
                    break;
                    
                case 'contact':
                    $this->sendmail();
                    exit;
                    
                case 'contact_support':
                    $this->contact_support();
                    exit;
                    
                case 'update_option':
                    $this->update_option();
                    exit;
                
            }
        }
        
        function ajax_callback_nopriv() {
        
            if(!isset($_POST['type']))
                return;
                
            switch($_POST['type']) {
                    
                case 'contact':
                    $this->sendmail();
                    exit;
                
            }
        }
        
        function contact_support() {

            $to = 'iwakthemes@gmail.com';
           
            $name = trim($_POST['cfname']);
            $email = $_POST['cfemail'];
            $website = $_POST['cfwebsite'];
            $describe = $_POST['cfdescribe'];
            $message = $_POST['cfmessage'];
            $category = $_POST['cfcategory'];

            $body .= $name ? '<em>name: </em>'. $name. '<br/>' : '';
            $body .= $describe ? '<em>describe: </em>'. $describe. '<br/>' : '';
            $body .= $email ? '<em>email: </em>'. $email. '<br/>' : '';
            $body .= $website ? '<em>website: </em>'. $website. '<br/>' : '';
            $body .= '<em>theme: </em>'. THEME_NAME. '<br/><em>version: </em>'. THEME_VERSION. '<br/><em>framework: </em>'. FRAMEWORK_VERSION. '<br/><br/>---------<br/><br/>';
            $body .= $message. '<br/><br/>---------<br/><br/>You can reply to this email to respond.';

            $subject = '['. $category. '] Message sent via iwakthemes backend from '. $name;
            //$body = nl2br($body);
            
            //$admin = get_userdata(1);
            //$to = $admin->user_email; // Replace this with your own email address
            //$site_owners_name = $admin->display_name; // replace with your name
            
            if (strlen($name) < 2) {
                $error .= '1';
                $this->invalid_inputs['name'] = $name;                
            } else
                $error .= '0';
            
            if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
                $error .= '1';
                $this->invalid_inputs['email'] = $email;                
            } else
                $error .= '0';
            
            if (strlen($message) < 3) {
                $error .= '1';
                $this->invalid_inputs['message'] = $message;                
            } else
                $error .= '0';
            
            if ($error == '000') {
                require_once(TEMPLATEPATH. '/includes/class.phpmailer.php');
                $mail = new PHPMailer();

                //$mail->SetFrom($email, $name);
                $mail->From = $email;
                $mail->FromName = $name;
                $mail->Subject = $subject;
                $mail->AddAddress($to);
                //$mail->Body = $body;
                $mail->MsgHTML($body);
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
                
                /*/GMAIL STUFF
                $mail->IsSMTP(); // enable SMTP
                $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
                $mail->SMTPAuth = true;  // authentication enabled
                $mail->Host = 'ssl://smtp.gmail.com:465';
                //$mail->Port = 587;
                $mail->Username = "username"; // SMTP username
                $mail->Password = "password"; // SMTP password
                */
                
                // $mail->ErrorInfo will be printed if anything is wrong
                if($mail->Send())
                    $this->email_sent = true;
                        
            } # end if no error
            else {
                echo $error;
            } # end if there was an error sending
        
        }
        
        function update_option() {
            if(!isset($_POST['option_name']) || !isset($_POST['data']))
                return;
            
            $data = get_option($_POST['option_name']);
            $data = array_merge((array)$data, $_POST['data']);
            update_option($_POST['option_name'], $data);
        }
        
        function reset_global_vars($args) {
            global $first;
            
            $first = 1;
            
            return $args;
        }
        
        function nav_menu_css_class($class) {
            global $first;
                
            if($first) {
                $class = array_merge($class, array(' first'));
                $first = 0;
            }
                
            return $class;
        }
        
         /** 
         * A pagination function 
         * @param integer $range: The range of the slider, works best with even numbers 
         * Used WP functions: 
         * get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4 
         * previous_posts_link(' « '); - returns the Previous page link 
         * next_posts_link(' » '); - returns the Next page link 
         * Coded by Wordpress, Improved by Robert Basic (http://robertbasic.com)
         */  
        function get_pagination($range = 4, $before = '', $after = ''){  
          // $paged - number of the current page  
          global $paged, $wp_query;  
          // How much pages do we have?  
          if ( !$max_page ) {  
            $max_page = $wp_query->max_num_pages;  
          }  
          // We need the pagination only if there are more than 1 page  
          if($max_page > 1){  
            echo $before;
            
            if(!$paged){  
              $paged = 1;  
            }  
            // On the first page, don't put the First page link  
            if($paged != 1){  
              echo "<a class='first' href=" . get_pagenum_link(1) . "> ". __('First', THEME_NAME). " </a>";  
            }  
            // To the previous page  
            previous_posts_link(' « ');  
            // We need the sliding effect only if there are more pages than is the sliding range  
            if($max_page > $range){  
              // When closer to the beginning  
              if($paged < $range){  
                for($i = 1; $i <= ($range + 1); $i++){  
                  echo "<a href='" . get_pagenum_link($i) ."'";  
                  if($i==$paged) echo "class='current'";  
                  echo ">$i</a>";  
                }  
              }  
              // When closer to the end  
              elseif($paged >= ($max_page - ceil(($range/2)))){  
                for($i = $max_page - $range; $i <= $max_page; $i++){  
                  echo "<a href='" . get_pagenum_link($i) ."'";  
                  if($i==$paged) echo "class='current'";  
                  echo ">$i</a>";  
                }  
              }  
              // Somewhere in the middle  
              elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){  
                for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){  
                  echo "<a href='" . get_pagenum_link($i) ."'";  
                  if($i==$paged) echo "class='current'";  
                  echo ">$i</a>";  
                }  
              }  
            }  
            // Less pages than the range, no sliding effect needed  
            else{  
              for($i = 1; $i <= $max_page; $i++){  
                echo "<a href='" . get_pagenum_link($i) ."'";  
                if($i==$paged) echo "class='current'";  
                echo ">$i</a>";  
              }  
            }  
            // Next page  
            next_posts_link(' » ');  
            // On the last page, don't put the Last page link  
            if($paged != $max_page){  
              echo " <a class='last' href=" . get_pagenum_link($max_page) . "> ". __('Last', THEME_NAME). " </a>";  
            }  
            
            echo $after;
          }  
        }

        function get_post_image_url($size = 'full') {

            $post = $GLOBALS['post'];
            if($post->post_type == 'attachment') {
                return wp_get_attachment_url($post->ID);
            }
                
            // Get Thumbnail
            if( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
                // Retrieve the post thumbnail
                $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                $src = $src[0];
                
            /*} elseif( $this->o['general']['auto_thumbnail'] && $start = strpos($post->post_content, '<img') !== false ) {
            
                // Auto generate thumbnail by the first image in the post
                $c = $post->post_content;
                $start = strpos( $c, 'src', $start );
                $start = strpos( $c, "\"", $start ) + 1;
                $end = strpos( $c, "\"", $start );
                $src = substr($c, $start, $end - $start );
                */
            //} elseif( $this->o['general']['auto_thumbnail'] && $start = strpos($post->post_content, '[gallery') !== false ){
            } elseif( $this->o['general']['auto_thumbnail'] ) {
            
                // Auto generate thumbnail by the first image in attachements
                $args = array(
                    'post_type' => 'attachment',
                    'numberposts' => 1,
                    'post_status' => 'any',
                    'post_parent' => $post->ID,
                    'post_mime_type' => 'image',
                    'nopaging' => true,
                    //'orderby' => 'menu_order',
                );
                
                $attachments = & get_children($args);
                if ( !empty($attachments) ) {
                    $attachment = array_shift($attachments);
                    $src = wp_get_attachment_image_src($attachment->ID, 'full');
                    $src = $src[0];
                }
            }
            
            if( $src && $size != 'full' )
                $src = $this->get_thumbnail_url($src, $size);
                
            return $src;
        }

        function get_post_image($size = 'full', $attr = '') {
            $src = $this->get_post_image_url($size);

            $class = is_array($size) ? 'thumbnail thumbnail-fixed' : "thumbnail thumbnail-$size";
            
            $default_attr = array(
                'src'    => $src,
                'class'    => $class,
            );
            $attr = wp_parse_args($attr, $default_attr);
            $attr = apply_filters( 'iwak_post_image_attributes', $attr );
            $attr = array_map( 'esc_attr', $attr );
            
            $html = '<img ';
            foreach ( $attr as $name => $value ) {
                $html .= " $name=" . '"' . $value . '"';
            }
            $html .= ' />';

            return $html;
        }
        
        /* - If thumbable, replace image_url with thumb_url
         * otherwise, return back image_url without any change
         * - thumb engine: timthumb.php, it only accept LOCAL GIF/JPG/PNG images
         * - Returns: thumb_url if thumbable, otherwise source image url
         */
        function get_thumbnail_url($image_url, $size) {
        
            if(empty($image_url))
                return;
                
            $default_sizes = array(
                'small' => array('width'=>75, 'height'=>75),
                'medium' => array('width'=>112, 'height'=>112),
                'large' => array('width'=>300, 'height'=>190),
            );
            
            $sizes = apply_filters( 'iwak_image_sizes', $default_sizes );
            $quality = $this->o['general']['image_quality'] ? intval($this->o['general']['image_quality']) : 90;
            
            if( is_array($size) ) {
            
                $thumb_width = abs((int)$size[0]);
                $thumb_height = abs((int)$size[1]);
                
            } else {
                
                if(preg_match('/^(\d+(.\d+)?):(\d+)$/', $size, $matches)) {
                    $thumb_width = THUMB_WIDTH;
                    $thumb_height = intval($thumb_width * $matches[3] / $matches[1]);
                    
                } elseif(preg_match('/^(\d+)x(\d+)$/', $size, $matches)) {
                    $thumb_width = $matches[1];
                    $thumb_height = $matches[2];
                    
                } else {
                    if(!array_key_exists($size, $sizes))
                        $size = 'large';
                    $thumb_width = $sizes[$size]['width'];
                    $thumb_height = $sizes[$size]['height'];
                }
            }
            
            preg_match('/(https?:\/\/)?([^\/]+)/i', $image_url, $matches);
            $img_domain = $matches[0];            //echo $img_domain.'hahaha';//.$matches[3];

            preg_match('/(https?:\/\/)?(www\.)?([^\/]+)/i', HOME_URL, $matches);
            
            // if multi site && image on local
            global $blog_id;
            if( function_exists('is_multisite') && is_multisite() && strpos($img_domain, $matches[3]) !== false && isset($blog_id) && $blog_id > 0 ) {
                $paths = explode('/files/', $image_url);
                $image_url = get_current_site()->path. '/wp-content/blogs.dir/'. $blog_id. '/files/'. $paths[1];
            }
            
            // if image on remote and local sever doesn't allow remote fopen
            if( strpos($img_domain, $matches[3]) === false && !ini_get('allow_url_fopen') )
                return $image_url;
            
            return THEME_URL.'/core/thumb.php?src='.$image_url.'&w='.$thumb_width.'&h='.$thumb_height.'&zc=1&q='. $quality;
            
        }
        
        /* $src (string) - URL of the source image
            $size (array or 'small', 'middle' and 'large') - expected size of the destination thumbnail
        */
        function the_thumbnail($src, $size) {
            echo $this->get_the_thumbnail($src, $size);
        }
        
        function get_the_thumbnail($src, $size) {
            $class = ( is_string($size) && stripos($size, 'x') ) ? 'icon' : 'thumbnail';
            return '<img class="'. $class. '" src="'. $this->get_thumbnail_url($src, $size).  '" >';
        }
        
        /* Deprecated as it was added into WP Core since WP 2.6
            When the current post has a tag. Must be used inside The Loop. */
        function has_tag($tag, $taxonomy = 'post_tag') {
            $_post =& $GLOBALS['post'];
        
            if ( !$_post )
                return false;
        
            $r = is_object_in_term( $_post->ID, $taxonomy, $tag );
            if ( is_wp_error( $r ) )
                return false;
            return $r;
        }

        function the_post_link($text, $attr = '') {
            echo $this->get_the_post_link($text, $attr);
        }
        
        function get_the_post_link($text, $attr = '') {
            $post = $GLOBALS['post'];
            
            $default_attr = array(
                'href'    => get_permalink($post),
                'title'    => __('Permanent Link to ', THEME_NAME). get_the_title($post),
                'class'    => 'permalink',
                //'ref'    => 'bookmark',
            );
            
            $attr = wp_parse_args($attr, $default_attr);
            $attr = apply_filters( 'iwak_post_link_attributes', $attr );
            $attr = array_map( 'esc_attr', $attr );
            
            $html = '<a ';
            foreach ( $attr as $name => $value ) {
                $html .= " $name=" . '"' . $value . '"';
            }
            $html .= ' >'. $text. '</a>';

            return $html;
        }

        function the_content($args = array()) {
            global $post;
            
            $defaults = array('excerpt'=>false, 'auto_excerpt'=>true, 'auto_content'=>true, 'content_length'=>500, 
            'more_link'=>true, 'more_text'=>__('Read More', THEME_NAME), 'remove_media'=>true, 'auto_formatting'=>'on');
            
            $settings = array('excerpt'=>$this->o['blog']['display_excerpt'], 'auto_excerpt'=>$this->o['blog']['auto_excerpt'], 
            'auto_content'=>$this->o['blog']['auto_content'], 'content_length'=>$this->o['blog']['content_length'], 'remove_media'=>$this->o['blog']['remove_media']);

            // Both array_merge or wp_parse_args treat 'null' as a valid value, while we need persist default value if an option is 'null' (not exist)
            foreach($defaults as $index=>$value)
                if(!isset($settings[$index]) || $settings[$index] == null)
                    $settings[$index] = $value;
                    
            $args = wp_parse_args( $args, $settings );

            $args = (object)$args;
            $ending = $args->more_text ? $this->get_the_post_link($args->more_text, array('class'=>'more-link')) : '';

            $auto_formatting = get_post_meta($post->ID, 'auto_formatting', true);
            if(empty($auto_formatting))
                $auto_formatting = $args->auto_formatting;
                
            if($auto_formatting == 'off') {
                remove_filter('the_content', 'wpautop');
                remove_filter('the_content', 'wptexturize');
            }

            if(is_singular() || $args->full_content)
                the_content();
            else
                if( $args->excerpt && ($args->auto_excerpt || $post->post_excerpt) ) {
                    $c = apply_filters('the_excerpt', get_the_excerpt());
                    $c = str_replace('[...]', '', $c);
                    echo preg_replace('/<\/p>\s*$/is', $ending. '</p>', $c);
                }
                elseif( $args->auto_content ) {
                    if( !$length = get_post_meta($post->ID, 'content length', true))
                        $length = $args->content_length;
                    $c = truncate(get_the_content(''), $length, $ending, $args->remove_media);
                    echo apply_filters('the_content', $c);
                }
                else {
                    global $more;
                    $more = 0;
                    the_content($args->more_text);
                }
            
            if($auto_formatting == 'off') {
                add_filter('the_content', 'wpautop');
                add_filter('the_content', 'wptexturize');
            }
        }    
        
        function menubar($name, $home = false) {
            
            $menu_class = 'menu';
            $menu_id = $name. '-nav';
            $menu = $this->o['general']['menus'][$name]; 
            $depth = isset($menu['depth']) ? $menu['depth'] : 2;
            if( empty($menu) )
                return;
                
            switch($menu['type']) {
            
                case 'category':
                
                    $categories = wp_list_categories('title_li=&echo=0&depth='. $depth);
                    if( strpos($categories, "No categories") !== false ) $categories = '';
                    $output = $categories;
                    break;
                    
                case 'page':
                
                    $output = wp_list_pages('title_li=&sort_column=menu_order&echo=0&depth='. $depth);
                    break;
                    
                case 'both':
                
                    $output = wp_list_categories('title_li=&echo=0&depth='. $depth) . wp_list_pages('title_li=&sort_column=menu_order&echo=0&depth='. $depth);
                    break;
                    
                case 'custom':
                
                    if( WPVERSION >= 30 ) { 
                        if($menu['entity']):
                            $args = array('container'=>null, 'menu_class'=>$menu_class, 'menu_id'=>$menu_id, 'menu'=>$menu['entity'], 'depth'=>$depth);
                            $args = apply_filters( 'iwak_nav_menu_args', $args );
                            return wp_nav_menu($args);
                        else:    
                            echo "<ul id='$menu_id' class='$menu_class'>". wp_list_pages('title_li=&sort_column=menu_order&echo=0&depth=2'. $depth). '</ul>';return;
                        endif;
                    }
                    
                    if($depth > 0)
                        $depth -- ;
                        
                    foreach( explode(';', $menu['entity']) as $menu_item) {
                        
                        preg_match('/\.(.+),(.+)$/', $menu_item, $match);
                        
                        if($menu_item[0] == 'C') {
                            $cat_name = get_cat_name($match[1]);
                            $menu_class = is_category($cat_name) ? " class='current-menu-item'" : '';
                            $cat_link = get_category_link($match[1]);
                            $children = wp_list_categories('title_li=&echo=0&depth='. $depth. '&child_of='.$match[1]);
                            if(strtolower($children) == '<li>no categories</li>')
                                $children = '';
                            else
                                $children = '<ul class="children">'. $children. '</ul>';
                            $output .= "<li $menu_class><a class='depth-0' title='View all posts filed under $cat_name' href='$cat_link'>$cat_name</a>$children</li>";
                        } else if($menu_item[0] == 'P') {
                            $page_name = get_page($match[1])->post_title;
                            $menu_class = is_page($page_name) ? " class='current-menu-item'" : '';
                            $page_link = get_page_link($match[1]);
                            $children = wp_list_pages('title_li=&echo=0&depth='. $depth. '&child_of='.$match[1]);
                            if(!empty($children))
                                $children = '<ul class="children">'. $children. '</ul>';
                            $output .= "<li $menu_class><a class='depth-0' title='$page_name' href='$page_link'>$page_name</a>$children</li>";
                        }
                    }
                    break;
                    
                default:
                
                    break;
                    
            }
            
            $menu_class = is_home() ? " class='first current-menu-item'" : " class='first'";

            if($home) $output = '<li'. $menu_class. '><a class="depth-0" href= "'. HOME_URL . '/">' . __('Home', THEME_NAME) . '</a></li>' . $output;
            echo '<ul class="'. $class. '">' . $output . '</ul>';
        }
    }
}
?>