<?php

class iWaK_Option_Builders {
    function select_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
        
        $id = $option['name'] ? ' id="'. $option['name']. '"' : '';
        $name = $option['name'] ? ' name="'. $option['name']. '"' : '';
        $class = $option['class'] ? ' class="'. $option['class']. '"' : ' class="regular-select"';
        $o =   '<select'. $id. $name. $class. '>';
        foreach((array)$option['items'] as $value=>$text) {
            $selected = $option_value == $value ? ' selected="selected"' : '';
            $o .= ' <option value="'. $value. '"'. $selected. '>'. $text. '</option>';
        }
        $o .= '</select>';
        return $o;
    }
    
    function text_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
            
        $class = $option['class'] ? ' class="'. $option['class']. '"' : 'class="regular-text"';
        $o = '<input name="'. $option['name']. '" id="'. $option['name']. '" value="'. $option_value. '" type="text"'. $class. ' />';
        return $o;
    }
     
    function textarea_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
            
        $class = $option['class'] ? ' class="'. $option['class']. '"' : 'class="regular-textarea"';
        $o = '<textarea name="'. $option['name']. '" id="'. $option['name']. '"'. $class. '>'. $option_value. '</textarea>';
        return $o;
    }
     
    function radio_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
            
        $class = $option['class'] ? ' class="'. $option['class']. '"' : 'class="regular-radio"';
        $o = '';
        foreach((array)$option['items'] as $value=>$text) {
            $checked = $option_value == $value ? ' checked="checked"' : '';
            $o .= '<label '. $class. '"><input name="'. $option['name']. '" id="'. $option['name']. '" value="'. $value. '" type="radio" class="radiobutton" '. $checked. '/>'. $text. '</label>';
        }
        return $o;
    }
    
    function upload_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
            
        if( strpos($option['name'], 'font') === false )
            $is_image = true;
            
        $button_class = 'button upload_button';
        $button_class .= $is_image ? ' image_upload_button' : ' font_upload_button';
        $background = empty($option['background']) ? '' : "<img src='$option[background]' />";
        $position = ( isset($option['position']) && is_array($option['position']) ) ? "style='position:absolute; left: {$option[position][0]}px; top: {$option[position][1]}px'" : "";
            
        $class = $option['class'] ? ' class="'. $option['class']. '"' : 'class="regular-text"';
        $o = '<input name="'. $option['name']. '" id="'. $option['name']. '" value="'. $option_value. '" type="text"'. $class. ' />';
        $o .= '<span id="upload-'. preg_replace('/\[([^\[\]]+)\]/', '-\1', $option['name']). '" class="'. $button_class. '">'. __('Change', THEME_NAME). '</span>';
        $o .= '<div class="status"></div>';
        if($is_image) $o .= "<div class='$option[name]-preview ipreview'><img $position class='inner' src='$option_value' />$background</div>";
        return $o;
    }
    
    function multi_builder($option) {
        return;
    }
    
    function icon_builder($option) {
        $class = $option['class'] ? ' class="'. $option['class']. '"' : 'class="icon"';
        $o = '<span '. $class. '></span>';
        return $o;
    }
    
    function button_builder($option, $option_value = null) {
        if(!isset($option_value))
            $option_value = $option['std'];
            
        $option['class'] = $option_value ? 'iwak-button iwak-button-status button-status-on '. $option['class'] : 'iwak-button iwak-button-status '. $option['class'];
        $class = ' class="'. $option['class']. '"';
        $o = '<span '. $class. '>'. $option['text']. '</span>';
        $option['class'] = 'hidden';
        $option['items'] = array(0, 1);
        $o .= $this->select_builder($option, $option_value);
        return $o;
    }
    
    function menu_builder($option, $option_value = null) {
        
        if(WPVERSION >= 30) {
            $menus = wp_get_nav_menus();
            if(!$menus)
            	return sprintf( __('No custom menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') );
            foreach($menus as $menu)
                $option['items'][$menu->name] = $menu->name;
            return $this->select_builder($option, $option_value);
        }
        
        $items = iwak_get_list('category');
        foreach ($items as $id=>$name)
            $category_items[] = 'C.'. $id . ',' . $name;
            
        $items = iwak_get_list('page');
        foreach ($items as $id=>$name)
            $page_items[] = 'P.'. $id . ',' . $name;

        if(!empty($option_value))
            $menu_items = explode(';', $option_value);

        if( !isset( $menu_items )) {
            foreach ($category_items as $menu_item) {
                $menu_item = explode(',', $menu_item);
                $categories_form .= sprintf('<span class="item cat-item" id="%s">%s</span>', $menu_item[0], $menu_item[1]);
            }
            foreach ($page_items as $menu_item) {
                $menu_item = explode(',', $menu_item);
                $pages_form .= sprintf('<span class="item page-item" id="%s">%s</span>', $menu_item[0], $menu_item[1]);
            }
                
        } else {
            foreach( $menu_items as $menu_item ) {
                if( !empty($menu_item) ) {
                    $item_type = strpos($menu_item, 'C') === 0 ? 'cat-item' : 'page-item';
                    $menu_item = explode(',', $menu_item);
                    $menus_form .= sprintf('<span class="item %s" id="%s">%s</span>', $item_type, $menu_item[0], $menu_item[1]);
                }
            }
            foreach( array_diff($category_items, $menu_items) as $not_menu_item ) {
                if( !empty($not_menu_item) ) {
                    $not_menu_item = explode(',', $not_menu_item);
                    $categories_form .= sprintf('<span class="item cat-item" id="%s">%s</span>', $not_menu_item[0], $not_menu_item[1]);
                }
            }
            foreach( array_diff($page_items, $menu_items) as $not_menu_item ) {
                if( !empty($not_menu_item) ) {
                    $not_menu_item = explode(',', $not_menu_item);
                    $pages_form .= sprintf('<span class="item page-item" id="%s">%s</span>', $not_menu_item[0], $not_menu_item[1]);
                }
            }
        }
                    
        $o = '<div class="menubar-editor">';
        
        // Current menus
        $o .= '<div class="menu-items">';
        $o .= $option['homelink'] ? '<span class="item-home" class="safe-item">'. __('Home', THEME_NAME). '</span>' : '';
        $o .= $menus_form;
        $o .= '</div>';
        
        // Available pages
        $o .= '<div class="page-items"><b class="safe-item">'. __('Pages: ', THEME_NAME). '</b>';
        $o .= $pages_form;
        $o .= '</div>';
        
        // Available categories
        $o .= '<div class="cat-items"><b class="safe-item">'. __('Categories: ', THEME_NAME). '</b>';
        $o .= $categories_form;
        $o .= '</div>';
        
        $o .= "<input type='hidden' name='{$option['name']}' id='{$option['name']}' value='{$option_value}' />";
        
        $o .= '<p class="item-meta" class="hidden">';
        $o .= '<a class="move-forward button" href="#move">'. __('Move Forward', THEME_NAME). '</a>';
        $o .= '<a class="move-backward button" href="#move">'. __('Move Backward', THEME_NAME). '</a>';
        $o .= '<a class="toggle-status button" href="#toggle">'. __('Include/Exclude', THEME_NAME). '</a>';
        $o .= '</p>';
        
        $o .= '</div>';
        return $o;
    }
}

?>