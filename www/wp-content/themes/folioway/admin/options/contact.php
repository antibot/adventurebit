<?php

$options = array();

$options[] = array('title'=>__('Useful Data', THEME_NAME), 
                            'type'=>'multi', 
                            'desc'=>'%agent%    -     website name, indicate where the mail came from
                                    <br>%name%     -    sender name
                                    <br>%email%      -    sender email address
                                    <br>%message% -    the message
                                    <br><br><em>You may use these data in Mail Subject, Mail Body</em><br>',
                            'alt'=>1
                        );

$options[] = array('name'=>'contact[email_subject]', 
                            'title'=>__('EMail Subject', THEME_NAME),
                            'desc'=>__('The subject of "contact us" mails', THEME_NAME),
                            'type'=>'text', 
                            'std'=>'Message from %name%', 
                            'alt'=>1,
                        );
                        
$options[] = array('name'=>'contact[email_address]', 
                            'title'=>__('EMail Address', THEME_NAME),
                            'desc'=>__('The mail address where "contact us" mails will be delivered to', THEME_NAME),
                            'type'=>'text', 
                            'std'=>get_userdata(1)->user_email, 
                            'alt'=>1
                        );          
                        
$options[] = array('name'=>'contact[email_template]', 
                            'title'=>__('EMail Body', THEME_NAME),
                            'desc'=>__('The template of "contact us" mails, HTML supported', THEME_NAME),
                            'type'=>'textarea', 
                            'std'=>'%message%', 
                            'alt'=>1
                        );          
                        
global $groups;
if(!isset($groups))
    $groups = array();
    
$groups[] = array('title'=>'Contact',
                           'desc'=>'Email Subject, Email Address, Email Template',
                           'options'=>$options
                        );
//polar_register_options($options, $metainfo);

?>