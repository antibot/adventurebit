<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>

	<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>

	<?php if ( is_search() ) { ?>Search Results: <?php the_search_query(); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>

	<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Author Archives<?php } ?>

	<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>

	<?php if ( is_page() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>

	<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>

	<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>

	<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>

</title>
 
<?php
    if(is_singular()) {
      wp_enqueue_script('comment-reply');
    }
    wp_head();
?>
 
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

</head>

<body>

<div id="header">

  <a href="/">
    <img id="logo" src="<?php bloginfo('template_url') ?>/images/logo.png" alt="Adventure Bit" title="Adventure Bit" />
  </a>
	
	<div id="menu">
	 <?php wp_nav_menu('menu=main'); ?>
	</div>
	
  <div class="breadcrumbs">
  <?php
    if(function_exists('bcn_display')) {
      bcn_display();
    }
  ?>
</div>

</div>

