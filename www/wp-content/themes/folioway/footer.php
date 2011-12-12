</div><!-- #wrapper -->        

        <div id="footer">
            <div class="inner">
                <span class="icon"></span>
                <?php global $iwak;  if($iwak->o['general']['logosmall']): ?>
                    <div class="fr">
                        <a title="<?php echo $site_title; ?>" href="<?php echo HOME_URL; ?>"><img id="logosmall" src="<?php echo $iwak->o['general']['logosmall']; ?>" alt="<?php echo $site_title; ?>" /></a>
                        Powered by Wordpress, Designed by <a href="http://themecrunch.blogspot.com/2011/05/folioway.html">iwakthemes</a>
                    </div>
                <?php endif; ?>
                
                <?php $iwak->load_widgets_group('Footer Column 1', '<div class="column first">', '</div>'); ?>
                <?php $iwak->load_widgets_group('Footer Column 2', '<div class="column">', '</div>'); ?>
                <?php $iwak->load_widgets_group('Footer Column 3', '<div class="column">', '</div>'); ?>
                <?php $iwak->load_widgets_group('Footer Column 4', '<div class="column">', '</div>'); ?>
                
                
                <div class="clear"></div>
             </div>
        </div>
                            
        <div id="underfooter">
            <div class="inner">
            </div>
        </div>
        
    <?php wp_footer(); ?>
    
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            <?php if($iwak->o['general']['cufon'] ): ?>
                Cufon.replace('h1, h2, h3, h4, h5, h6, .comment-index, .comment-author, .comment-meta, .breadcrumb, .entry-title', {hover:true});  
            <?php endif; ?>
            
            $("a[rel^='prettyPhoto']").prettyPhoto({
                //slideshow: 3000,
                //autoplay_slideshow: true
                default_width: 940,
                default_height: 800,
                theme: 'light_square'
            });
        });
    </script>
    
    <?php if( !empty($iwak->o['general']['tracking_code']) ) echo $iwak->o['general']['tracking_code']; ?>

</body>
</html>