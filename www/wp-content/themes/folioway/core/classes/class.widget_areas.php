<?php

class iWaK_Widget_Areas {
    var $property;
    var $colnum;
    var $option_builders;
    
    function __construct($args) {
        $this->colnum = 3;
        $this->property = $args;
        // Before i figure out a solution, default entries must be forbidden, reason is written in   itools.php   ->   function iwak_get_options()
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
        
        $ikd['widget_areas'][] = array('name'=>'Custom Sidebar 1', 'description'=>'This is a custom sidebar for demonstration, you can find and manage all custom sidebars from Creations -> Wdiget Areas');
        $ikd['widget_areas'][] = array('name'=>'Custom Sidebar 2', 'description'=>'This is a custom sidebar for demonstration, you can find and manage all custom sidebars from Creations -> Wdiget Areas');
        $ikd['widget_areas'][] = array('name'=>'Custom Sidebar 3', 'description'=>'This is a custom sidebar for demonstration, you can find and manage all custom sidebars from Creations -> Wdiget Areas');
    }
    
    function build() {
        $table = $this->property;
        $builders = empty($table['builders']) ? OPTION_BUILDERS : $table['builders'];
        if(empty($builders))
            return;
        $this->option_builders = new $builders();
        
        $theme_options = iwak_get_options();
        $widget_areas = $theme_options['widget_areas'];
        if(empty($widget_areas))
            $widget_areas = array();

        $default_value = array('name'=>'Area Name',
                                    'description'=>'A brief description',
                                );
                        
        foreach($widget_areas as $index=>$widget_area) {
            $table['index'] = $index;
            $widget_area = array_merge($default_value, $widget_area);
            $output .= $this->create_list_item($table, $widget_area);
        }
        
        // Create a default entry for convenience of adding new
        $default = $this->property;
        $default['class'] .= ' default-item hidden';
        $default_value['status'] = 'approved';
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
    
    // Name of the second parameter should be the same to the array name in corresponding options file for read value 
    function create_list_item($table, $widget_area = '') {
        $class = empty($table['class']) ? 'list-item' : 'class="list-item '. $table['class']. '"';
        // Base of 10 columns
        $colspans = array(4, 5, 1);
                            
        ob_start();
        echo "<table $class><tbody>";
        $i = $col = 0;
        $alt ='';
        foreach($table['options'] as $option) {
            if( !isset($option['depth']) || $option['depth'] < 0) 
                $option['depth'] = 0;
                        
            if(preg_match('/^widget_area(\[.+\])+$/i', $option['name'])) {
                // Get option value
                if(!empty($widget_area))
                    $value = eval('return $'. $option['name'] . ';');
                    
                // Convert option name, for convenience of submission, to widget_areas[index][name]
                if( isset($table['index']) )
                    $option['name'] = str_ireplace('widget_area', 'widget_areas['. $table['index']. ']', $option['name']);
            }
            
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