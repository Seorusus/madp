/* global jQuery */

(function ($) {

    "use strict";
    $(document).ready(function () {

        /* navbar */
        $(".navbar-toggle").click(function(){
            $("#navbar-collapse-mobile").toggleClass("active");
        });

        $('.navbar-toggle').on('click', function(e) {
            $(this).toggleClass('active-nav');
        });

        /* tooltips */
        $(".tooltip--triangle").click(function(){
            return false;
        });

        /* lien coté entrant */
        $(".btn-rappeler").hover(
            function() {
                $( this ).addClass( "hover" );
            }, function() {
                $( this ).removeClass( "hover" );
            }
        );

        /* lien coté entrant tablette + mobile*/
        if ( $(window).width() <= 1024 ) {
            $(".btn-rappeler" ).click(function() {
                $( this ).toggleClass( "hover" );
            });
        }

        /* lien coté entrant tablette + mobile*/
        if ( $(window).width() < 1023 ) {
            /* bloc simu MADP vous informe > mobile / tablette */
            $('.collapsible.closed > .content').hide();
            $('.block-right h3').addClass('title');


            $('.collapsible > .title')
                .click(function() {
                    $(this).siblings('.ct-p').fadeToggle('normal');
                    $(this).parent('.collapsible').toggleClass('open');
                    $(this).parent('.collapsible').toggleClass('closed');
                });
        }
    });

    /* sticky menu */
    $(window).on('scroll', function(){
        if ( $(window).width() < 1024 ) {
            if(jQuery(this).scrollTop() > 0) {
                $('header').addClass('sticky');
                $('.logo').removeClass('l-animate');
            } else {
                $('header').removeClass('sticky');
                $('.logo').addClass('l-animate');
            }
        }
    });

    // force remove class sticky
    $(window).on('resize', function(){
        if ( $(window).width() > 1024 ) {
            $('header').removeClass('sticky');
            $('.logo').addClass('l-animate');
        }
    });


})(jQuery);

