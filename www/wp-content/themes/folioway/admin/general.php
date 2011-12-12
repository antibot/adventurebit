<?php

global $groups, $links;
$groups = array();

get_template_part('admin/options/general');
get_template_part('admin/options/home');
get_template_part('admin/options/blog');
get_template_part('admin/options/portfolio');
get_template_part('admin/options/contact');
get_template_part('admin/options/customize');

$pageinfo = array('title'=>'General Settings',
                           'links'=>$links,
                           'groups'=>$groups,
                           'filename'=>basename(__FILE__),
                           'class'=>'iwak-page-general',
                           'is_child'=>false
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>