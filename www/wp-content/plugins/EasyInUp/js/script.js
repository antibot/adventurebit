$(document).ready(function(){

  'use strict';
  
  var INOUT = INOUT || {}; 

  INOUT.reg_send = $('.widget_inout .inout_reg .inout_send');
  INOUT.auth_send = $('.widget_inout .inout_auth .inout_send');
  INOUT.exit = $('.widget_inout .inout_exit .inout_exit_link');
  
  INOUT.reg = $('.widget_inout .inout_reg');
  INOUT.auth = $('.widget_inout .inout_auth');
  
  INOUT.type = $.trim(INOUT_TYPE);
  INOUT.redirect = $.trim(INOUT_REDIRECT);
  
  INOUT.goto = function(redirect) {
    if(redirect) {
      location.href = redirect; 
    } else {
      location.reload();
    }
  }
  
  INOUT.exit.bind('click', function(){  
  
    $.get('http://192.168.0.124/wp-login.php?action=logout&_wpnonce=11f32769eb', {}, function(data){
      INOUT.goto(INOUT.redirect); 
    });
    
  });
  
  INOUT.auth_send.bind('click', function(){
    console.log('auth');
  });
  
  INOUT.reg_send.bind('click', function(){
    console.log('reg');
  });
  

});