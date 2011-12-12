<?php
if ( ! isset( $content_width ) ) $content_width = 640;
add_theme_support('automatic-feed-links');

get_template_part('/core/load');
get_template_part('/core/itools');
get_template_part('/includes/widgets');
get_template_part('/admin/admin');
get_template_part('/plugins/category-plus');
get_template_part('/includes/template');
get_template_part('/includes/sidebar-generator');
get_template_part('/includes/portfolio-options');
get_template_part('/core/shortcodes');
get_template_part('/core/modules/instant_position');
get_template_part('/core/modules/font_replacement');

?>