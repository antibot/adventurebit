<?php

class iWaK_Option_Table {
    var $table;
    
    function __construct($table) {
        $this->table = $table;
        $this->create_defaults();
    }
    
    function __get($name) {
        if(isset($this->table[$name]))
            return $this->table[$name];
        else
            return;
    }
    
    function create_defaults() {
        global $ikd;
        
        foreach($this->options as $option) {
            if(isset($option['std']))
                if(preg_match('/^([^\[\]]+)(\[.+\])$/', $option['name'], $matches)) {
                    eval('$ikd[$matches[1]]'. $matches[2]. ' = $option[std];');
                } else
                    $ikd[$option['name']] = $option['std'];
             
            if($option['name'] == 'general[style]' && isset($option['items']))
                $ikd['general']['styles'] = array_keys($option['items']);
        }
                    
    }
    
    /* class based logic
     > ------------------------------------------------------
     > option = createOption($args);
     >
     > if depth increase
     >      anchor.push(last_option);
     >      last_option.add($option);
     > elseif depth decrease
     >      parent_option = anchor.pop();
     >      parent_option.add($option);
     > else depth equal
     >      parent_option.add($option);
     > endif
     >
     > last_option = option;
     */
     function build() {
        $table = $this->table;
        $builders = empty($table['builders']) ? OPTION_BUILDERS : $table['builders'];
        if(empty($builders))
            return;
        $builders = new $builders();
        
        if(empty($table['source']))
            $table['source'] = 'general';
        $options = iwak_get_options();
        $values = $options;//$options[$table['source']];

        ob_start();
        echo "<table><tbody>";
        $i = 0;
        $alt ='';
        foreach($table['options'] as $option) {
            if( !isset($option['depth']) || $option['depth'] < 0) 
                $option['depth'] = 0;
            
            // Get option's value
            if(preg_match('/^([^\[\]]+)(\[([^\[\]]+)\])(\[([^\[\]]+)\])?(\[([^\[\]]+)\])?/', $option['name'], $matches)) {
                $keys = array();
                foreach($matches as $index=>$match) {
                    if($index%2 == 0)
                        continue;
                    $keys[] = $match;
                }
                switch(count($keys)) {
                    case 1:
                        $value = $values[$keys[0]];
                        break;
                    case 2:
                        $value = $values[$keys[0]][$keys[1]];
                        break;
                    case 3:
                        $value = $values[$keys[0]][$keys[1]][$keys[2]];
                        break;
                    case 4:
                        $value = $values[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
                        break;
                    default:
                        // 4+ level isn't supported
                        break;
                }
            }
            else
                $value = $values[$option['name']];
                
            // Extract option items
            if(is_string($option['items']) && preg_match('/^\{(\w+)\}$/', $option['items'], $matches)) :
            
            switch($option['items']) {
                case '{posts}':
                    $option['items'] = iwak_get_list('post');
                    break;
                case '{pages}':
                    $option['items'] = iwak_get_list('page');
                    break;
                case '{categories}':
                    $option['items'] = array(-1=>'&nbsp;') + iwak_get_list('category');
                    //$option['items'][-1] = '&nbsp;';
                    break;
                case '{link_categories}':
                    $option['items'] = array(-1=>'&nbsp;') + iwak_get_list('link_categories');
                    //$option['items'][-1] = '&nbsp;';
                    break;
                default:
                    $option['items'] = iwak_get_list($matches[1]);
                    break;
            }
            
            endif;
                
            $body_builder = $option['type']. '_builder';
            $option['body'] = $builders->$body_builder($option, $value);
            $title = empty($option['title']) ? '' : '<th>'. $option['title']. '</th>';
            $class = 'depth-'. $option['depth'];
            $class .= ($i%2 == 0) ? ' even' : ' odd';
            $class .= ' row_'. $option['name'];
            $desc = empty($option['desc']) ? '' : '<p class="desc">'. $option['desc']. '</p>';
            
            if($option['alt'])
                $alt = empty($alt) ? 'alt' : '';
            
            if(!isset($last_option))
                echo "<tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
            elseif($option['depth'] > $last_option['depth'])
                echo "<table><tbody><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
            elseif($option['depth'] < $last_option['depth'])
                echo "</tbody></table></td></tr><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
            elseif($option['inline'])
                echo "</td><td class='$class $alt'>". $option['body']. $desc;
            else
                echo "</td></tr><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
                
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
