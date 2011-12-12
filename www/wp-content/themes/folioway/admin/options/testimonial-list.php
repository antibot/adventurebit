<?php

$options = array();

$options[] = array('name'=>'testimonial_record[author]', 
                            'type'=>'text',
                            'class'=>'small-text',
                            'inline'=>1,
                        );
                        
$options[] = array('name'=>'testimonial_record[content]', 
                            'title'=>__('Testimonial', THEME_NAME), 
                            'type'=>'textarea',
                            'class'=>'wide-textarea',
                            'inline'=>1,
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-expand',
                            'inline'=>1,
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-delete',
                            'inline'=>1,
                        );

    $options[] = array('name'=>'testimonial_record[author_image]', 
                                'title'=>__('Author Image', THEME_NAME), 
                                'type'=>'upload',
                            );

    $options[] = array('name'=>'testimonial_record[author_is]', 
                                'title'=>__('Author is', THEME_NAME), 
                                'type'=>'text',
                                'desc'=>__('Who he/she is', THEME_NAME),
                            );

    $options[] = array('name'=>'testimonial_record[site_name]', 
                                'title'=>__('Author Link Text', THEME_NAME), 
                                'type'=>'text',
                                'class'=>'small-text',
                            );

    $options[] = array('name'=>'testimonial_record[site_url]', 
                                'title'=>__('Author Link URL', THEME_NAME), 
                                'type'=>'text',
                            );

global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Testimonials',
                           'desc'=>'Manage all testimonials here',
                           'type'=>'testimonial_list',
                           'repeat'=>1,
                           'class'=>'testimonial-item draggable',
                           'options'=>$options
                        );

?>