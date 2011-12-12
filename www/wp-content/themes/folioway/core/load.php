<?php

$iwaktheme = get_theme_data(TEMPLATEPATH. '/style.css');

//Theme relevant constants
define('THEME_NAME', $iwaktheme['Name']);
define('THEME_SLUG', str_replace(' ', '-', strtolower($iwaktheme['Name'])));
define('THEME_VERSION', $iwaktheme['Version']);
define('THEME_PATH', TEMPLATEPATH);
define('THEME_URL', get_template_directory_uri());
define('STYLESHEET_URL', get_stylesheet_directory_uri());
define('HOME_URL', home_url());

//Framework relevant constants
define('FRAMEWORK_VERSION', '1.6.1');
define('FRAMEWORK_PATH', TEMPLATEPATH. '/core');
define('ADMIN_TEMPLATE', 'default');
define('OPTION_BUILDERS', 'iwak_option_builders');
define('IWAK_OPTIONS', 'iwak_options');
define('FRAMEWORK_URL', THEME_URL. '/core');
define('ADMIN_URL', THEME_URL.'/core/templates/default');
define('WPVERSION', substr(str_replace('.', '', get_bloginfo('version')), 0, 2));

//Load framework classes automatically
function __autoload($classname) {
    if(stripos($classname, 'iwak_') === false)
        return;
        
    $filename = 'class.'. strtolower(str_ireplace('iwak_', '', $classname)). '.php';
    $filepath = TEMPLATEPATH. '/core/classes/'. $filename;
    if(file_exists($filepath))
        include_once $filepath;
}

?>
