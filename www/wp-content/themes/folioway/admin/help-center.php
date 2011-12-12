<?php global $links;

$pageinfo = array('title'=>__('Help Center', THEME_NAME),
                           'template'=>'help.php',
                           'links'=>$links,
                           'filename'=>basename(__FILE__)
                        );

$ikp = new iWaK_Admin_Page($pageinfo);

?>