<?php

global $groups, $links;
$groups = array();

require_once('options/testimonial-list.php');

$pageinfo = array('title'=>__('Testimonials', THEME_NAME),
                           'links'=>$links,
                           'groups'=>$groups,
                           'class'=>'iwak-page-testimonial',
                           'filename'=>basename(__FILE__)
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>