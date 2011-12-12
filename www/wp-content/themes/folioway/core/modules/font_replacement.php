<?php

class iWaK_Font_Replacement {

    function __construct() {
            
        //extract($args);
        add_action('wp_head', array(&$this, 'print_styles'));
    }
    
    function print_styles() {
        global $iwak;
            
        if($iwak->o['general']['cufon']) {
            $include .= '<script type="text/javascript" src="'.THEME_URL.'/js/cufon-yui.js"></script>';
            $include .= '<script type="text/javascript" src="'. $iwak->o['general']['cufon_font']. '"></script>';
        }
            
            if( isset($_GET['font']) ) { // handle http request
                $iwak->o['general']['custom_font_code'] = $_GET['font'];
            }

        $text_font = empty($iwak->o['general']['custom_font_code']) ? $iwak->o['general']['text_font'] : $iwak->o['general']['custom_font_code'];
        if($text_font != 'system') {
            $font_family = explode(':', $text_font);
            $font_family = str_replace('+', ' ', $font_family[0]);
            $include  .= "<link href='http://fonts.googleapis.com/css?family=$text_font' rel='stylesheet' type='text/css'>";
            $css .= "body, input, textarea { font-family:  '$font_family', Arial, Helvetica, 'Liberation Sans', FreeSans, sans-serif; }\n";
        }
        
        echo $include . "<style text='text/css'>\n". $css. "</style>\n";
    }
}

new iWaK_Font_Replacement();