<?php

class iWaK_Instant_Position {

    function __construct() {
            
        //extract($args);
        add_action('wp_footer', array(&$this, 'print_scripts'));
        add_action('wp_head', array(&$this, 'print_styles'));
    }
    
    function print_styles() {
        global $iwak;
        
        if( $positions = get_option('iwak_positions') ) {
            foreach($positions as $id=>$position)
                $css .= "#$id {left:". $position[0]. "px;top:". $position[1]. "px;}";
        }
        
        if( is_super_admin() && isset($iwak->o['instant_position']) && in_array(1, $iwak->o['instant_position']) ) {
            foreach($iwak->o['instant_position'] as $id=>$value) {
                if($value != 1)
                    continue;
                    
                $css .= "#$id, #$id a {cursor: move}";
            }
        }
        
            $output = "<style text='text/css'>\n". $css. "</style>\n";
            echo $output;
    }
    
    function print_scripts() {
        global $iwak;
        
        if( is_super_admin() && isset($iwak->o['instant_position']) && in_array(1, $iwak->o['instant_position']) ): ?>
            <script type="text/javascript" src="<?php echo ADMIN_URL; ?>/js/jquery-ui-1.8.custom.min.js"></script>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    instant_pos_elements =  '<?php foreach($iwak->o['instant_position'] as $id=>$value) if($value == 1) $list .= isset($list) ? ', #'. $id : '#'. $id; echo $list; ?>';
                    //alert(instant_pos_elements);
                    $(instant_pos_elements).addClass('instant_pos_enabled');
                    $(instant_pos_elements).draggable(
                    {
                        stop: function() {
                            p = jQuery(this).position();
                            //alert('action=iwak_ajax_action&type=update_option&option_name=iwak_positions&data[' + jQuery(this).attr('id') + '][]=' + p.left + '&data[' + jQuery(this).attr('id') + '][]=' + p.top);
                            jQuery.ajax({
                                type: 'post',
                                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                                data: 'action=iwak_ajax_action&type=update_option&option_name=iwak_positions&data[' + jQuery(this).attr('id') + '][]=' + p.left + '&data[' + jQuery(this).attr('id') + '][]=' + p.top,
                                success: function(results) {
                                    //jQuery('.loaderIcon', contactform).css('display', 'none');
                                }
                            }); // end ajax
                        }
                    });
                });
            </script>
        
        <?php endif;
    }
}

new iWaK_Instant_Position();