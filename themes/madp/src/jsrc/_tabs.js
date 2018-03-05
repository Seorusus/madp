(function ($, Drupal, drupalSettings) {
Drupal.behaviors.tabs = {
    attach: function (context, settings) {
        $('.js-tabs__header').once().click(function(){
            var tab_id = $(this).attr('data-tab');

            $('.js-tabs__header').removeClass('current');
            $('.js-tabs__content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })
    }
};
})(jQuery, Drupal, drupalSettings);