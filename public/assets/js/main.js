
(function ($) {
    
    "use strict";

  
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass('show');
        });
        return false;
    });


   
    if ($('.search-box-outer').length) {
        $('.search-box-outer').on('click', function () {
            $('body').addClass('search-active');
        });
        $('.close-search').on('click', function () {
            $('body').removeClass('search-active');
        });
    }


     
    $(document).on('ready', function () {
        $("[data-background]").each(function () {
            $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
        });
    });


   
    $('.sidebar-btn').on('click', function() {
        $('.sidebar-popup').addClass('open');
        $('.sidebar-wrapper').addClass('open');
    });
    $('.close-sidebar-popup, .sidebar-popup').on('click', function() {
        $('.sidebar-popup').removeClass('open');
        $('.sidebar-wrapper').removeClass('open');
    });



    new WOW().init();


 
    $('.hero-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: true,
        margin: 0,
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        items: 1,
        navText: [
            "<i class='far fa-long-arrow-left'></i>",
            "<i class='far fa-long-arrow-right'></i>"
        ],

        onInitialized: function(event) {
        var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
        doAnimations($firstAnimatingElements);
        },

        onChanged: function(event){
        var $firstAnimatingElements = $('.owl-item').eq(event.item.index).find("[data-animation]");
        doAnimations($firstAnimatingElements);
        }
    });

 
    function doAnimations(elements) {
		var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		elements.each(function () {
			var $this = $(this);
			var $animationDelay = $this.data('delay');
			var $animationDuration = $this.data('duration');
			var $animationType = 'animated ' + $this.data('animation');
			$this.css({
				'animation-delay': $animationDelay,
				'-webkit-animation-delay': $animationDelay,
                'animation-duration': $animationDuration,
                '-webkit-animation-duration': $animationDuration,
			});
			$this.addClass($animationType).one(animationEndEvents, function () {
				$this.removeClass($animationType);
			});
		});
	}


  
    $('.testimonial-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });



    $('.partner-slider').owlCarousel({
        loop: true,
        margin: 70,
        nav: false,
        navText: [
            "<i class='icofont-long-arrow-left'></i>",
            "<i class='icofont-long-arrow-right'></i>"
        ],
        dots: false,
        autoplay: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 6
            }
        }
    });   



    $('.counter').countTo();
    $('.counter-box').appear(function () {
        $('.counter').countTo();
    }, {
        accY: -100
    });



    $(".popup-gallery").magnificPopup({
        delegate: '.popup-img',
        type: 'image',
        gallery: {
            enabled: true
        },
    });

    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });



 
    $(window).scroll(function () {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            $("#scroll-top").addClass('active');
        } else {
            $("#scroll-top").removeClass('active');
        }
    });

    $("#scroll-top").on('click', function () {
        $("html, body").animate({ scrollTop: 0 }, 1500);
        return false;
    });


  
    $(window).scroll(function () {
        if ($(this).scrollTop() > 140) {
            $('.navbar').addClass("fixed-top");
        } else {
            $('.navbar').removeClass("fixed-top");
        }
    });



  $('[data-countdown]').each(function() {

    let finalDate = $(this).data('countdown');

    $(this).countdown(finalDate)

    .on('update.countdown', function(event) {

        $(this).html(event.strftime(

            '<div class="time-wrap">' +

            '<span class="time">' +
            '<span>%-D</span>' +
            '<span class="unit">Day%!D</span>' +
            '</span>' +

            '<span class="divider">:</span>' +

            '<span class="time">' +
            '<span>%H</span>' +
            '<span class="unit">Hour%!H</span>' +
            '</span>' +

            '<span class="divider">:</span>' +

            '<span class="time">' +
            '<span>%M</span>' +
            '<span class="unit">Min%!M</span>' +
            '</span>' +

            '<span class="divider">:</span>' +

            '<span class="time">' +
            '<span>%S</span>' +
            '<span class="unit">Sec%!S</span>' +
            '</span>' +

            '</div>'
        ));

    })

    .on('finish.countdown', function() {

        $(this).html(
            '<div class="conference-live">' +
                '<span>THE CONFERENCE IS LIVE</span>' +
            '</div>'
        );

    });

});


 
    let date = new Date().getFullYear();
    $("#date").html(date);


 
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


})(jQuery);










