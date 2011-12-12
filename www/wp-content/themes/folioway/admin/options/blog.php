<?php

$options = array();

$options[] = array('name'=>'blog[title]', 
                            'title'=>__('Blog Page Title', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('The title to be displayed on intro area', THEME_NAME),
                            'std'=>'Blog',
                        );

$options[] = array('name'=>'blog[display_excerpt]', 
                            'title'=>__('Index Page Display', THEME_NAME), 
                            'type'=>'select', 
                            'desc'=>__('Display post content or post excerpt', THEME_NAME),
                            'std'=>1,
                            'items'=>array('0'=>__('Content', THEME_NAME),
                                                  '1'=>__('Excerpt', THEME_NAME),
                                                 )
                        );

$options[] = array('name'=>'blog[excerpt_length]', 
                            'title'=>__('Excerpt Length', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('Decide length of excerpts to be displayed. In words', THEME_NAME),
                            'std'=>55,
                            'class'=>'small-text',
                            'alt'=>1
                        );

$options[] = array('name'=>'blog[auto_content]', 
                            'title'=>__('Content Autosplit', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Auto split content at a specified length, no influence on posts have a manual split (the <more> tag) set.', THEME_NAME),
                            'std'=>0,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1
                        );

$options[] = array('name'=>'blog[content_length]', 
                            'title'=>__('Content Split at', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('In characters', THEME_NAME),
                            'std'=>500,
                             'class'=>'small-text'
                        );
                        
$options[] = array('name'=>'blog[display_tags]', 
                            'title'=>__('Display Tags', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Display post tags on post page', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1
                        );
                        
$options[] = array('name'=>'blog[display_author]', 
                            'title'=>__('Display Author', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Display post author info on post page', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );
                        
$options[] = array('name'=>'blog[display_shareto]', 
                            'title'=>__('Display Share-to', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Display a couple of links for quick share on post page', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );
                        
$options[] = array('name'=>'blog[display_extra_posts]', 
                            'title'=>__('Display Related & Popular Posts', THEME_NAME), 
                            'type'=>'radio', 
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );
                                                
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Blog',
                           'desc'=>'Excerpt/Content, Length, Split, Author Info...',
                           'options'=>$options
                        );
//polar_register_options($options, $metainfo);

?>