<?php

$options = array();

$options[] = array('name'=>'portfolio[title]', 
                            'title'=>__('Portfolio Page Title', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('The title to be displayed on intro area', THEME_NAME),
                            'std'=>'Portfolio',
                        );

$options[] = array('name'=>'portfolio[orderby]',
                            'type'=>'select', 
                            'std'=>'date', 
                            'title'=>__('Order By', THEME_NAME),
                            'items'=>array(
                                'date'=>__('date'), 
                                'title'=>__('title'), 
                                'ID'=>__('ID'), 
                                'author'=>__('author'), 
                                'modified'=>__('modified'), 
                                'comment_count'=>__('comment_count'), 
                                'parent'=>__('parent')
                            ),
                        );

$options[] = array('name'=>'portfolio[order]',
                            'type'=>'select', 
                            'std'=>'DESC', 
                            'title'=>__('Order', THEME_NAME),
                            'items'=>array(
                                'ASC'=>__('ASC'), 
                                'DESC'=>__('DESC')
                            )
                        );

$options[] = array('name'=>'portfolio[display_shareto]', 
                            'title'=>__('Display Share-to', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Display a couple of links for quick share on post page', THEME_NAME),
                            'std'=>0,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );
                                                
                        
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Portfolio',
                           'desc'=>'Control stuffs on portfolio post page',
                           'options'=>$options
                        );
//polar_register_options($options, $metainfo);

?>