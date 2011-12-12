<?php
if(function_exists('register_sidebar')) {
  register_sidebar();
}
 
if(function_exists('add_theme_support')) {
    add_theme_support('menus');
}

register_nav_menu('main', 'Главное меню'); 
    
?>