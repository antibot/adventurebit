                <?php $option_name = 'thumbnail_size'; _e('Thumbnail Size: ', THEME_NAME); ?>
                <select name="<?php echo $this->get_field_name($option_name); ?>" id="<?php echo $this->get_field_id($option_name); ?>"  class="widefat">
                    <option value="2.55:1" <?php if( $instance[$option_name] == '2.55:1' ) echo ' selected="selected"'; ?>><?php _e('Large', THEME_NAME); ?></option>
                    <option value="58x58" <?php if( $instance[$option_name] == '58x58' ) echo ' selected="selected"'; ?>><?php _e('Small', THEME_NAME); ?></option>
                </select><br />
