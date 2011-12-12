<?php
global $preset_buttons, $easing_list;

$preset_buttons = array('white'=>__('White', THEME_NAME),
                                'red'=>__('Red', THEME_NAME),
                                'yellow'=>__('Yellow', THEME_NAME),
                                'blue'=>__('Blue', THEME_NAME),
                                'azure'=>__('Azure', THEME_NAME),
                                'download'=>__('Download', THEME_NAME),
                                'purchase'=>__('Purchase', THEME_NAME),
                            );

$easing_list = array('linear'=>'linear', 
                        'swing'=>'swing', 
                        'easeInBounce'=>'easeInBounce',
                        'easeOutBounce'=>'easeOutBounce', 
                        'easeInOutBounce'=>'easeInOutBounce', 
                        'easeInQuad'=>'easeInQuad',
                        'easeOutQuad'=>'easeOutQuad', 
                        'easeInOutQuad'=>'easeInOutQuad', 
                        'easeInCubic'=>'easeInCubic',
                        'easeOutCubic'=>'easeOutCubic', 
                        'easeInOutCubic'=>'easeInOutCubic', 
                        'easeInQuart'=>'easeInQuart',
                        'easeOutQuart'=>'easeOutQuart', 
                        'easeInOutQuart'=>'easeInOutQuart', 
                        'easeInQuint'=>'easeInQuint',
                        'easeOutQuint'=>'easeOutQuint', 
                        'easeInOutQuint'=>'easeInOutQuint', 
                        'easeInSine'=>'easeInSine',
                        'easeOutSine'=>'easeOutSine', 
                        'easeInOutSine'=>'easeInOutSine', 
                        'easeInExpo'=>'easeInExpo',
                        'easeOutExpo'=>'easeOutExpo', 
                        'easeInOutExpo'=>'easeInOutExpo', 
                        'easeInCirc'=>'easeInCirc',
                        'easeOutCirc'=>'easeOutCirc', 
                        'easeInOutCirc'=>'easeInOutCirc', 
                        'easeInElastic'=>'easeInElastic',
                        'easeOutElastic'=>'easeOutElastic', 
                        'easeInOutElastic'=>'easeInOutElastic', 
                        'easeInBack'=>'easeInBack',
                        'easeOutBack'=>'easeOutBack', 
                        'easeInOutBack'=>'easeInOutBack', 
                    );

$options = array();

$options[] = array('name'=>'featured[autoplay]', 
                            'title'=>__('Autoplay', THEME_NAME), 
                            'type'=>'radio', 
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1
                        );

$options[] = array('name'=>'featured[autoplay_duration]', 
                            'title'=>__('Autoplay Duration', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('How long each featured item stay for. In seconds', THEME_NAME),
                            'std'=>4,
                            'class'=>'small-text'
                        );
                        
$options[] = array('name'=>'featured[pause_on_hover]', 
                            'title'=>__('Pause on Hover', THEME_NAME), 
                            'desc'=>__('Stop animation when mouse over', THEME_NAME),
                            'type'=>'radio', 
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                        );

$options[] = array('name'=>'featured[autoload]', 
                            'title'=>__('Autoload', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Load eligible featured items automatically', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('yes', THEME_NAME),
                                                  0=>__('no', THEME_NAME),
                                                 ),
                            'alt'=>1
                        );

$options[] = array('name'=>'featured[loadbefore]', 
                            'title'=>__('Autoload Position', THEME_NAME), 
                            'type'=>'radio', 
                            'desc'=>__('Auto loaded featured item will be add to before existent items or after', THEME_NAME),
                            'std'=>1,
                            'items'=>array(1=>__('before', THEME_NAME),
                                                  0=>__('after', THEME_NAME),
                                                 ),
                        );

$options[] = array('name'=>'featured[number_of_posts]', 
                            'title'=>__('Max Number of Featured Contents', THEME_NAME), 
                            'type'=>'text', 
                            'desc'=>__('How many featured items are to be displayed at most, where 0 means no limitation', THEME_NAME),
                            'std'=>0,
                            'class'=>'small-text',
                            'alt'=>1
                        );

// Default settings of each featured entry                        

    $options[] = array('name'=>'featured_defaults[animation][in_duration]', 
                                'title'=>__('Fade-in Duration', THEME_NAME), 
                                'type'=>'text',
                                'std'=>800,
                                'desc'=>__('How long the fade animation last, milliseconds', THEME_NAME),
                                'class'=>'small-text',
                            'alt'=>1
                            );
 
    $options[] = array('name'=>'featured_defaults[animation][out_duration]', 
                                'title'=>__('Fade-out Duration', THEME_NAME), 
                                'type'=>'text',
                                'std'=>800,
                                'class'=>'small-text',
                            );
                            
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Settings',
                           'desc'=>'General options of featured panel',
                           'source'=>'featured',
                           'options'=>$options
                        );

?>