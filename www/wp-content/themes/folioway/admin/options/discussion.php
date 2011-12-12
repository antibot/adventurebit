<?php

$options = array();

$options[] = array('name'=>'comments[display_index]', 
                            'title'=>__('Display Comments No.', THEME_NAME), 
                            'type'=>'radio', 
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1
                        );
                        
$options[] = array('name'=>'comments[display_children_index]', 
                            'title'=>__('Display Nested Comments No.', THEME_NAME), 
                            'type'=>'radio', 
                            'std'=>0,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );
                        
$options[] = array('name'=>'comments[highlight_author]', 
                            'title'=>__('Highlight Author', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Highlight comments/replies made by author or administrator. <br><br> More comment options are available at Settings -> Discussion, you can enable nested comments there, define comments number per page, and more', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 )
                        );
                        
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Discussion',
                           'desc'=>'Comments No, Highlight on Author Replies',
                           'options'=>$options
                        );
//polar_register_options($options, $metainfo);

?>