(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.accordion = {
        attach: function (context, settings) {
            var act = 0;
            $('#edit-tid .form-radios > ul').accordion({
                animate: 200,
                // activate: function(event, ui) {
                //     localStorage.setItem("accIndex", $(this).accordion("option", "active"));
                // },
                // active: parseInt(localStorage.getItem("accIndex"))
            } );
        }
    };
})(jQuery, Drupal, drupalSettings);
