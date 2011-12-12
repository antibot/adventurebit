<?php

class iWaK_Filter_List {
    var $property;
    var $colnum;
    var $option_builders;
    
    function __construct($args) {
                
        $this->colnum = 4;
        $this->property = $args;
        // Before i figure out a solution, default entries must be forbidden, reason is written in   class.template.php   ->   function __construct()
        //$this->create_defaults();
    }
    
    function __get($name) {
        if(isset($this->property[$name]))
            return $this->property[$name];
        else
            return;
    }
    
    function create_defaults() {
        global $ikd;
        
        $ikd['filters'][] = array('exclude'=>0, 'type'=>'sticky');
        if($id = term_exists('featured', 'category')) {
            if(is_array($id))
                $cat_id = $id['term_id'];
            $ikd['filters'][] = array('exclude'=>0, 'type'=>'category', 'id'=>$cat_id);
        }
        if($id = term_exists('featured', 'tag')) {
            if(is_array($id))
                $tag_id = $id['term_id'];
            $ikd['filters'][] = array('exclude'=>0, 'type'=>'tag', 'id'=>$tag_id);
        }
    }

    function build() {
        $table = $this->property;
        $builders = empty($table['builders']) ? OPTION_BUILDERS : $table['builders'];
        if(empty($builders))
            return;
        $this->option_builders = new $builders();
        
        $theme_options = iwak_get_options();
        $featured_filters = $theme_options['filters'];
        if(empty($featured_filters))
            $featured_filters = array();
        
        // Create lists for switching
        $selectbox = array('type'=>'select',
                            'class'=>'hidden category-selectbox',
                            'items'=>iwak_get_list('category')
                        );
        $output = $this->option_builders->select_builder($selectbox);
        
        $selectbox = array('type'=>'select',
                            'class'=>'hidden tag-selectbox',
                            'items'=>iwak_get_list('tag')
                        );
        $output .= $this->option_builders->select_builder($selectbox);

        $selectbox = array('type'=>'select',
                            'class'=>'hidden sticky-selectbox',
                            'items'=>__('All Sticky Posts', THEME_NAME)
                        );
        $output .= $this->option_builders->select_builder($selectbox);
        
        /*$args=array(
          'public'   => true,
          '_builtin' => false
        ); 
        $return = 'objects'; // or names
        $operator = 'and'; // 'and' or 'or'
        $taxonomies=get_taxonomies($args,$return,$operator); 
        if  ($taxonomies) {
          foreach ($taxonomies  as $taxonomy ) {
            $selectbox = array('type'=>'select',
                                'class'=>'hidden '. $tax->name. '-selectbox',
                                'items'=>iwak_get_list($tax->id),
                            );
            $output .= $this->option_builders->select_builder($selectbox);
          }
        }*/

        $output .= '<div class="content">'. $this->content. '</div>';
        foreach($featured_filters as $index=>$featured_filter) {
            $table['index'] = $index;
            $output .= $this->create_list_item($table, $featured_filter);
        }
        
        // Create a default list item for convenience of add featured post
        $default = $this->property;
        $default['class'] .= ' default-item draggable-item hidden';
        if(isset($default['index']))
            unset($default['index']);
        $default_value = array('exclude'=>0,
                                    'type'=>'category',
                                );
        $output .= $this->create_list_item($default, $default_value);

        $output .= $this->create_function_bar();
        return $output;
    }
    
    function create_function_bar() {
        $o  = '<div class="iwak-console"><span class="iwak-add button">Add An Item</span></div>';
        return $o;
    }
    
    function create_list_item($table, $filter = '') {
        $class = empty($table['class']) ? 'list-item' : 'class="list-item '. $table['class']. '"';
        
        if(empty($filter['type']))
            $filter['type'] = 'category';
            
        if($filter['type'] == 'sticky')
            $selectbox_items = array(0=>__('All Sticky Posts', THEME_NAME));
        else
            $selectbox_items = iwak_get_list($filter['type']);
                
        ob_start();
        echo "<table $class><tbody>";
        $i = $col = 0;
        $alt ='';
        foreach($table['options'] as $option) {
            if( !isset($option['depth']) || $option['depth'] < 0) 
                $option['depth'] = 0;
            
            // Produce 'title' selectbox options per type (post, page) of featured post
            if($option['name'] == 'filter[id]')
                $option['items'] = $selectbox_items;
            elseif($option['name'] == 'filter[exclude]')
                $prebody = 1;
            
            if(preg_match('/^filter(\[.+\])+$/i', $option['name'])) {
                // Get option value
                if(!empty($filter))
                    $value = eval('return $'. $option['name'] . ';');
                    
                // Convert option name, for convenience of submission, to featured_posts[index][name]
                if( isset($table['index']) )
                    $option['name'] = str_ireplace('filter', 'filters['. $table['index']. ']', $option['name']);
            }
            
            if($prebody) {
                $c = $value ? 'icon icon-exclude' : 'icon';
                $option['body'] = '<span class="'. $c. '"></span><span class="iwak-toggle"></span>';
                unset($prebody);
            }
            
            $body_builder = $option['type']. '_builder';
            $option['body'] .= $this->option_builders->$body_builder($option, $value);
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