$.fn.serializeObject = function()
{
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};

$(document).ready(function(){

  'use strict';
  
  /*  Init
  ----------------------------------------------------------------------------*/
  
  var INOUT = INOUT || {}; 

  INOUT.reg = $('.widget_inout #inout_reg');
  INOUT.auth = $('.widget_inout #inout_auth');
  INOUT.exit = $('.widget_inout #inout_exit');
  INOUT.forgot = $('.widget_inout #inout_forgot');
  
  INOUT.screen = $('.widget_inout .inout_screen');
  INOUT.loading = $('.widget_inout .inout_loading');
  
  INOUT.content = $('.widget_inout .inout_content');
  INOUT.message = $('.widget_inout .inout_message');
  
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
    INOUT.progress(true);
    
    type = type || 'auth';

    $.post(PLUGIN_URL+'modules/form.php', {type: type}, function(data) {
      INOUT.content.html(data); 
      INOUT.progress(false);
    });
  }

  INOUT.post = function(option) {
    INOUT.progress(true);
    
    option = option || {};
    
    var form = option.form; 
    var fields = form.serializeObject();
    
    $.post(PLUGIN_URL+'modules/validation.php', fields, function(data) {
      console.dir(data);
      INOUT.progress(false);
    });  
  }
  
  /*  Actions
  ----------------------------------------------------------------------------*/
  
  INOUT.forgot.delegate('', 'click', function(){  
    var form = $(this);
    INOUT.post({
      form: form
    });
    return false;
  });
  
  INOUT.auth.delegate('', 'submit', function(){
    var form = $(this);
    INOUT.post({
      form: form
    });
    return false;
  });
  
  INOUT.reg.delegate('', 'submit', function(){
    var form = $(this);
    INOUT.post({
      form: form
    });
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