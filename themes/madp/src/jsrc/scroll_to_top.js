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