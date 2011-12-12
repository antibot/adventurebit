<?php get_header(); ?>

<div id="container">
		<?php if (have_posts()) : ?>
			<?php $post = $posts[0]; ?>
		<?php while (have_posts()) : the_post(); ?>
		<div class="post">
				 <div class="date_cal"> 
				    <div class="date"><?php the_time ('j'); ?></div> 
				    <div class="month"><?php the_time ('M'); ?></div> 
				</div> 

				<div class="postinfo">Posted by <?php the_author(); ?> &#187; <a href="<?php the_permalink() ?>#comments"><?php comments_number('Add Comment &#187;','1 Comment &#187;','% Comments &#187;'); ?></a></div>


		<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<?php the_content("[Read more &rarr;]"); ?>
	 	 </div>

	<?php get_sidebar(); ?>

<?php endwhile; ?>
<?php else: ?>
<?php endif; ?>
<div style="clear:both;"></div>
<div class="postnav"><?php posts_nav_link(); ?></div>
</div>
<?php get_footer() ?>