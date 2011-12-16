$(document).ready(function(){

  'use strict';
  
  /*  Init
  ----------------------------------------------------------------------------*/
  
  var INOUT = INOUT || {}; 

  INOUT.reg = $('.widget_inout #inout_reg');
  INOUT.auth = $('.widget_inout #inout_auth');
  INOUT.exit = $('.widget_inout #inout_exit');
  INOUT.forgot = $('.widget_inout #inout_forgot');
  
  INOUT.screen = $('.widget_inout .inout-screen');
  INOUT.loading = $('.widget_inout .inout-loading');
  
  INOUT.content = $('.widget_inout .inout-content');
  
  INOUT.progress = function(type){
    if(type) {
      INOUT.screen.show();
      INOUT.loading.show();  
    } else {
      INOUT.screen.hide();
      INOUT.loading.hide();  
    }
  }

  INOUT.reload = function(type) {
    type = type || 'auth';
    
    INOUT.progress(true);
    
    $.post(PLUGIN_URL+'modules/form.php', {type: type}, function(data){
      INOUT.content.html(data); 
      INOUT.progress(false);
    });
  }
  
  /*  Actions
  ----------------------------------------------------------------------------*/
  
  INOUT.forgot.delegate('', 'click', function(){  
    var serialize = $(this).serialize();
    console.dir(serialize);
    return false;
  });
  
  INOUT.auth.delegate('', 'submit', function(){
    var serialize = $(this).serialize();
    console.dir(serialize);
    return false;
  });
  
  INOUT.reg.delegate('', 'submit', function(){
    var serialize = $(this).serialize();
    console.dir(serialize);
    return false;
  });
  
  /*  Change Models
  ----------------------------------------------------------------------------*/
  
  INOUT.content.delegate('.inout_reg_link', 'click', function(){
    INOUT.reload('reg'); 
  });
  
  INOUT.content.delegate('.inout_auth_link', 'click', function(){
    INOUT.reload('auth'); 
  });
  
  INOUT.content.delegate('.inout_forgot_link', 'click', function(){
    INOUT.reload('forgot');  
  });
  

});