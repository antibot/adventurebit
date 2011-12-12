<?php
/*
Template Name: Full Width
*/
?>

<?php get_header(); echo '</div>'; // close #background ?>

<div id="container">
    <div class="inner">
    
        <?php the_post(); ?>
            <div id="post-<?php the_ID() ?>" class="page single">
                <h1 class="post-title super-heading"><?php the_title() ?></h1>
                <div class="post-content"><?php $iwak->the_content() ?></div>
            </div><!-- .post -->
                                
        <div class="clear"></div>
    </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>