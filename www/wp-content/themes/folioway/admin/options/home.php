<?php

$options = array();

$options[] = array('name'=>'home[place_widgets]', 
                            'title'=>__('Widgets Area', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Enter a positive number (not same to others) for this area to be displayed, this number will also be used to determine the position of this module. Enter 0 to remove this module from home page.', THEME_NAME),
                            'std'=>0,
                        );

$options[] = array('name'=>'home[place_posts]', 
                            'title'=>__('Blog Area', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Enter a positive number (not same to others) for this area to be displayed, this number will also be used to determine the position of this module. Enter 0 to remove this module from home page.', THEME_NAME),
                            'std'=>0,
                        );

$options[] = array('name'=>'home[place_works]', 
                            'title'=>__('Latest Works Area', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Enter a positive number (not same to others) for this area to be displayed, this number will also be used to determine the position of this module. Enter 0 to remove this module from home page.', THEME_NAME),
                            'std'=>1,
                        );

$options[] = array('name'=>'home[place_clients]', 
                            'title'=>__('Clients Area', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Enter a positive number (not same to others) for this area to be displayed, this number will also be used to determine the position of this module. Enter 0 to remove this module from home page.', THEME_NAME),
                            'std'=>2,
                        );

$options[] = array('name'=>'home[place_content]', 
                            'title'=>__('Additional Content Area', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Enter a positive number (not same to others) for this area to be displayed, this number will also be used to determine the position of this module. Enter 0 to remove this module from home page.', THEME_NAME),
                            'std'=>0,
                        );

$options[] = array('name'=>'home[posts_per_belt]',
                            'title'=>__('Latest Works Number', THEME_NAME), 
                            'desc'=>__('How many works will be displayed for the "latest works" also "similar works" areas, at most 24', THEME_NAME),
                            'class'=>'small-text',
                            'type'=>'text',
                            'std'=>8,
                            'alt'=>1,
                        );

$options[] = array('name'=>'home[belt_works_category]',
                            'type'=>'select', 
                            'desc'=>__('Display works only from the chosen category, if specified', THEME_NAME),
                            'title'=>__('Latest Works Category', THEME_NAME),
                            'std'=>-1,
                            'items'=>'{portfolio_category}'
                        );

$options[] = array('name'=>'home[belt_posts_category]',
                            'type'=>'select', 
                            'desc'=>__('If to display blog posts on latest works area too, none for no', THEME_NAME),
                            'title'=>__('Show Posts Meantime', THEME_NAME),
                            'std'=>0,
                            'items'=>array(0=>__('None', THEME_NAME), -1=>__('All Categories', THEME_NAME)) + iwak_get_list('category'),
                        );

$options[] = array('name'=>'home[links_category]',
                            'type'=>'select', 
                            'desc'=>__('Where is the cilents links ', THEME_NAME),
                            'title'=>__('Cilents Category', THEME_NAME),
                            'items'=>'{link_categories}',
                            'alt'=>1,
                        );

$options[] = array('name'=>'home[additional_content]',
                            'type'=>'select', 
                            'title'=>__('Choose Additional Content', THEME_NAME),
                            'desc'=>__('You can choose a page to be displayed on home page additionally, but content only, no title, no sidebar, no another page template will be displayed, etc', THEME_NAME),
                            'items'=>'{pages}',
                            'alt'=>1,
                        );

global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Home',
                           'desc'=>'Manage home areas and area orders',
                           'options'=>$options
                        );

?>