<?php

global $groups, $links;
$groups = array();

require_once('options/about.php');

$pageinfo = array('title'=>'About Page',
                           'links'=>$links,
                           'groups'=>$groups,
                           'filename'=>basename(__FILE__),
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>