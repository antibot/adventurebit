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
  
  /*  Functions
  ----------------------------------------------------------------------------*/
  
  INOUT.empty_error = function() {
    INOUT.content.find('.inout_error').empty();
  }
  
  INOUT.progress = function(type){
    if(type) {
      INOUT.screen.show();
      INOUT.loading.show();  
    } else {
      INOUT.screen.hide();
      INOUT.loading.hide();  
    }
  }

  INOUT.reload = function(what) {
    INOUT.progress(true);
    
    what = what || 'auth';

    $.post(INOUT_PLUGIN_URL+'modules/form.php', {what: what}, function(data) {
      INOUT.content.html(data); 
      INOUT.progress(false);
    });
  }

  INOUT.post = function(option) {
    INOUT.progress(true);
    INOUT.empty_error();
     
    option = option || {};
    
    INOUT.message.empty();
    
    var form = option.form; 
    var fields = form.serializeObject();
    var type = fields.type;
    
    $.post(INOUT_PLUGIN_URL+'modules/validation.php', fields, function(data) {
    
      if(data) {
        var info = $.evalJSON(data) || null;
        
        if(info) {
          if(info.success) {
            INOUT.message.html(info.message);  
            if(type == 'auth' || type == 'reg') {
              var redirect = info.redirect;
              if(redirect) {                       
                location.href = redirect;
              } 
            }
          } else {
            $.each(info, function(i, what){
              INOUT.content.find('.inout_'+what.name).find('.inout_error').html(what.message);    
            });  
          }
        }
      }
      
      INOUT.progress(false);
    });  
  }
  
  /*  Error
  ----------------------------------------------------------------------------*/
  
  INOUT.content.delegate('.inout_error', 'click', function() {
    INOUT.empty_error();   
  });
  
  /*  Actions
  ----------------------------------------------------------------------------*/
  
  INOUT.forgot.delegate('', 'submit', function(){  
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