<?php
/* Define the custom box */

// WP 3.0+
add_action('add_meta_boxes', 'iwak_portfolio_option_box');

// backwards compatible
//add_action('admin_init', 'myplugin_add_custom_box', 1);

/* Do something with the data entered */
add_action('save_post', 'iwak_save_portfolio_options');

/* Adds a box to the main column on the Post and Page edit screens */
function iwak_portfolio_option_box() {
    add_meta_box( 
        'iwak-portfolio-options',
        THEME_NAME. __( ' Portfolio Post Options', THEME_NAME ),
        'iwak_portfolio_options',
        'portfolio' 
    );
}

/* Prints the box content */
function iwak_portfolio_options() {

    // Use nonce for verification
    //wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );
	    global $post;
	    $post_id = $post;
	    if (is_object($post_id)) {
	    	$post_id = $post_id->ID;
	    }
   	$project_link = get_post_meta($post_id, 'project_link', true);
   	$project_link_text = get_post_meta($post_id, 'project_link_text', true);

    // The actual fields for data entry ?>
                
                <div class="iwak-post-option">
					<p>
						<strong>Project Link</strong>
					</p>
                    
                    <p>
                        <input type="text" class="regular-text" id="project_link" name="project_link" value="<?php echo $project_link; ?>">
                    </p>
                    
					<p class="help">
						Empty for no project link displayed, you may include a custom link in the excerpt option box
					</p>
                </div>
                
                <div class="iwak-post-option">
					<p>
						<strong>Project Link Text</strong>
					</p>
                    
                    <p>
                        <input type="text" class="regular-text" id="project_link_text" name="project_link_text" value="<?php echo $project_link_text; ?>">
                    </p>
					<p class="help">
						By default 'Launch Project'
					</p>
                            
                </div>
                
                <input name="edit" type="hidden" value="edit" />
<?php
}

/* When the post is saved, saves our custom data */
function iwak_save_portfolio_options( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  //if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
     // return $post_id;

  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
      return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data

  //$mydata = $_POST['project_link'];
  
		$is_saving = $_POST['edit'];
		if(!empty($is_saving)){
			delete_post_meta($post_id, 'project_link');
			add_post_meta($post_id, 'project_link', $_POST['project_link']);
			delete_post_meta($post_id, 'project_link_text');
			add_post_meta($post_id, 'project_link_text', $_POST['project_link_text']);
		}		

  // Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)

   //return $mydata;
}
?>