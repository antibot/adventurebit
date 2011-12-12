<?php

$options = array();

$options[] = array('name'=>'filter[exclude]', 
                            'type'=>'select',
                            'std'=>0,
                            'inline'=>1,
                            'class'=>'hidden mini-select',
                            'items'=>array(0=>'Include',
                                                  1=>'Exclude',
                                                 )
                        );

$options[] = array('name'=>'filter[type]', 
                            'type'=>'select',
                            'std'=>'post',
                            'inline'=>1,
                            'class'=>'mini-select item-type',
                            'items'=>array('category'=>__('Category', THEME_NAME),
                                                  'tag'=>__('Tag', THEME_NAME),
                                                  'sticky'=>__('Sticky', THEME_NAME),
                                                 )
                        );

$options[] = array('name'=>'filter[id]', 
                            'type'=>'select',
                            'inline'=>1,
                            'class'=>'regular-select item-title',
                            'items'=>''
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-delete',
                            'inline'=>1,
                        );
                        
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>__('Rules', THEME_NAME),
                           'type'=>'filter_list',
                           'desc'=>'Define criteria of featured item here',
                           'class'=>'filter-item draggable',
                           'options'=>$options,
                           'content'=>__('Define your featured criteria here, once done it will handle all featured posts automatically, no more action, any new posts meet criteria here will be autoloaded. And any ever featured posts that no longer meet these criterias will be removed silently.', THEME_NAME)
                        );

?>