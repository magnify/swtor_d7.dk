(function ($) {

$(document).ready(function() {
  var account = $('#swtor-account');
  
  // Add click handler.
  account.click(function() {
    // Can't use toggle, as logout link floats down (in animation).
    var content = $('.content', account);
    if (content.is(":visible")) {
      content.hide();
    }
    else {
      content.fadeIn();
    }

    return false;
  });
});

})(jQuery);