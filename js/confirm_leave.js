/**
 * @file
 * confirm_leave.js
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Adds a confirmation message on unload.
   *
   * @type {Object}
   */
  Drupal.behaviors.commerceConfirmLeave = {
    attach: function (context, settings) {
      // @todo review .page selector
      $(context).find('.page').once('commerceConfirmLeave').each(function(){
        // Allow submit buttons.
        var submit = false;
        // @todo review :submit selector
        // @todo handle empty basket
        // @todo handle same page refresh
        $("input[type='submit']").each(function () {
          $(this).click(function () {
            submit = true;
          });
        });
        // @todo review custom error message instead of default one.
        // See custom message support removal
        // https://bugs.chromium.org/p/chromium/issues/detail?id=587940
        window.addEventListener("beforeunload", function (e) {
          if (!submit) {
            var confirmationMessage = settings.commerce_confirm_leave.confirmation_message;
            (e || window.event).returnValue = confirmationMessage; // Gecko, IE Trident, Chrome 34+
            return confirmationMessage;                            // Webkit Safari, Chrome <34
          }
        });

      });
    }
  };

}(jQuery, Drupal));
