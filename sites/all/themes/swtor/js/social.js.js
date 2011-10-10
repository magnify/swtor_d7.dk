/*
 * Make sure that fb like is loaded for elements that ajax into the page.
 */
(function ($) {
  Drupal.behaviors.swtor = {
    attach: function (context, settings) {
      FB.XFBML.parse();
    }
  };
})(jQuery);

