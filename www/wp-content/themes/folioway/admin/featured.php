<?php

global $groups, $links;
$groups = array();

get_template_part('admin/options/featured-panel');
get_template_part('admin/options/featured-criteria');
get_template_part('admin/options/featured-list');

$pageinfo = array('title'=>'Featured Panel',
                           'links'=>$links,
                           'groups'=>$groups,
                           'class'=>'iwak-page-featured',
                           'filename'=>basename(__FILE__)
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>