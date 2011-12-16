$(document).ready(function(){

  'use strict';
  
  /*  Init
  ----------------------------------------------------------------------------*/
  
  var INOUT = INOUT || {}; 

  INOUT.reg = $('.widget_inout #inout_reg');
  INOUT.auth = $('.widget_inout #inout_auth');
  INOUT.exit = $('.widget_inout #inout_exit');
  
  INOUT.reg_send = INOUT.reg.find('.inout_send');
  INOUT.auth_send = INOUT.auth.find('.inout_send');
  INOUT.exit_send = INOUT.exit.find('.inout_send');
  
  INOUT.reg_link = INOUT.auth.find('.inout_reg_link');
  INOUT.forgot_link = INOUT.auth.find('.inout_forgot_link');
  INOUT.auth_link = INOUT.reg.find('.inout_auth_link');
  
  INOUT.reload = function(type) {
    type = type || 'auth';
    $.post(PLUGIN_URL+'modules/form.php', {type: type}, function(data){
      console.dir(data);
    });
  }
  
  /*  Actions
  ----------------------------------------------------------------------------*/
  
  INOUT.exit_send.delegate('', 'click', function(){  
    console.log('exit_send');  
  });
  
  INOUT.auth_send.delegate('', 'click', function(){
    console.log('auth_send');
  });
  
  INOUT.reg_send.delegate('', 'click', function(){
    console.log('reg_send');
  });
  
  /*  Change Models
  ----------------------------------------------------------------------------*/
  
  INOUT.reg_link.delegate('', 'click', function(){
    INOUT.reload('reg'); 
  });
  
  INOUT.auth_link.delegate('', 'click', function(){
    INOUT.reload('auth'); 
  });
  
  INOUT.forgot_link.delegate('', 'click', function(){
    INOUT.reload('forgot');  
  });
  

});