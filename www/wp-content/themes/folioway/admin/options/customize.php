<?php

$options = array();

$options[] = array('name'=>'general[logo]', 
                            'title'=>__('Logo', THEME_NAME), 
                            'type'=>'upload', 
                            'std'=>THEME_URL. '/images/logo.png',
                        );

$options[] = array('name'=>'instant_position[logo]', 
                            'title'=>__(' '), 
                            'desc'=>__('This feature enable you to adjust logo position from frontend, it is admin only, but we still recommend you to turn off it in most time to avoid position change by accident ', THEME_NAME),
                            'text'=>__('Instant Position', THEME_NAME), 
                            'std'=>0,
                            'type'=>'button', 
                        );

$options[] = array('name'=>'general[intro]', 
                            'title'=>__('Header Intro', THEME_NAME), 
                            'type'=>'upload', 
                            'desc'=>__('Leave here empty for no introduction on header.', THEME_NAME),
                            'std'=>THEME_URL. '/images/intro.png',
                        );

$options[] = array('name'=>'instant_position[intro]', 
                            'title'=>__(' '), 
                            'desc'=>__('This feature enable you to adjust introduction image position from frontend, it is admin only, but we still recommend you to turn off it in most time to avoid position change by accident ', THEME_NAME),
                            'text'=>__('Instant Position', THEME_NAME), 
                            'std'=>0,
                            'type'=>'button', 
                        );

$options[] = array('name'=>'general[logosmall]', 
                            'title'=>__('Footer Logo', THEME_NAME), 
                            'type'=>'upload', 
                            'desc'=>__('Leave here empty for no logo on footer.', THEME_NAME),
                            'std'=>THEME_URL. '/images/logosmall.png',
                            'alt'=>1,
                        );

$options[] = array('name'=>'instant_position[logosmall]', 
                            'title'=>__(' '), 
                            'desc'=>__('This feature enable you to adjust logo position from frontend, it is admin only, but we still recommend you to turn off it in most time to avoid position change by accident ', THEME_NAME),
                            'text'=>__('Instant Position', THEME_NAME), 
                            'std'=>0,
                            'type'=>'button', 
                        );

$options[] = array('name'=>'general[copyright]', 
                            'title'=>__('Footer Copyright', THEME_NAME), 
                            'type'=>'textarea', 
                            'desc'=>__('A copyright notice displayed on the footer.', THEME_NAME),
                            'std'=>'Copyright © '. date('Y '). get_bloginfo('name'). ', All Rights Reserved',
                        );

$options[] = array('name'=>'general[favicon]', 
                            'title'=>__('Favicon', THEME_NAME), 
                            'type'=>'upload', 
                            'desc'=>__('Do not forget to clear browser cache if you want to see favicon change <br> immediately.', THEME_NAME),
                            'alt'=>1
                        );

$options[] = array('name'=>'general[color]', 
                            'title'=>__('Main Color', THEME_NAME), 
                            'type'=>'text', 
                            'std'=>'FD8C38',
                            'class'=>'iwak-color',
                            'desc'=>__('Change main color here, default FD8C38', THEME_NAME),
                            'alt'=>1
                        );

$options[] = array('name'=>'background[color]', 
                            'title'=>__('Background Color', THEME_NAME), 
                            'type'=>'text', 
                            'std'=>'FFFFFF',
                            'class'=>'iwak-color',
                            'desc'=>__('Change background color here, default FFFFFF', THEME_NAME),
                        );

$options[] = array('name'=>'background[image]', 
                            'title'=>__('Background Image', THEME_NAME), 
                            'type'=>'upload', 
                            'desc'=>__('Upload your custom background image here', THEME_NAME),
                        );

$options[] = array('name'=>'background[tile]', 
                            'title'=>__('Tile Image', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Make custom background image tile', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );

$options[] = array('name'=>'general[cufon_font]', 
                            'title'=>__('Heading Font', THEME_NAME), 
                            'desc'=>__('Replace heading font by using a custom cufon font file', THEME_NAME),
                            'type'=>'upload', 
                            'std'=>THEME_URL. '/js/custom.font.basic.js',
                            'alt'=>1,
                        );

$options[] = array('name'=>'general[cufon]', 
                            'title'=>__(' '), 
                            'desc'=>__('Enable/disable font replacment here, don\'t forget to save. You can use another font by replace /themename/js/custom.font.js to yours', THEME_NAME),
                            'text'=>__('Cufon Font Replacement', THEME_NAME), 
                            'std'=>1,
                            'type'=>'button', 
                        );

$options[] = array('name'=>'general[text_font]', 
                            'title'=>__('Text Font', THEME_NAME), 
                            'desc'=>__('Font chose here will be applied for texts except heading', THEME_NAME),
                            'type'=>'select', 
                            'std'=>'Droid+Sans:regular,bold',
                            'alt'=>1,
                            'items'=>array(  
                                                   'system'=>'System',
                                                   'Droid+Sans:regular,bold'=>'Droid Sans',
                                                   'Lato:regular,regularitalic,bold,bolditalic'=>'Lato',
                                                   'PT+Sans:regular,italic,bold,bolditalic'=>'PT Sans',
                                                   'Molengo'=>'Molengo',
                                                   'Cabin:regular,regularitalic,bold,bolditalic'=>'Cabin',
                                                   'Puritan:regular,italic,bold,bolditalic'=>'Puritan',
                                                   'Arimo:regular,italic,bold,bolditalic'=>'Arimo',
                                                   'Nobile:regular,italic,bold,bolditalic'=>'Nobile',
                                                   'Ubuntu:regular,italic,bold,bolditalic'=>'Ubuntu',
                                                ),
                        );

$options[] = array('name'=>'general[custom_font_code]', 
                            'title'=>__('Google Font Code', THEME_NAME), 
                            'desc'=>__('Paste here the code of your preferred font, you can generate it from <a target="_blank" href-"http://www.google.com/webfonts">Google Web Fonts</a>, e.g. Droid+Sans:regular,bold, this option will overwrite above one', THEME_NAME),
                            'type'=>'text', 
                        );

$options[] = array('name'=>'general[page404_title]', 
                            'title'=>__('404 Page Title', THEME_NAME), 
                            'type'=>'text', 
                            'std'=>'404 - Oops! Page Not Found',
                            'alt'=>1
                        );

$options[] = array('name'=>'general[page404_content]', 
                            'title'=>__('404 Page Content', THEME_NAME), 
                            'desc'=>__('A user friendly messsage for the 404 (file or directory not found) page ', THEME_NAME),
                            'type'=>'textarea', 
                            'std'=>'The page you are trying to reach can not be found<br><br>Try refining your search, or use the navigation above to locate the post.',
                        );
                        
$options[] = array('name'=>'general[custom_css]', 
                            'title'=>__('Custom CSS', THEME_NAME), 
                            'type'=>'textarea', 
                            'desc'=>__('Apply your custom/plugin css here', THEME_NAME),
                            'alt'=>1
                        );

global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Custom',
                           'desc'=>'Logo, Favicon, Custom CSS, 404 Message',
                           'options'=>$options
                        );

?>