<div id="page-menu">
  <?php wp_nav_menu('menu=main'); ?>
</div>

<?php 
  if(function_exists('bcn_display')) {
    bcn_display();
  }
  
  if (have_posts()) :
    while(have_posts()) :
      the_post(); 
?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if (is_front_page()) : ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php else: ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- entry-content -->
					<?php edit_post_link( __( 'Редактировать', 'adventurebit' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- post -->

				<?php comments_template('', true ); ?>

<?php 
    endwhile;
  endif; 
?>
