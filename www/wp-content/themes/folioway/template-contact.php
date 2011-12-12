<?php
/*
Template Name: Contact
*/
?>

<?php get_header(); echo '</div>'; // close #background ?>
    
<div id="container">
        <div class="inner">
            <div id="content">
                <?php the_post(); ?>
                    <div id="post-<?php the_ID() ?>" class="single page">
                        <h1 class="post-title super-heading"><?php the_title() ?></h1>
                        <div class="post-content">
                            <?php $iwak->the_content() ?>
                            
                    <div class="box contactbox">        
                    <h3 class="boxtitle"><?php _e('Send us a message', THEME_NAME); ?></h3>
                    <ul class="error"><?php if(isset($_POST['submit'])) $iwak->sendmail(); //If the form is submitted ?></ul>
                        
                    <?php if(isset($iwak->email_sent) && $iwak->email_sent == true): ?>
                        <p class="thankyou">
                            <?php _e('Your Message Has Been Sent, thank you!', THEME_NAME); ?>
                        </p>
                    <?php else: ?>
                        <form id="contactform" action="" method="post">
                            <div class="form-input">
                                <input id="cfname" name="cfname" class="text <?php if(isset($iwak->invalid_inputs['name'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['name'];?>" size="28" maxlength="50" tabindex="1" />
                                <label class="form-label"><?php _e('Name', THEME_NAME); ?> *</label>
                            </div>

                            <div class="form-input">
                                <input id="cfemail" name="cfemail" class="text <?php if(isset($iwak->invalid_inputs['email'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['email'];?>" size="28" maxlength="50" tabindex="2" />
                                <label class="form-label"><?php _e('EMail', THEME_NAME); ?> *</label>
                            </div>

                            <div class="form-textarea">
                                <textarea id="cfmessage" name="cfmessage" class="textarea <?php if(isset($iwak->invalid_inputs['message'])) echo 'invalid';?>" tabindex="3" rows="8" ><?php echo $iwak->invalid_inputs['message'];?></textarea>
                            </div>
                            
                            <p class="form-submit">
                                <input id="submit" name="submit" class="button" type="submit" value="<?php _e( 'Send E-Mail', THEME_NAME ) ?>" tabindex="4" />
                                <span class="loaderIcon hidden"></span>
                                <input type="hidden" value="<?php echo admin_url("admin-ajax.php"); ?>" name="submit_url" id="submit_url">
                            </p>
                        </form>
                    <?php endif; ?>
                            </div>
                        </div>
                
                </div><!-- #post -->
            </div><!-- #content -->
                
            <div id="contact-sidebar" class="sidebar">
                <?php iwak_get_sidebar(null); ?>
            </div>
                                                    
            <div class="clear"></div>
        </div><!-- .inner -->
</div><!-- #container -->
        
<?php get_footer() ?>