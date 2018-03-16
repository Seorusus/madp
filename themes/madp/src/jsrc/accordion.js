(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.accordion = {
        attach: function (context, settings) {
            var $activeItem = $('.hierarchical-taxonomy-menu .menu-item--active');
            var $parentActive = $activeItem.parent().parent();
            var $parentActiveIndex = $('.hierarchical-taxonomy-menu > .menu-item').index($parentActive);

            $('.hierarchical-taxonomy-menu').accordion({
                animate: 200,
                active: $parentActiveIndex
            } );
        }
    };
})(jQuery, Drupal, drupalSettings);
