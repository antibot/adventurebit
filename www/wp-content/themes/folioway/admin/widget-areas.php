<?php

global $groups, $links;
$groups = array();

require_once('options/widget-areas.php');

$pageinfo = array('title'=>__('Widget Areas', THEME_NAME),
                           'links'=>$links,
                           'groups'=>$groups,
                           'class'=>'iwak-widget-areas',
                           'filename'=>basename(__FILE__)
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>