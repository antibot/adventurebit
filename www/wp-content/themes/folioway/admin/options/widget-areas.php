<?php

$options = array();

$options[] = array('name'=>'widget_area[name]', 
                            'type'=>'text',
                            'class'=>'small-text',
                            'inline'=>1,
                        );

$options[] = array('name'=>'widget_area[description]', 
                            'type'=>'text',
                            'inline'=>1,
                        );

$options[] = array('type'=>'icon',
                            'class'=>'icon icon-delete',
                            'inline'=>1,
                        );

global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'The Areas',
                           'desc'=>'Manage all custom widget areas here',
                           'type'=>'widget_areas',
                           'repeat'=>1,
                           'class'=>'widget-area-row',
                           'options'=>$options
                        );

?>