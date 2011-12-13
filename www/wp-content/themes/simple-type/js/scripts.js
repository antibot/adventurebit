$(document).ready(function(){

  var COMMENT = {};
  COMMENT.cancel_link = $('a#cancel-comment-reply-link');
  COMMENT.cancel_reply = $('a.cancel-comment-reply-link');
  COMMENT.reply = $('a.comment-reply-link');
  
  COMMENT.cancel_reply.hide();  
   
  COMMENT.reply.click(function() {
    COMMENT.cancel_reply.show();  
  });

  COMMENT.cancel_reply.click(function() {      
    COMMENT.cancel_reply.hide();  
    COMMENT.cancel_link.click();
  });

});