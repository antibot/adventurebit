<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>

<head profile="http://gmpg.org/xfn/11">

    <?php global $iwak; $site_title = get_bloginfo('name'); ?>
    <title><?php echo wp_title('|', false, 'right'). $site_title; ?></title>
    <meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
    <?php if($iwak->o['general']['favicon']): ?><link rel="shortcut icon" href="<?php echo $iwak->o['general']['favicon']; ?>" /><?php endif; ?>
    
    <!--[if lte IE 8]>
    <link rel="stylesheet" type="text/css"  href="<?php echo THEME_URL; ?>/css/ie8-.css" />
    <![endif]-->
    
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css"  href="<?php echo THEME_URL; ?>/css/ie7.css" />
    <![endif]-->
    
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?php echo THEME_URL; ?>/css/noscript.css"/>
    </noscript>
    
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head() // For plugins ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
    
</head>

<body <?php body_class(); ?>>
  <div id="wrapper">
  <div id="background">      
    <div id="header">
        <div class="inner">
            <div id="logo">
                <?php if($iwak->o['general']['logo']): ?>
                    <a title="<?php echo $site_title; ?>" href="<?php echo HOME_URL; ?>"><img src="<?php echo $iwak->o['general']['logo']; ?>" alt="<?php echo $site_title; ?>" /></a>
                <?php else: ?>
                    <h1><a href="<?php echo HOME_URL; ?>/"><?php echo $site_title; ?></a></h1>
                    <div id="blogdesc"><?php bloginfo('description') ?></div>
                <?php endif; ?>
            </div>
            
            <div id="phone"><?php if(!$iwak->o['general']['top_search']) echo $iwak->o['general']['phone']; else get_template_part('searchform'); ?></div>
            
            <?php if($iwak->o['general']['intro']): ?>
                <div id="intro"><img src="<?php echo $iwak->o['general']['intro']; ?>" /></div>
            <?php endif; ?>
                       
            <div id="menu-wrapper"><?php $iwak->menubar('main', 1); ?><span class="icon"></span></div>
        </div><!-- .inner -->              
    </div><!--.header-->

