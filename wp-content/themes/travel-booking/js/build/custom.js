jQuery(document).ready(function($){	
    //Header Search form show/hide
    var winWidth = $(window).width();
    if(winWidth > 1024){
        $('html').on( 'click', function() {
            $('.site-header .form-holder').slideUp();
        });

        $('.site-header .form-section-holder').on( 'click', function(event) {
            event.stopPropagation();
        });

        $('.site-header .form-holder').on( 'click', function(event) {
            event.stopPropagation();
        });

        $(".site-header .form-section-holder").on( 'click', function() {
            $(".site-header .form-holder").slideToggle();
            return false;
        });

        $(".site-header .right .tools .form-section-holder .form-holder .btn-form-close").on( 'click', function() {
            $(".site-header .form-holder").slideToggle();
            return false;
        });
    }

    //prepending svg code for blockquote
    $("#primary .post .entry-content blockquote").append('<svg x="0px" y="0px"><path class="st0" d="M30.2,18.6h-2.8c0.2-2.1,0.6-3.8,1.2-5.1c0.6-1.3,1.4-2.6,2.5-3.9c1.2-1.3,2.3-2.3,3.5-3.1 c1.1-0.8,2.6-1.7,4.5-2.8L36.9,0c-1.2,0.8-2.7,1.8-4.5,3c-1.8,1.2-3.5,2.7-5.1,4.5c-1.6,1.7-2.9,3.7-4,5.8 c-1.1,2.2-1.6,4.6-1.6,7.2c0,3.7,0.7,6.8,2.2,9.1c1.4,2.3,3.8,3.4,7.2,3.4c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8 c0-2.4-0.7-4.2-2.1-5.5C34.5,19.3,32.6,18.6,30.2,18.6z M9.4,9.6c1.2-1.3,2.3-2.3,3.5-3.1c1.1-0.8,2.6-1.7,4.5-2.8L15.2,0 c-1.2,0.8-2.7,1.8-4.5,3C8.9,4.2,7.2,5.7,5.6,7.4c-1.6,1.7-2.9,3.7-4,5.8C0.6,15.4,0,17.8,0,20.5c0,3.7,0.7,6.8,2.2,9.1 C3.6,31.8,6,33,9.4,33c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8c0-2.4-0.7-4.2-2.1-5.5c-1.4-1.2-3.3-1.9-5.7-1.9H5.7 c0.2-2.1,0.6-3.8,1.2-5.2C7.5,12.1,8.3,10.8,9.4,9.6z"/></svg>');
    $("#primary .page .entry-content blockquote").append('<svg x="0px" y="0px"><path class="st0" d="M30.2,18.6h-2.8c0.2-2.1,0.6-3.8,1.2-5.1c0.6-1.3,1.4-2.6,2.5-3.9c1.2-1.3,2.3-2.3,3.5-3.1 c1.1-0.8,2.6-1.7,4.5-2.8L36.9,0c-1.2,0.8-2.7,1.8-4.5,3c-1.8,1.2-3.5,2.7-5.1,4.5c-1.6,1.7-2.9,3.7-4,5.8 c-1.1,2.2-1.6,4.6-1.6,7.2c0,3.7,0.7,6.8,2.2,9.1c1.4,2.3,3.8,3.4,7.2,3.4c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8 c0-2.4-0.7-4.2-2.1-5.5C34.5,19.3,32.6,18.6,30.2,18.6z M9.4,9.6c1.2-1.3,2.3-2.3,3.5-3.1c1.1-0.8,2.6-1.7,4.5-2.8L15.2,0 c-1.2,0.8-2.7,1.8-4.5,3C8.9,4.2,7.2,5.7,5.6,7.4c-1.6,1.7-2.9,3.7-4,5.8C0.6,15.4,0,17.8,0,20.5c0,3.7,0.7,6.8,2.2,9.1 C3.6,31.8,6,33,9.4,33c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8c0-2.4-0.7-4.2-2.1-5.5c-1.4-1.2-3.3-1.9-5.7-1.9H5.7 c0.2-2.1,0.6-3.8,1.2-5.2C7.5,12.1,8.3,10.8,9.4,9.6z"/></svg>');
    $("#primary .post .entry-content .pull-right").append('<svg x="0px" y="0px"><path class="st0" d="M30.2,18.6h-2.8c0.2-2.1,0.6-3.8,1.2-5.1c0.6-1.3,1.4-2.6,2.5-3.9c1.2-1.3,2.3-2.3,3.5-3.1 c1.1-0.8,2.6-1.7,4.5-2.8L36.9,0c-1.2,0.8-2.7,1.8-4.5,3c-1.8,1.2-3.5,2.7-5.1,4.5c-1.6,1.7-2.9,3.7-4,5.8 c-1.1,2.2-1.6,4.6-1.6,7.2c0,3.7,0.7,6.8,2.2,9.1c1.4,2.3,3.8,3.4,7.2,3.4c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8 c0-2.4-0.7-4.2-2.1-5.5C34.5,19.3,32.6,18.6,30.2,18.6z M9.4,9.6c1.2-1.3,2.3-2.3,3.5-3.1c1.1-0.8,2.6-1.7,4.5-2.8L15.2,0 c-1.2,0.8-2.7,1.8-4.5,3C8.9,4.2,7.2,5.7,5.6,7.4c-1.6,1.7-2.9,3.7-4,5.8C0.6,15.4,0,17.8,0,20.5c0,3.7,0.7,6.8,2.2,9.1 C3.6,31.8,6,33,9.4,33c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8c0-2.4-0.7-4.2-2.1-5.5c-1.4-1.2-3.3-1.9-5.7-1.9H5.7 c0.2-2.1,0.6-3.8,1.2-5.2C7.5,12.1,8.3,10.8,9.4,9.6z"/></svg>');
    $("#primary .page .entry-content .pull-right").append('<svg x="0px" y="0px"><path class="st0" d="M30.2,18.6h-2.8c0.2-2.1,0.6-3.8,1.2-5.1c0.6-1.3,1.4-2.6,2.5-3.9c1.2-1.3,2.3-2.3,3.5-3.1 c1.1-0.8,2.6-1.7,4.5-2.8L36.9,0c-1.2,0.8-2.7,1.8-4.5,3c-1.8,1.2-3.5,2.7-5.1,4.5c-1.6,1.7-2.9,3.7-4,5.8 c-1.1,2.2-1.6,4.6-1.6,7.2c0,3.7,0.7,6.8,2.2,9.1c1.4,2.3,3.8,3.4,7.2,3.4c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8 c0-2.4-0.7-4.2-2.1-5.5C34.5,19.3,32.6,18.6,30.2,18.6z M9.4,9.6c1.2-1.3,2.3-2.3,3.5-3.1c1.1-0.8,2.6-1.7,4.5-2.8L15.2,0 c-1.2,0.8-2.7,1.8-4.5,3C8.9,4.2,7.2,5.7,5.6,7.4c-1.6,1.7-2.9,3.7-4,5.8C0.6,15.4,0,17.8,0,20.5c0,3.7,0.7,6.8,2.2,9.1 C3.6,31.8,6,33,9.4,33c2.1,0,3.8-0.7,5.1-2.2c1.2-1.5,1.8-3.1,1.8-4.8c0-2.4-0.7-4.2-2.1-5.5c-1.4-1.2-3.3-1.9-5.7-1.9H5.7 c0.2-2.1,0.6-3.8,1.2-5.2C7.5,12.1,8.3,10.8,9.4,9.6z"/></svg>');

    // responsive menu
    
    if (winWidth < 1025) {
        // $('.site-header .right').prepend('<div class="btn-close-menu"><span></span></div>');
        $('#site-navigation ul li.menu-item-has-children').append('<div class="angle-down"><span class="fa fa-angle-down"></span></div>');
        $('#site-navigation ul li .angle-down').on( 'click', function() {
            $(this).prev().slideToggle();
            $(this).toggleClass('active');
        });

        $('#toggle-button').on( 'click', function() {
            // $('.site-header .right').toggleClass('open');
            $('body').toggleClass('menu-open');
        });

        // $('.btn-close-menu').click(function() {
        //     $('body').removeClass('menu-open');
        //     $('.site-header .right').removeClass('open');
        // });


        $('#toggle-button').on( 'click', function(event) {
            event.stopPropagation();
        });

        $('.site-header .right').on( 'click', function(event) {
            event.stopPropagation();
        });
    }

    //responsive menu toggle
    $('.mobile-header .mobile-menu-opener').on('click', function(){
        $('.mobile-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    $('.mobile-header .close').on('click', function () {
        $('.menu-open').removeClass('menu-open');
        $('.mobile-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    $('.overlay').on( 'click', function() {
        $('.menu-open').removeClass('menu-open');
        $('.mobile-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    $('<button class="arrow-down"></button>').insertAfter($('.mobile-navigation ul .menu-item-has-children > a'));
    $('.mobile-navigation ul li .arrow-down').on( 'click', function() {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
    });

    // responsive language
    $('.site-header .right .tools .languages li a').on( 'click', function(){
        $(this).next().slideToggle();
    });

    //custom scroll bar
    if( $('.page-template-template-trip_types .item .text' ).length ){
        $('.page-template-template-trip_types .item .text').each(function(){ 
            var ps = new PerfectScrollbar($(this)[0]); 
        });
    }

    if( $('.page-template-template-activities .item .text' ).length ){
        $('.page-template-template-activities .item .text').each(function(){ 
            var ps = new PerfectScrollbar($(this)[0]); 
        });
    }
    if(winWidth > 1024){
        $(".main-navigation ul li a").on( 'focus', function() {
            $(this).parents("li").addClass("focus");
        }).on( 'blur', function() {
            $(this).parents("li").removeClass("focus");
        });
    }
});
