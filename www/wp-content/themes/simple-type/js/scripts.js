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
  		//Bind parent form submit
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
  COMMENT.text = COMMENT.form.find('textarea');
  COMMENT.author = COMMENT.form.find('#author input');
  COMMENT.email = COMMENT.form.find('#email input');
  
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

	COMMENT.text.textareaCount(options);
	COMMENT.text.TextAreaResizer();
	
	COMMENT.author.defaultValue('Name');
  COMMENT.email.defaultValue('E-mail');
  	
});