<?php global $iwak;
    $slider = TEMPLATEPATH. '/slider-'. $iwak->o['featured']['slider']. '.php';
    
    if( file_exists($slider) ) {
        include_once($slider);
    } else {
        include_once(TEMPLATEPATH. '/slider-custom.php');
    }
?>