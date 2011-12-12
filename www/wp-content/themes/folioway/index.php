<?php get_header(); get_template_part('slider'); echo '</div>'; // close #background ?>
    
    <div id="container">
        <div class="inner">
        
        <?php ob_start(); ?>
        
        <?php if($pos = $iwak->o['home']['place_content']): query_posts(array('post_type'=>'page', 'page_id'=>$iwak->o['home']['additional_content'])); the_post(); ?>
            <div id="post-<?php the_ID() ?>" class="single page post-content panel-content panel">
            <?php $iwak->the_content('full_content=1'); ?>
            </div>
            <div class="clear"></div>
        <?php $outputs[$pos] = ob_get_contents(); ob_clean(); endif; ?>
        
        <?php if($pos = $iwak->o['home']['place_widgets']): $iwak->load_widgets_group('Home Page', '<div class="panel panel-widgets">', '</div><div class="clear"></div>'); $outputs[$pos] = ob_get_contents(); ob_clean(); endif; ?>
        
        <?php if($pos = $iwak->o['home']['place_works']): get_template_part('latest-works'); $outputs[$pos] = ob_get_contents(); ob_clean(); endif; ?>
        
        <?php if($pos = $iwak->o['home']['place_clients']): ?>
            <div id="clients" class="panel panel-clients">
                <h3><?php _e('Our Clients', THEME_NAME); ?></h3>
                <ul class="horiz-list">
                    <?php $clients = get_bookmarks(array('category'=>$iwak->o['home']['links_category'], 'orderby'=>'id'));
                        foreach($clients as $client):
                    ?>
                    <li><a target="<?php echo $client->link_target;?>" href="<?php echo $client->link_url;?>" title="<?php echo $client->link_name; ?>"><img src="<?php echo $client->link_image; ?>" alt="<?php echo $client->link_name; ?>" /></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php $outputs[$pos] = ob_get_contents(); ob_clean(); endif; ?>
                
        <?php if($pos = $iwak->o['home']['place_posts']): ?>
            <div class="panel panel-blog">
            <div id="content">

                <?php // The Query
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    query_posts(array('post_type'=>'post', 'paged'=>$paged));
                ?>

                <?php if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                    <div id="post-<?php the_ID() ?>" class="post entry<?php echo $i == 1 ? ' first' : ''; ?>">
                        <h2 class="post-title"><span class="day"><?php the_time('d');?></span><span class="month"><?php the_time('M'); ?></span><?php $iwak->the_post_link(get_the_title()); ?></h2>
                        
                        <?php if($src = $iwak->get_post_image_url(array(640, 240))): ?>
                            <a title="<?php echo get_the_title(); ?>" href="<?php echo get_permalink(); ?>">
                                <img class="thumbnail" src="<?php echo $src; ?>" />
                            </a>
                        <?php endif; ?>
                        
                        <div class="post-content"><?php $iwak->the_content(array('more_text'=>'Continue Reading')); ?></div>
                        
                        <div class="post-meta">
                            <?php _e('Posted by ', THEME_NAME); the_author_posts_link(); 
                            _e(' in ', THEME_NAME); echo get_the_category_list(', '); 
                            _e(', Followed with ', THEME_NAME); comments_popup_link( __('0 Comment', THEME_NAME), __('1 Comment', THEME_NAME), __('% Comments', THEME_NAME) ); 
                            //edit_post_link( __( 'edit', THEME_NAME ), '(', ')' ); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            
                <div class="pagination"><?php $iwak->get_pagination(10) ?></div>
                
                <?php else: get_template_part('noresults'); ?>

                <?php endif; ?>
            
            </div><!-- #content -->
                
            <div id="archive-sidebar" class="sidebar">
                <?php iwak_get_sidebar('Blog Sidebar'); ?>
            </div>
                    
            <div class="clear"></div></div>
        <?php $outputs[$pos] = ob_get_clean(); endif; ?>
        
        <?php ksort($outputs); foreach($outputs as $output) echo $output; ?>
            <div class="clear"></div>
        </div>
    </div>

<?php get_footer() ?>