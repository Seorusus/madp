(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.accordion = {
        attach: function (context, settings) {
            var $activeItem = $('.hierarchical-taxonomy-menu .menu-item--active');
            var $parentActive = $activeItem.parent().parent();
            var $parentActiveIndex = (($activeItem.length > 0)
                ? $('.hierarchical-taxonomy-menu > .menu-item').index($parentActive)
                : 0
            );
            console.log($activeItem);
            console.log($parentActiveIndex);
            $('.hierarchical-taxonomy-menu').accordion({
                animate: 200,
                active: $parentActiveIndex
            } );
        }
    };
})(jQuery, Drupal, drupalSettings);
