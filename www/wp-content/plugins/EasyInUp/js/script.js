$(document).ready(function(){

  'use strict';
  
  var INOUT = INOUT || {}; 

  INOUT.reg = $('.widget_inout #inout_reg');
  INOUT.auth = $('.widget_inout #inout_auth');
  INOUT.exit = $('.widget_inout #inout_exit');
  
  INOUT.reg_send = INOUT.reg.find('.inout_send');
  INOUT.auth_send = INOUT.auth.find('.inout_send');
  INOUT.exit_send = INOUT.exit.find('.inout_send');
  
  INOUT.reg_link = INOUT.auth.find('.inout_reg_link');
  INOUT.forgot_link = INOUT.auth.find('.inout_forgot_link');
  INOUT.auth_link = INOUT.reg.find('.inout_reg_link');
  
  INOUT.type = $.trim(INOUT_TYPE);
  INOUT.redirect = $.trim(INOUT_REDIRECT);
  
  INOUT.goto = function(redirect) {
    if(redirect) {
      location.href = redirect; 
    } else {
      location.reload();
    }
  }
  
  INOUT.exit_send.bind('click', function(){  
    console.log('exit_send');  
  });
  
  INOUT.auth_send.bind('click', function(){
    console.log('auth_send');
  });
  
  INOUT.reg_send.bind('click', function(){
    console.log('reg_send');
  });
  
  INOUT.reg_link.bind('click', function(){
    console.log('reg_link');
  });
  
  INOUT.auth_link.bind('click', function(){
    console.log('auth_link');
  });
  
  INOUT.forgot_link.bind('click', function(){
    console.log('forgot_link');
  });
  

});