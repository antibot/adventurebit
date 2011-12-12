<?php
////////////////////////////////////////////////////////////////////////////////
//  DEFINE CATEGORY TEMPLATES
////////////////////////////////////////////////////////////////////////////////
global $cat_templates, $cat_orderby,$cat_order, $cat_visual_effects;
$cat_templates = array('blog', 'portfolio', 'portfolio2','portfolio3');
$cat_orderby = array('date', 'title', 'ID', 'author', 'modified', 'comment_count', 'parent');
$cat_order = array('ASC', 'DESC');
$cat_visual_effects = array('standard', 'folded');
add_action('portfolio_category_add_form_fields', 'fresh_category_form');
add_action('portfolio_category_edit_form', 'fresh_category_form');
add_action('create_portfolio_category','fresh_edit_category');
add_action('edit_portfolio_category','fresh_edit_category');

function fresh_edit_category($ID)
{
  if(isset($_POST['page_template']))
  {
    update_option($ID.'-cat_template', $_POST['page_template']);
    update_option($ID.'-cat_posts', $_POST['posts_per_page']);
    update_option($ID.'-cat_orderby', $_POST['orderby']);
    update_option($ID.'-cat_order', $_POST['order']);
    update_option($ID.'-cat_visual_effect', $_POST['visual_effect']);
  }
  //update_option('cat_option_pico', $ID);
}


function fresh_category_form()
{
  global $cat_templates, $cat_orderby,$cat_order, $cat_visual_effects;
 // echo $_GET['tag_ID']. 'dsdsds';
   $actual_selected = get_option($_GET['tag_ID'].'-cat_template');
   $order_by = get_option($_GET['tag_ID'].'-cat_orderby');
   $order = get_option($_GET['tag_ID'].'-cat_order');
   $visual_effect = get_option($_GET['tag_ID'].'-cat_visual_effect');
   
   if(empty($order_by) || !in_array($order_by, $cat_orderby)) $order_by = 'date';
   if(empty($actual_selected) || !in_array($actual_selected, $cat_templates)) $actual_selected = 'blog';
   if(empty($order) || !in_array($order, $cat_order)) $order = 'DESC';
   if(empty($visual_effect) || !in_array($visual_effect, $cat_visual_effects)) $visual_effect = 'standard';
   
   $posts_per_page = get_option($_GET['tag_ID'].'-cat_posts');
   if($posts_per_page =="" ) $posts_per_page = 5;
?>
    
    <table class="form-table">
    <tr class="form-field">
			<th scope="row" valign="top"><label for="description">Order by</label></th>
			<td>		<select name='orderby' id='orderby' class='postform' >
			
			<option value='<?php echo $order_by; ?>'><?php echo $order_by; ?></option>
			<?php 
			
			foreach($cat_orderby as $cat_temp)
			{
			 if($order_by != $cat_temp)
        echo '<option class="level-0" value="'.$cat_temp.'">'.$cat_temp.'</option>';
      }
    	?>
    </select>

			<span class="description"></span></td>
		</tr>
    </table>   
    
    <table class="form-table">
    <tr class="form-field">
			<th scope="row" valign="top"><label for="description">Order</label></th>
			<td>		<select name='order' id='order' class='postform' >
			
			<option value='<?php echo $order; ?>'><?php echo $order; ?></option>
			<?php 
			
			foreach($cat_order as $cat_temp)
			{
			 if($order != $cat_temp)
        echo '<option class="level-0" value="'.$cat_temp.'">'.$cat_temp.'</option>';
      }
    	?>
    </select>

			<span class="description"></span></td>
		</tr>
    </table>    
    
<br/>        
<?php
}

?>
