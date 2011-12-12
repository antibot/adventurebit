<?php

class iWaK_Testimonial_List {
    var $property;
    var $colnum;
    var $option_builders;
    
    function __construct($args) {
        $this->colnum = 4;
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
        
        $ikd['testimonial_records'][] = array('author'=>'Jennifer Lopez', 'content'=>'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.');
        $ikd['testimonial_records'][] = array('author'=>'Bill Gates', 'content'=>'Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi.');
    }
        
    function build() {
        $table = $this->property;
        $builders = empty($table['builders']) ? OPTION_BUILDERS : $table['builders'];
        if(empty($builders))
            return;
        $this->option_builders = new $builders();
        
        $theme_options = iwak_get_options();
        $testimonials = $theme_options['testimonial_records'];
        if(empty($testimonials))
            $testimonials = array();

        $default_value = array('author'=>'His name',
                                    'content'=>'What he/she says',
                                    'site_name'=>'',
                                    'site_url'=>'',
                                    'author_is'=>'',
                                    'author_image'=>'',
                                );
                        
        foreach($testimonials as $index=>$testimonial) {
            $table['index'] = $index;
            $testimonial = array_merge($default_value, $testimonial);
            $output .= $this->create_list_item($table, $testimonial);
        }
        
        // Create a default entry for convenience of adding new
        $default = $this->property;
        $default['class'] .= ' default-item draggable-item hidden';
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
    function create_list_item($table, $testimonial_record = '') {
        $class = empty($table['class']) ? 'list-item' : 'class="list-item '. $table['class']. '"';
        $colspans = array(3, 5, 1, 1);
                            
        ob_start();
        echo "<table $class><tbody>";
        $i = $col = 0;
        $alt ='';
        foreach($table['options'] as $option) {
            if( !isset($option['depth']) || $option['depth'] < 0) 
                $option['depth'] = 0;
                        
            if(preg_match('/^testimonial_record(\[.+\])+$/i', $option['name'])) {
                // Get option value
                if(!empty($testimonial_record))
                    $value = eval('return $'. $option['name'] . ';');
                    
                // Convert option name, for convenience of submission, to testimonials[index][name]
                if( isset($table['index']) )
                    $option['name'] = str_ireplace('testimonial_record', 'testimonial_records['. $table['index']. ']', $option['name']);
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