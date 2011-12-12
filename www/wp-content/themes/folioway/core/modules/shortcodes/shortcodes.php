<?php
/**
 * Theme Short-code Functions
 */

/* ------------------------------------------------------------------------------
        Columns
    ------------------------------------------------------------------------------ */
     
function shortcode_one_half( $atts, $content = null ) {
   return '<div class="one_half">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_half', 'shortcode_one_half');

function shortcode_one_half_last( $atts, $content = null ) {
   return '<div class="one_half last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_half_last', 'shortcode_one_half_last');

function shortcode_one_third( $atts, $content = null ) {
   return '<div class="one_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_third', 'shortcode_one_third');

function shortcode_one_third_last( $atts, $content = null ) {
   return '<div class="one_third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_third_last', 'shortcode_one_third_last');

function shortcode_one_fourth( $atts, $content = null ) {
   return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_fourth', 'shortcode_one_fourth');

function shortcode_one_fourth_last( $atts, $content = null ) {
   return '<div class="one_fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('one_fourth_last', 'shortcode_one_fourth_last');

function shortcode_three_fourth( $atts, $content = null ) {
   return '<div class="three_fourth">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fourth', 'shortcode_three_fourth');

function shortcode_three_fourth_last( $atts, $content = null ) {
   return '<div class="three_fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('three_fourth_last', 'shortcode_three_fourth_last');

function shortcode_two_third( $atts, $content = null ) {
   return '<div class="two_third">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_third', 'shortcode_two_third');

function shortcode_two_third_last( $atts, $content = null ) {
   return '<div class="two_third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('two_third_last', 'shortcode_two_third_last');

function shortcode_two_fifth( $atts, $content = null ) {
   return '<div class="two_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_fifth', 'shortcode_two_fifth');

function shortcode_two_fifth_last( $atts, $content = null ) {
   return '<div class="two_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('two_fifth_last', 'shortcode_two_fifth_last');

function shortcode_three_fifth( $atts, $content = null ) {
   return '<div class="three_fifth">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_fifth', 'shortcode_three_fifth');

function shortcode_three_fifth_last( $atts, $content = null ) {
   return '<div class="three_fifth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode('three_fifth_last', 'shortcode_three_fifth_last');

/* ------------------------------------------------------------------------------
        Buttons
    ------------------------------------------------------------------------------ */

function shortcode_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'link'      => '#',
    ), $atts));
	return "<a class=\"button\" href=\"" .$link. "\"><span>" .do_shortcode($content). "</span></a>";
}
add_shortcode('button', 'shortcode_button');

function shortcode_fancy_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'link'      => '#',
        'style'      => 'white',
        'text'      => 'Button Text',
        'desc'      => 'Some description',
    ), $atts));
	return '<a class="fancybtn fancybtn_'. $style. '" href="' .$link. '"><span class="icon"></span><span class="btntxt">'. $text. '<span>'. $desc. '</span></span></a>';
}
add_shortcode('fancy_button', 'shortcode_fancy_button');

/* ------------------------------------------------------------------------------
        Lists
    ------------------------------------------------------------------------------ */

function shortcode_check_list( $atts, $content = null ) {
    $content = str_replace(array('<ul>', '</ul>'), array('<ul class="checklist customlist">','</ul>'), do_shortcode($content));
     return $content;
}
add_shortcode('check_list', 'shortcode_check_list');

function shortcode_arrow_list( $atts, $content = null ) {
    $content = str_replace(array('<ul>', '</ul>'), array('<ul class="arrowlist customlist">','</ul>'), do_shortcode($content));
     return $content;
}
add_shortcode('arrow_list', 'shortcode_arrow_list');

/* ------------------------------------------------------------------------------
        Boxes
    ------------------------------------------------------------------------------ */

function shortcode_box_download( $atts, $content = null ) {
   return '<div class="box_download">' . do_shortcode($content) . '</div>';
}
add_shortcode('box_download', 'shortcode_box_download');

function shortcode_box_info( $atts, $content = null ) {
   return '<div class="box_info">' . do_shortcode($content) . '</div>';
}
add_shortcode('box_info', 'shortcode_box_info');

function shortcode_box_warning( $atts, $content = null ) {
   return '<div class="box_warning">' . do_shortcode($content) . '</div>';
}
add_shortcode('box_warning', 'shortcode_box_warning');

function shortcode_box_note( $atts, $content = null ) {
   return '<div class="box_note">' . do_shortcode($content) . '</div>';
}
add_shortcode('box_note', 'shortcode_box_note');

function shortcode_fancy_box( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'title'      => '',
        ), $atts));
    $heading = empty($title) ? '' : '<h5>'. $title. '</h5>'; 
    return '<div class="fancybox">'. $heading . do_shortcode($content) . '</div>';
}
add_shortcode('fancy_box', 'shortcode_fancy_box');

/* ------------------------------------------------------------------------------
        Other
    ------------------------------------------------------------------------------ */
    
function shortcode_dropcap( $atts, $content = null ) {
   return '<span class="dropcap">' . do_shortcode($content) . '</span>';
}
add_shortcode('dropcap', 'shortcode_dropcap');

function shortcode_pullquote_left( $atts, $content = null ) {
   return '<span class="pullquote_left">' . do_shortcode($content) . '</span>';
}
add_shortcode('pullquote_left', 'shortcode_pullquote_left');

function shortcode_pullquote_right( $atts, $content = null ) {
   return '<span class="pullquote_right">' . do_shortcode($content) . '</span>';
}
add_shortcode('pullquote_right', 'shortcode_pullquote_right');

function shortcode_toggle( $atts, $content = null)
{
 extract(shortcode_atts(array(
        'title'      => '',
        ), $atts));
   return '<h5 class="toggle"><a href="#">'.$title.'</a></h5><div class="toggle_content"><div class="block">'. do_shortcode($content) . '</div></div>';
}
add_shortcode('toggle', 'shortcode_toggle');

function shortcode_highlight1( $atts, $content = null)
{

   return '<span class="highlight1">'. do_shortcode($content) . '</span>';
}
add_shortcode('highlight1', 'shortcode_highlight1');

function shortcode_highlight2( $atts, $content = null)
{

   return '<span class="highlight2">'. do_shortcode($content) . '</span>';
}
add_shortcode('highlight2', 'shortcode_highlight2');

function shortcode_frame_left( $atts, $content = null)
{
    extract(shortcode_atts(array(
    'frame' => 1,
    'width' => 0,
    'height' => 0
    ), $atts));
    if(!empty($width) || !empty($height)) {
        $content = THEME_URL.'/core/thumb.php?src='.$content.'&w='.$width.'&h='.$height.'&zc=1&q=90';
    }
    $class = $frame ? 'frame alignleft' : 'alignleft';
    return '<span class="'. $class. '"><img src="' . do_shortcode($content) . '" /></span>';
}
add_shortcode('frame_left', 'shortcode_frame_left');

function shortcode_frame_right( $atts, $content = null)
{
    extract(shortcode_atts(array(
    'frame' => 1,
    'width' => 0,
    'height' => 0
    ), $atts));
    if(!empty($width) || !empty($height)) {
        $content = THEME_URL.'/core/thumb.php?src='.$content.'&w='.$width.'&h='.$height.'&zc=1&q=90';
    }
    $class = $frame ? 'frame alignright' : 'alignright';
    return '<span class="'. $class. '"><img src="' . do_shortcode($content) . '" /></span>';
}
add_shortcode('frame_right', 'shortcode_frame_right');

function shortcode_frame_center( $atts, $content = null)
{
    extract(shortcode_atts(array(
    'frame' => 1,
    'width' => 0,
    'height' => 0
    ), $atts));
    if(!empty($width) || !empty($height)) {
        $content = THEME_URL.'/core/thumb.php?src='.$content.'&w='.$width.'&h='.$height.'&zc=1&q=90';
    }
    $class = $frame ? 'frame aligncenter' : 'aligncenter';
    return '<span class="'. $class. '"><img src="' . do_shortcode($content) . '" /></span>';
}
add_shortcode('frame_center', 'shortcode_frame_center');

function shortcode_divider( $atts, $content = null)
{
   return '<div class="divider"></div>';
}
add_shortcode('divider', 'shortcode_divider');

function shortcode_divider_top( $atts, $content = null)
{
   return '<div class="divider top"><a href="#">Top</a></div>';
}
add_shortcode('divider_top', 'shortcode_divider_top');

function etdc_tab_group( $atts, $content ){
$GLOBALS['tab_count'] = 0;
do_shortcode( $content );
if( is_array( $GLOBALS['tabs'] ) ){
foreach( $GLOBALS['tabs'] as $tab ){
$tabs[] = '<li><a class="" href="#">'.$tab['title'].'</a></li>';
$panes[] = '<div class="pane">'.$tab['content'].'</div>';
}
$return = "\n".'<!-- the tabs --><ul class="tabs">'.implode( "\n", $tabs ).'</ul>'."\n".'<!-- tab "panes" --><div class="panes">'.implode( "\n", $panes ).'</div>'."\n";
}
return $return;
}
add_shortcode( 'tabgroup', 'etdc_tab_group' );

function etdc_tab( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Tab %d'
), $atts));
$x = $GLOBALS['tab_count'];
$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' =>  $content );
$GLOBALS['tab_count']++;
}
add_shortcode( 'tab', 'etdc_tab' );

function shortcode_contact_form( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'email'      => '',
        'rows'      => 5,
    ), $atts));
    
    global $iwak;
    $out = $iwak->contactform($email, array('rows'=>$rows));
    
    return $out;
}
add_shortcode('contactform', 'shortcode_contact_form');

function shortcode_testimonial( $atts, $content = null ) {
    
    global $iwak;
    $out = $iwak->testimonial();
    
    return $out;
}
add_shortcode('testimonial', 'shortcode_testimonial');

function shortcode_super_title( $atts, $content = null ) {
   return '<span class="suptitle">'. do_shortcode($content) . '</span>';
}
add_shortcode('super_title', 'shortcode_super_title');

function shortcode_social_links( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'username'      => '',
        'rss'   =>  0,
    ), $atts));
    
    global $iwak;
    $out = $iwak->social_links(array('username'=>$username, 'rss'=>$rss, 'echo'=>0));
    
    return $out;
}
add_shortcode('social_links', 'shortcode_social_links');

function shortcodes_runtime() {
    echo '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'/core/modules/shortcodes/css/shortcodes.reset.css" media="screen" />';
    echo '<script type="text/javascript" src="'.THEME_URL.'/core/modules/shortcodes/js/jquery.tools.min.js"></script>';
    echo '<script type="text/javascript" src="'.THEME_URL.'/core/modules/shortcodes/js/shortcodes.js"></script>';
}
add_action('wp_head', 'shortcodes_runtime');
?>