<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>Adventure Bit</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if(is_singular() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

	<div id="header">
	</div><!-- #header -->

	<div id="main">
