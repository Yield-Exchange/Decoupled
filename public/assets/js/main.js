(function ($) {
    "use strict";

    //===== Prealoder
    $(window).on('load', function (event) {
        $('.proloader').delay(500).fadeOut(500);
    });

    $('#mobile-menu-active').meanmenu({
      meanScreenWidth: "991",
      meanMenuContainer: '.menu-prepent',
      onePage: true,
  });

    // sticky
    var wind = $(window);
    var sticky = $('.header-bar-area');
    wind.on('scroll', function () {
        var scroll = wind.scrollTop();
        if (scroll < 100) {
            sticky.removeClass('sticky');
        } else {
            sticky.addClass('sticky');
        }
    });

    $('.venobox').venobox();

        // Smooth Scroll Effect
        $('.main-menu a[href*="#"]:not([href="#"]), .go a[href*="#"]:not([href="#"])').on('click', function () {
          if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                  $('html, body').animate({
                      scrollTop: target.offset().hasOwnProperty("top") ? (target.offset().top - 95) : 95
                  }, 1200, "easeInOutExpo");
                  return false;
              }
          }
      });


      //===== Section Menu Active

      var scrollLink = $('.main-menu a');
      // Active link switching
      $(window).scroll(function () {
          var scrollbarLocation = $(this).scrollTop();

          scrollLink.each(function () {

              var sectionOffset = $(this.hash).offset() && $(this.hash).offset().hasOwnProperty("top") ? ($(this.hash).offset().top - 96) : 96;

              if (sectionOffset <= scrollbarLocation) {
                  $(this).parent().addClass('active');
                  $(this).parent().siblings().removeClass('active');
              }
          });
      });

    /*=========================
      Slick Slider Start
    ===========================*/

    $('.brand-slider').slick({
        //slidesToShow: 4,
        //slidesToScroll: 4,
        slidesPerRow: 4,
        // centerMode: true,
        rows:4,
        autoplay: false,
        arrows: true,
        dots: true,
        cssEase: 'linear',
        prevArrow: '<span class="prev"><i class="fa fa-angle-left fa-4x" aria-hidden="true"></i></span>',
        nextArrow: '<span class="next"><i class="fa fa-angle-right fa-4x" aria-hidden="true"></i></span>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

})(jQuery);
