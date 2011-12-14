jQuery.fn.defaultValue = function(text){
  return this.each(function(){
  	if(this.type != 'text' && this.type != 'password' && this.type != 'textarea') {
  		return;
  	}
  	
  	var fld_current=this;
  	
    if(this.value=='') {
  		this.value=text;
  	} else {
  		return;
  	}
  	
  	$(this).focus(function() {
  		if(this.value==text || this.value=='') {
  			this.value='';
  		}
  	});
  	
  	$(this).blur(function() {
  		if(this.value==text || this.value=='') {
  			this.value=text;
  		}
  	});
  
  	$(this).parents("form").each(function() {
  		$(this).submit(function() {
  			if(fld_current.value==text) {
  				fld_current.value='';
  			}
  		});
  	});
  	
  });
};

$(document).ready(function(){

  var COMMENT = {};
  COMMENT.cancel_link = $('a#cancel-comment-reply-link');
  COMMENT.cancel_reply = $('a.cancel-comment-reply-link');
  COMMENT.reply = $('a.comment-reply-link');
  COMMENT.form = $('#comment-form');
  COMMENT.comment = COMMENT.form.find('textarea');
  COMMENT.author = COMMENT.form.find('#author input');
  COMMENT.email = COMMENT.form.find('#email input');
  COMMENT.submit = COMMENT.form.find('#submit-comment'); 
  COMMENT.fields = COMMENT.form.find('textarea, input'); 
  COMMENT.border_color = COMMENT.comment.css('border-color'); 
  COMMENT.error = function() {     
    COMMENT.fields.css('border-color', 'red');
    COMMENT.form.effect("bounce", {
      times: 5,
      direction: 'right',
      distance: 10
    },100,function() {
    });
    COMMENT.submit.removeAttr('disabled');
    
    COMMENT.fields.focus(function(){
      COMMENT.fields.css('border-color', COMMENT.border_color);
      //$(this).css('border-color', COMMENT.border_color);
    });
    
  }
  
  COMMENT.cancel_reply.hide();  
   
  COMMENT.reply.click(function() {
    COMMENT.cancel_reply.show();  
  });

  COMMENT.cancel_reply.click(function() {      
    COMMENT.cancel_reply.hide();  
    COMMENT.cancel_link.click();
  });
  
  var options = {
    'maxCharacterSize': 200,
    'originalStyle': 'originalTextareaInfo',
    'warningStyle' : 'warningTextareaInfo',
    'warningNumber': 40,
    'displayFormat' : '#input/#max | #words words'
	}

	COMMENT.comment.textareaCount(options);
	COMMENT.comment.TextAreaResizer();
	
	COMMENT.author.defaultValue('Name');
  COMMENT.email.defaultValue('E-mail');
 
  COMMENT.form.submit(function(){

    COMMENT.submit.attr('disabled', 'disabled');
    COMMENT.fields.blur();
    
    var obj = COMMENT.form.serialize();
  
    jQuery.ajax({
      type: 'POST',
      url: COMMENT.form.attr('action'),
      data: obj,
      success: function(data) {
        if(data == 'success') {
          location.reload();
        } else {
          COMMENT.error();    
        }
      },
      error: function(data) {
        COMMENT.error();  
      }
    });   
      
    return false;
   
  });
  	
});