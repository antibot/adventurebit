<?php
/*
Template Name: Home Page
*/
?>

<?php get_header(); get_template_part('slider'); echo '</div>'; // close #background ?>
    
    <div id="container">
        <div class="inner">

            <?php if($iwak->o['home']['display_widgets']): ?>
                <?php $iwak->load_widgets_group('Home Page', '<div class="panel panel-widgets">', '</div>'); ?>
            <?php else: the_post(); ?>
                    <div class="post-content panel"><?php $iwak->the_content(); ?></div>
            <?php endif;//defaults?>
            
            <div class="clear"></div>
        </div>
    </div>

<?php get_footer() ?>