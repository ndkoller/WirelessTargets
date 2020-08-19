!function(e){"use strict";e(window).on("load",function(){e("#js-preloader").delay(0).fadeOut(),e("#js-preloader-overlay").delay(200).fadeOut("slow")}),e.fn.exists=function(){return this.length>0};var a=e(".main-nav"),s=e(".js-rooms--grid"),t=e(".js-main-slider"),i=e(".js-room-single-slick"),l=e(".js-rooms-related"),n=e("#instagram-feed"),r=e("#instagram-feed-tagged"),o=e(".mp_single-img"),m=e(".mp_gallery"),p=e(".mp_iframe"),y=e(".gm-map"),g=e(".js-testimonials-slick"),c={initialize:function(){this.stickyHeader(),this.headerNav(),this.isotope(),this.slickSlider(),this.instagramFeed(),this.googleMap(),this.magnificPopupInit(),this.animateOnScroll(),this.miscScripts()},stickyHeader:function(){var a=e("#header").innerHeight();e(".page-heading").css("padding-top",a),e("#header").jPinning({offset:100})},headerNav:function(){if(a.exists()){var s=e(".site-wrapper"),t=e(".main-nav__list"),i=e(".main-nav__list > li"),l=e("#header-mobile__toggle"),n=e(".js-search-form-control"),r=e("html, body");e(".nav-secondary__login").clone().appendTo(t),l.on("click",function(){s.toggleClass("site-wrapper--has-overlay"),e(this).toggleClass("burger-menu-icon--active")}),e(".site-overlay, .main-nav__back").on("click",function(){s.toggleClass("site-wrapper--has-overlay")}),e(".site-overlay").on("click",function(e){e.preventDefault(),s.removeClass("site-wrapper--has-overlay-pushy site-wrapper--has-overlay")}),i.has(".main-nav__sub").addClass("has-children").prepend('<span class="main-nav__toggle"></span>'),i.has(".main-nav__megamenu").addClass("has-children").prepend('<span class="main-nav__toggle"></span>'),e(".main-nav__toggle").on("click",function(){e(this).toggleClass("main-nav__toggle--rotate").parent().siblings().children().removeClass("main-nav__toggle--rotate"),e(".main-nav__sub, .main-nav__megamenu").not(e(this).siblings(".main-nav__sub, .main-nav__megamenu")).slideUp("normal"),e(this).siblings(".main-nav__sub").slideToggle("normal"),e(this).siblings(".main-nav__megamenu").slideToggle("normal")}),e(".main-nav__list > li > ul > li").has(".main-nav__sub-2").addClass("has-children").prepend('<span class="main-nav__toggle-2"></span>'),e(".main-nav__list > li > ul > li > ul > li").has(".main-nav__sub-3").addClass("has-children").prepend('<span class="main-nav__toggle-2"></span>'),e(".main-nav__toggle-2").on("click",function(){e(this).toggleClass("main-nav__toggle--rotate"),e(this).siblings(".main-nav__sub-2").slideToggle("normal"),e(this).siblings(".main-nav__sub-3").slideToggle("normal")}),n.on("click",function(a){r.addClass("search-active"),e(".input-search").focus(),a.preventDefault(),e(document).keyup(function(e){27===e.keyCode&&r.hasClass("search-active")&&(r.removeClass("search-active"),e.preventDefault())})}),e(".js-search-form-close").on("click",function(e){r.removeClass("search-active"),e.preventDefault()})}},isotope:function(){if(s.exists())var a=s.imagesLoaded(function(){var s=e(".js-filter");a.isotope({filter:"*",itemSelector:".room",layoutMode:"fitRows",masonry:{columnWidth:".room"}}),s.on("click","button",function(){var t=e(this).attr("data-filter");s.find("button").removeClass("btn-primary").addClass("btn-outline-secondary"),e(this).removeClass("btn-outline-secondary").addClass("btn-primary"),a.isotope({filter:t})})})},slickSlider:function(){t.exists()&&(t.on("init",function(a,s){d(e(".main-slider__item:first-child").find("[data-animation]"))}),t.on("beforeChange",function(a,s,t,i){d(e('.main-slider__item[data-slick-index="'+i+'"]').find("[data-animation]"))}),t.slick({autoplay:!0,autoplaySpeed:7e3,arrows:!1,dots:!0,infinite:!0,speed:600,fade:!0,rows:0,cssEase:"cubic-bezier(0.455, 0.03, 0.515, 0.955)"})),i.exists()&&i.slick({autoplay:!0,autoplaySpeed:7e3,arrows:!1,dots:!0,infinite:!0,speed:600,fade:!0,rows:0,cssEase:"cubic-bezier(0.455, 0.03, 0.515, 0.955)"}),l.exists()&&l.slick({arrows:!0,dots:!1,infinite:!0,slidesToShow:3,slidesToScroll:1,rows:0,responsive:[{breakpoint:768,settings:{slidesToShow:2,arrows:!1}},{breakpoint:480,settings:{slidesToShow:1,arrows:!1}}]}),g.exists()&&g.slick({autoplay:!0,autoplaySpeed:7e3,arrows:!0,dots:!1,infinite:!0,speed:600,cssEase:"cubic-bezier(0.455, 0.03, 0.515, 0.955)",slidesToShow:2,slidesToScroll:1,prevArrow:'<span class="slick-arrow-divider"></span><button type="button" class="slick-prev-arrow"><span></span></button>',nextArrow:'<button type="button" class="slick-next-arrow"><span></span></button>',rows:0,responsive:[{breakpoint:768,settings:{slidesToShow:1,arrows:!1}}]})},instagramFeed:function(){n.exists()&&new Instafeed({get:"user",target:"instagram-feed",userId:"6679748018",accessToken:"",limit:6,template:'<li class="widget-instagram__item"><a href="{{link}}" id="{{id}}" class="widget-instagram__link-wrapper" target="_blank"><span class="widget-instagram__plus-sign"><img src="{{image}}" alt="" class="widget-instagram__img" /></span></a></li>'}).run();r.exists()&&new Instafeed({get:"user",target:"instagram-feed-tagged",userId:"6679748018",accessToken:"",limit:8,template:'<li class="widget-instagram__item" data-aos="zoom-in" data-aos-duration="600"><a href="{{link}}" id="{{id}}" class="widget-instagram__link-wrapper" target="_blank"><span class="widget-instagram__plus-sign"><img src="{{image}}" alt="" class="widget-instagram__img" /><span class="widget-instagram__item-meta"><span class="widget-instagram__item-meta-likes"><i class="ion-heart"></i> {{likes}}</span><span class="widget-instagram__item-meta-comments"><i class="ion-chatbubble"></i> {{comments}}</span></span></span></a></li>',resolution:"low_resolution"}).run()},googleMap:function(){y.exists()&&y.each(function(){var a=e(this),s=a.attr("data-map-address")?a.attr("data-map-address"):"New York, USA",t=a.attr("data-map-zoom")?a.attr("data-map-zoom"):"15",i=a.attr("data-map-icon")?a.attr("data-map-icon"):"",l=a.attr("data-map-style"),n="";n="default"===l?[{featureType:"administrative.country",elementType:"geometry",stylers:[{visibility:"simplified"},{hue:"#ff0000"}]}]:"light-dream"===l?[{featureType:"landscape",stylers:[{hue:"#FFBB00"},{saturation:43.400000000000006},{lightness:37.599999999999994},{gamma:1}]},{featureType:"road.highway",stylers:[{hue:"#FFC200"},{saturation:-61.8},{lightness:45.599999999999994},{gamma:1}]},{featureType:"road.arterial",stylers:[{hue:"#FF0300"},{saturation:-100},{lightness:51.19999999999999},{gamma:1}]},{featureType:"road.local",stylers:[{hue:"#FF0300"},{saturation:-100},{lightness:52},{gamma:1}]},{featureType:"water",stylers:[{hue:"#0078FF"},{saturation:-13.200000000000003},{lightness:2.4000000000000057},{gamma:1}]},{featureType:"poi",stylers:[{hue:"#00FF6A"},{saturation:-1.0989010989011234},{lightness:11.200000000000017},{gamma:1}]}]:"shades-of-grey"===l?[{featureType:"all",elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#000000"},{lightness:40}]},{featureType:"all",elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#000000"},{lightness:16}]},{featureType:"all",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#000000"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:17},{weight:1.2}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#000000"},{lightness:20}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#000000"},{lightness:21}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#000000"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:29},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#000000"},{lightness:18}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#000000"},{lightness:16}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#000000"},{lightness:19}]},{featureType:"water",elementType:"geometry",stylers:[{color:"#000000"},{lightness:17}]}]:"blue-water"===l?[{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#444444"}]},{featureType:"landscape",elementType:"all",stylers:[{color:"#f2f2f2"}]},{featureType:"poi",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"road",elementType:"all",stylers:[{saturation:-100},{lightness:45}]},{featureType:"road.highway",elementType:"all",stylers:[{visibility:"simplified"}]},{featureType:"road.arterial",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"all",stylers:[{visibility:"off"}]},{featureType:"water",elementType:"all",stylers:[{color:"#46bcec"},{visibility:"on"}]}]:[{featureType:"water",elementType:"geometry",stylers:[{color:"#e9e9e9"},{lightness:17}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:20}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#ffffff"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#ffffff"},{lightness:29},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:18}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:16}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:21}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#dedede"},{lightness:21}]},{elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#ffffff"},{lightness:16}]},{elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#333333"},{lightness:40}]},{elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#f2f2f2"},{lightness:19}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#fefefe"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#fefefe"},{lightness:17},{weight:1.2}]}],a.gmap3({zoom:Number(t),mapTypeId:google.maps.MapTypeId.ROADMAP,scrollwheel:!1,address:s,styles:n}).marker({address:s,icon:i})})},magnificPopupInit:function(){o.exists()&&e(".mp_single-img").magnificPopup({type:"image",removalDelay:300,gallery:{enabled:!1},mainClass:"mfp-fade",autoFocusLast:!1}),m.exists()&&e(".mp_gallery").magnificPopup({type:"image",removalDelay:300,gallery:{enabled:!0},mainClass:"mfp-fade",autoFocusLast:!1}),p.exists()&&e(".mp_iframe").magnificPopup({type:"iframe",removalDelay:300,mainClass:"mfp-fade",autoFocusLast:!1,patterns:{youtube:{index:"youtube.com/",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}},srcAction:"iframe_src"})},animateOnScroll:function(){AOS.init()},miscScripts:function(){}};function d(a){a.each(function(){var a=e(this),s=a.data("delay"),t="animated "+a.data("animation");a.css({"animation-delay":s,"-webkit-animation-delay":s}),a.addClass(t).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){a.removeClass(t)})})}e(document).on("ready",function(){c.initialize()})}(jQuery);