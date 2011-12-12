<div id="iwak-admin" class="iwak-page iwak-help-center <?php echo $bricks['class']; ?>">
    
    <div style="margin-top:20px;">
        <img src="/wp-content/themes/Creations/core/templates/default/images/helpcenter.png">
    </div>

    <div class="clear"></div>
    
    <div id="issues" class="box">
        <h3>Common Issues</h3>
        <ul>
            <li><a href="http://forum.iwakthemes.com/viewtopic.php?f=13&t=23">I got an error message "Parse error: ... unexpected ... "</a></li>
            <li><a href="http://forum.iwakthemes.com/viewtopic.php?f=13&t=23">Theme doesn't show up correctly, it's like all text</a> </li>
            <li><a href="http://forum.iwakthemes.com/viewtopic.php?f=13&t=23">Thumbnails/images not showing up</a></li>
        </ul>
    </div>
    
    <div id="tuts" class="box">
        <h3>Tutorials</h3>
        <ul>
            <?php foreach($bricks['links']['tutorials'] as $tut): ?>
            <li><a href="<?php echo $tut['url']; ?>"><?php echo $tut['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div id="questions" class="box">
        <h3>Ask Question</h3>
        <ul>
            <li><a href="<?php echo $bricks['links']['forum']['url']; ?>">Log in to our support forum to browser questions or ask a question</a></li>
        </ul>
    </div>
    
    <?php if(isset($_POST['submit'])) $iwak->contact_support(); //If the form is submitted ?>
    <div id="words" class="box">
        <h3>Love our theme?</h3>
                
            <?php if(isset($iwak->email_sent) && $iwak->email_sent == true): ?>
                <p class="thankyou">
                    <?php _e('Your Message Has Been Sent, thank you!', THEME_NAME); ?>
                </p>
            <?php else: ?>
                <form class="iwak-contactform" action="" method="post">
                    
                    <p class="form-submit">
                        <input id="submit" name="submit" class="button button-primary" type="submit" value="<?php _e( 'Spread the words', THEME_NAME ) ?>" tabindex="4" />
                        <span class="loaderIcon hidden"></span>
                        <input type="hidden" value="<?php echo admin_url("admin-ajax.php"); ?>" name="submit_url" id="submit_url">
                        <input type="hidden" value="Spread The Words" name="submit_category" id="submit_category">
                    </p>

                    <div class="form-textarea">
                        <textarea id="cfmessage" name="cfmessage" class="large-textarea <?php if(isset($iwak->invalid_inputs['message'])) echo 'invalid';?>" tabindex="3" rows="<?php echo $rows; ?>"><?php echo $iwak->invalid_inputs['message'];?></textarea>
                    </div>
                    
                    <div class="form-input">
                        <input id="cfname" name="cfname" class="small-text <?php if(isset($iwak->invalid_inputs['name'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['name'] ? $iwak->invalid_inputs['name'] : get_userdata(1)->display_name;?>" size="30" maxlength="50" tabindex="1" />
                        <label class="form-label"><?php _e('Name', THEME_NAME); ?> (*)</label>
                    </div>

                    <div class="form-input">
                        <input id="cfemail" name="cfemail" class="small-text <?php if(isset($iwak->invalid_inputs['email'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['email'] ? $iwak->invalid_inputs['email'] : get_userdata(1)->user_email; ?>" size="30" maxlength="50" tabindex="2" />
                        <label class="form-label"><?php _e('EMail', THEME_NAME); ?> (*)</label>
                    </div>

                    <div class="form-input">
                        <input id="cfdescribe" name="cfdescribe" class="regular-text" type="text" value="" size="30" maxlength="50" tabindex="1" />
                        <label class="form-label"><?php _e('About You', THEME_NAME); ?></label>
                    </div>

                    <div class="form-input">
                        <input id="cfwebsite" name="cfwebsite" class="regular-text" type="text" value="<?php bloginfo('siteurl'); ?>" size="30" maxlength="50" tabindex="2" />
                        <label class="form-label"><?php _e('Website', THEME_NAME); ?></label>
                    </div>
                </form>
            <?php endif; ?>
    </div>
    
    <div id="survey" class="box">
        <h3>Help Us Better</h3>
                
            <?php if(isset($iwak->email_sent) && $iwak->email_sent == true): ?>
                <p class="thankyou">
                    <?php _e('Your Message Has Been Sent, thank you!', THEME_NAME); ?>
                </p>
            <?php else: ?>
                <form class="iwak-contactform" action="" method="post">
                    
                    <p class="form-submit">
                        <input id="submit" name="submit" class="button button-primary" type="submit" value="<?php _e( 'Send E-Mail', THEME_NAME ) ?>" tabindex="4" />
                        <span class="loaderIcon hidden"></span>
                        <input type="hidden" value="<?php echo admin_url("admin-ajax.php"); ?>" name="submit_url" id="submit_url">
                        <input type="hidden" value="Help Us Better" name="submit_category" id="submit_category">
                    </p>

                    <div class="form-textarea">
                        <textarea id="cfmessage" name="cfmessage" class="large-textarea <?php if(isset($iwak->invalid_inputs['message'])) echo 'invalid';?>" tabindex="3" rows="<?php echo $rows; ?>"><?php echo 'What features/options do you want to see in future update?&#10;&#10;&#10;What features/options do you like the most? Least? Why?&#10;&#10;&#10;Which style do you like the most? Least?&#10;&#10;&#10;' ?></textarea>
                    </div>
                    
                    <div class="form-input">
                        <input id="cfname" name="cfname" class="small-text <?php if(isset($iwak->invalid_inputs['name'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['name'] ? $iwak->invalid_inputs['name'] : get_userdata(1)->display_name;?>" size="30" maxlength="50" tabindex="1" />
                        <label class="form-label"><?php _e('Name', THEME_NAME); ?> (*)</label>
                    </div>

                    <div class="form-input">
                        <input id="cfemail" name="cfemail" class="small-text <?php if(isset($iwak->invalid_inputs['email'])) echo 'invalid';?>" type="text" value="<?php echo $iwak->invalid_inputs['email'] ? $iwak->invalid_inputs['email'] : get_userdata(1)->user_email; ?>" size="30" maxlength="50" tabindex="2" />
                        <label class="form-label"><?php _e('EMail', THEME_NAME); ?> (*)</label>
                    </div>
                </form>
            <?php endif; ?>    
    </div>
</div>
