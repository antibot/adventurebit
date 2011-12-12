<?php get_header(); ?>

<div id="container">

	<?php if(have_posts()): ?><?php while(have_posts()):the_post(); ?>

		<div class="post">

			<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

				<?php the_content("[Read more &rarr;]"); ?>
				
		 </div>

	<?php get_sidebar(); ?>



<?php endwhile; ?>

<?php else: ?>

<?php endif; ?>

</div>

<?php get_footer() ?>