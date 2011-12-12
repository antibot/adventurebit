<?php

global $preset_buttons, $easing_list;
                                   
$options = array();

$options[] = array('name'=>'featured_post[type]', 
                            'type'=>'select',
                            'std'=>'post',
                            'inline'=>1,
                            'class'=>'item-type',
                            'items'=>'{post_types}',
                        );

$options[] = array('name'=>'featured_post[id]', 
                            'type'=>'select',
                            'inline'=>1,
                            'class'=>'large-select item-title',
                            'items'=>''
                        );

$options[] = array('name'=>'featured_post[status]', 
                            'type'=>'select',
                            'std'=>'auto',
                            'class'=>'item-status',
                            'inline'=>1,
                            'items'=>array('auto'=>__('Auto', THEME_NAME),
                                                  'static'=>__('Static', THEME_NAME),
                                                 )
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-expand',
                            'inline'=>1,
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-delete',
                            'inline'=>1,
                        );

    $options[] = array('name'=>'featured_post[image_link]', 
                                'title'=>__('Custom Link', THEME_NAME), 
                                'type'=>'text', 
                                'desc'=>__('Image is by deafult linked to selected post/page, enter URL here for a different one, "none" for no link.', THEME_NAME),
                            );

    $options[] = array('name'=>'featured_post[animation][in_duration]', 
                                'title'=>__('Fade-in Duration', THEME_NAME), 
                                'type'=>'text',
                                'std'=>800,
                                'desc'=>__('How long the fade animation last, in milliseconds', THEME_NAME),
                                'class'=>'small-text',
                            );

    $options[] = array('name'=>'featured_post[animation][out_duration]', 
                                'title'=>__('Fade-out Duration', THEME_NAME), 
                                'type'=>'text',
                                'std'=>800,
                                'class'=>'small-text',
                            );

                            
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Items',
                           'desc'=>'All featured items',
                           'type'=>'featured_list',
                           'repeat'=>1,
                           'class'=>'featured-item draggable',
                           'options'=>$options
                        );

?>