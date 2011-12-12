<?php

class iWaK_Featured_List {
    var $property;
    var $colnum;
    var $option_builders;
    
    function __construct($args) {
        $this->colnum = 5;
        $this->property = $args;
        // Before i figure out a solution, default entries must be forbidden, reason is written in   itools.php   ->   function iwak_get_options()
    }
    
    function __get($name) {
        if(isset($this->property[$name]))
            return $this->property[$name];
        else
            return;
    }
    
    function print_scripts_backup() {
        // Add featured post ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.iwak-add').click(function() {
                    var default_item  = jQuery(this).parent().prev('.default-item');
                    
                    // Fetch index of the last item and plus 1
                    var index = 0;
                    var last_item = default_item.prev();
                    var reg = /([^\[]+)\[(\d+)\]/;
                    var matches = reg.exec(jQuery('select:first', last_item).attr('id'));
                    if(matches) {
                        index = ++ matches[2];
                        var newstr = matches[1] + '[' + index + ']';
                    }
                    
                    // Generate the new item
                    reg = /([^\[]+)\[.+\]/;
                    matches = reg.exec(jQuery('select:first', default_item).attr('id'));
                    if(!newstr) {
                        newstr = matches[1] + 's[0]';
                    }
                    var pattern = new RegExp(RegExp.quote(matches[1]), 'ig');
                    var new_item = default_item.clone().removeClass('default-item').removeClass('hidden');
                    new_item.html(new_item.html().replace(pattern, newstr));
                    default_item.before(new_item);
                    
                    // Replace selectbox and make this new item draggable
                    jQuery('.jquery-selectbox', new_item).unselectbox();
                    jQuery('select:not(.hidden)', new_item).selectbox();
                    new_item.dragitem();
                });
            });
        </script>
        
        <?php
    }
        
    function build() {
        $table = $this->property;
        $builders = empty($table['builders']) ? OPTION_BUILDERS : $table['builders'];
        if(empty($builders))
            return;
        $this->option_builders = new $builders();
        
        $theme_options = iwak_get_options();
        $featured_posts = iwak_get_list('featured_posts');
        if(empty($featured_posts))
            $featured_posts = array();

        $default_value = array_merge( array('type'=>'post', 'status'=>'auto'), $theme_options['featured_defaults'] );
        /*$default_value = array('type'=>'post',
                                    'status'=>'auto',
                                    'display_content'=>$theme_options['featured']['display_content'],
                                    'display_comments_link'=>$theme_options['general']['display_comments_link'],
                                    'buttons'=>$theme_options['featured']['buttons'],
                                    'animation'=>$theme_options['featured']['animation']
                                );
        */
        
        $post_types = get_post_types();
        foreach( $post_types as $post_type) {
            $selectbox = array('type'=>'select',
                                'class'=>'hidden '. $post_type. '-selectbox',
                                'items'=>iwak_get_list($post_type)
                            );
            $output .= $this->option_builders->select_builder($selectbox);
        }
                
        foreach($featured_posts as $index=>$featured_post) {
            $table['index'] = $index;
            $featured_post = array_merge($default_value, $featured_post);
            $output .= $this->create_list_item($table, $featured_post);
        }
        
        // Create a default featured post for convenience of add new
        $default = $this->property;
        $default['class'] .= ' default-item draggable-item hidden';
        $default_value['status'] = 'static';
        if(isset($default['index']))
            unset($default['index']);
        $output .= $this->create_list_item($default, $default_value);

        $output .= $this->create_function_bar();
        return $output;
    }
     
    function create_function_bar() {
        $o  = '<div class="iwak-console"><span class="iwak-add button">Add An Item</span></div>';
        return $o;
    }
    
    function create_list_item($table, $featured_post = '') {
        $class = empty($table['class']) ? 'list-item' : 'class="list-item '. $table['class']. '"';
        $colspans = array(1, 6, 1, 1, 1);
        
        if(empty($featured_post['type']))
            $featured_post['type'] = 'post';
            
        $selectbox_items = iwak_get_list($featured_post['type']);
        
        ob_start();
        echo "<table $class><tbody>";
        $i = $col = 0;
        $alt ='';
        foreach($table['options'] as $option) {
            if( !isset($option['depth']) || $option['depth'] < 0) 
                $option['depth'] = 0;
            
            // Produce 'title' selectbox options per type (post, page) of featured post
            if($option['name'] == 'featured_post[id]')
                $option['items'] = $selectbox_items;
            
            if(preg_match('/^featured_post(\[.+\])+$/i', $option['name'])) {
                // Get option value
                if(!empty($featured_post))
                    $value = eval('return $'. $option['name'] . ';');
                    
                // Convert option name, for convenience of submission, to featured_posts[index][name]
                if( isset($table['index']) )
                    $option['name'] = str_ireplace('featured_post', 'featured_posts['. $table['index']. ']', $option['name']);
            }
            
            // Extract option items
            if(is_string($option['items']) && preg_match('/^\{(\w+)\}$/', $option['items'], $matches)) :
            
            switch($option['items']) {
                case '{posts}':
                    $option['items'] = iwak_get_list('post');
                    break;
                case '{pages}':
                    $option['items'] = iwak_get_list('page');
                    break;
                case '{post_types}':
                    $option['items'] = iwak_get_list('post_types');
                    break;
                case '{categories}':
                    $option['items'] = array(-1=>'&nbsp;') + iwak_get_list('category');
                    //$option['items'][-1] = '&nbsp;';
                    break;
                default:
                    $option['items'] = iwak_get_list($matches[1]);
                    break;
            }
            
            endif;
            
            $body_builder = $option['type']. '_builder';
            $option['body'] = $this->option_builders->$body_builder($option, $value);
            $title = empty($option['title']) ? '' : '<th>'. $option['title']. '</th>';
            $class = 'depth-'. $option['depth'];
            $class .= ($i%2 == 0) ? ' even' : ' odd';
            $desc = empty($option['desc']) ? '' : '<p class="desc">'. $option['desc']. '</p>';
            $colspan = $this->colnum - $option['depth'] - 1;
            
            if($option['alt'])
                $alt = empty($alt) ? 'alt' : '';
            
            if(!isset($last_option))
                echo '<tr class="main">'. $title. "<td colspan='2'><div class='inner'><div class='$class $alt column-$col column'>". $option['body']. $desc;
            elseif($option['depth'] > $last_option['depth'])
                echo "<table><tbody><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
            elseif($option['depth'] < $last_option['depth'])
                echo "</tbody></table></td></tr><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
            elseif($option['inline']) {
                $col ++;
                echo "</div><div class='$class $alt column-$col column'>". $option['body']. $desc;
            }
            else
                echo "</div></div></td></tr><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
                
            $last_option = $option;
            $i++;
        }
        
        for( $d = $last_option['depth']; $d >= 1; $d -- )
            echo '</tbody></table></td></tr>';
        echo '</tbody></table>';
        
        unset($last_option);
        return ob_get_clean();
    }
}

?>