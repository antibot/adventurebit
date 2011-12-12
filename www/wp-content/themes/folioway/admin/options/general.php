<?php

// Lists: link_categories, widget_areas, featured_posts, sticky_posts, post_types, category, tag

$options = array();

$options[] = array('name'=>'general[pattern]',
                            'type'=>'select', 
                            'desc'=>__('Preset background patterns, you can customize background under the tab "Custom"', THEME_NAME),
                            'std'=>'default', 
                            'title'=>__('Current Pattern', THEME_NAME),
                            'items'=>array(
                                                  'none'=>__('None', THEME_NAME),
                                                  'carbon'=>__('Carbon', THEME_NAME),
                                                  'dot'=>__('Dot', THEME_NAME),
                                                  'grid'=>__('Grid', THEME_NAME),
                                                  'reticulated'=>__('Reticulated', THEME_NAME),
                                                  'horizontal_strip'=>__('Horizontal Strip', THEME_NAME),
                                                  'vertical_strip'=>__('Vertical Strip', THEME_NAME),
                                                  'left_strip'=>__('Left Strip', THEME_NAME),
                                                  'left_strip_2'=>__('Left Strip 2', THEME_NAME),
                                                  'left_strip_3'=>__('Left Strip 3', THEME_NAME),
                                                  'right_strip'=>__('Right Strip', THEME_NAME),
                                                  'right_strip_2'=>__('Right Strip 2', THEME_NAME),
                                                  'right_strip_3'=>__('Right Strip 3', THEME_NAME),
                                               )
                        );

$options[] = array('name'=>'general[rss]', 
                            'title'=>__('Feed Address', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>'Enter here your preferred RSS URL, feedburner or other. For other social settings, check out your profile page (Users -> Your Profile).',
                            'std'=>get_bloginfo('rss2_url'),
                            'alt'=>1
                        );

$options[] = array('name'=>'general[phone]', 
                            'title'=>__('Phone Number', THEME_NAME), 
                            'type'=>'text', 
                            'std'=>'1 800 753 4482',
                            'alt'=>1
                        );

$options[] = array('name'=>'general[top_search]', 
                            'title'=>__('Searchbox Instead', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Choose yes to display a searchbox instead of phone number.', THEME_NAME),
                            'std'=>0,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );

$options[] = array('name'=>'general[menus][main][type]',
                            'title'=>__('Main Navigation', THEME_NAME), 
                            'type'=>'multi',
                            'class'=>'regular-radio menu-type',
                            'std'=>'custom',
                            'items'=>array('category'=>__('Display categories', THEME_NAME), 
                                                 'page'=>__('Display pages', THEME_NAME),
                                                 'both'=>__('Both categories and pages', THEME_NAME), 
                                                 'custom'=>__('Custom', THEME_NAME),
                                                ),
                            'alt'=>1,
                        );

    $options[] = array('name'=>'general[menus][main][entity]',
                                'type'=>'menu',
                                'homelink'=>1,
                                'depth'=>1,
                            );

$options[] = array('name'=>'general[menus][main][depth]',
                            'title'=>__('Depth', THEME_NAME), 
                            'desc'=>__('how many levels of the hierarchy are to be included where 0 means all', THEME_NAME),
                            'class'=>'small-text',
                            'type'=>'text',
                            'std'=>0,
                        );

$options[] = array('name'=>'general[image_quality]',
                            'title'=>__('Image Quality', THEME_NAME), 
                            'desc'=>__('Enter a number between 0 - 100, this number will be used for determine the quality of slider images, post thumbnails, etc, but nothing to do with images inserted manually to posts, pages, or anywhere.', THEME_NAME),
                            'class'=>'small-text',
                            'type'=>'text',
                            'std'=>90,
                            'alt'=>1,
                        );

$options[] = array('name'=>'general[auto_thumbnail]', 
                            'title'=>__('Auto Thumbnail', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('If a post/page doesn\'t have a featured image manually set, use the top one in the attachments', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1,
                        );

$options[] = array('name'=>'general[notification]', 
                            'title'=>__('Update Notification', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Whether or not to display an update notification when a new version is out', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1,
                        );
                       
$options[] = array('name'=>'general[tracking_code]', 
                            'title'=>__('Tracking Code', THEME_NAME), 
                            'type'=>'textarea', 
                            'desc'=>'Paste your Google Analytics (or other) tracking code here. This will be applied to each page.',
                            'alt'=>1
                        );
                        
$options[] = array('name'=>'general[homepage_keywords]', 
                            'title'=>__('Site Keywords', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>'SEO Option - Let search engine know keywords of your site',
                            'alt'=>1
                        );
                        
$options[] = array('name'=>'general[homepage_desc]', 
                            'title'=>__('Site Description', THEME_NAME), 
                            'type'=>'textarea', 
                            'desc'=>'SEO Option - Decide your site description displayed in search results',
                        );

global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'General',
                           'desc'=>'Styles, Feed, Navigation, Analytics, SEO',
                           'options'=>$options
                        );

?>