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
// jQuery for page scrolling feature - requires jQuery Easing plugin
(function($) {
    $(document).ready(function(){
        $(".s-paragraph-menu a").click(function(){
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
})(jQuery);
(function($) {
$(document).ready(function(){

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 700) {
            $('.s-scroll-top').fadeIn();
        } else {
            $('.s-scroll-top').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.s-scroll-top').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

});
})(jQuery);
//# sourceMappingURL=scripts.js.map
