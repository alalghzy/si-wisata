jQuery(document).ready(function($) {
	// Set all variables to be used in scope
    var frame;

	// ADD IMAGE LINK
    $('body').on('click','.travel_booking_toolkit-upload-button',function(e) {
        e.preventDefault();
        var clicked = $(this).closest('div');
        var custom_uploader = wp.media({
            title: 'RARA Image Uploader',
            // button: {
            //     text: 'Custom Button Text',
            // },
            multiple: false  // Set this to true to allow multiple files to be selected
            })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            var str = attachment.url.split('.').pop(); 
            var strarray = [ 'jpg', 'gif', 'png', 'jpeg' ]; 
            if( $.inArray( str, strarray ) != -1 ){
                clicked.find('.travel_booking_toolkit-screenshot').empty().hide().append('<img src="' + attachment.url + '"><a class="travel_booking_toolkit-remove-image"></a>').slideDown('fast');
            }else{
                clicked.find('.travel_booking_toolkit-screenshot').empty().hide().append('<small>'+travel_booking_toolkit_uploader.msg+'</small>').slideDown('fast');    
            }
            
            clicked.find('.travel_booking_toolkit-upload').val(attachment.id).trigger('change');
            clicked.find('.travel_booking_toolkit-upload-button').val(travel_booking_toolkit_uploader.change);
        }) 
        .open();
    });
    $('body').on('click', '.del-contact-travel_booking_toolkit-icon', function() {
        var con = confirm(sociconsmsg.msg);
        if (!con) {
            return false;
        }
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
            $('ul.travel_booking_toolkit-contact-sortable-links input').trigger('change');
        });
        if ($('.del-contact-travel_booking_toolkit-icon').length < 1) {
            $('.travel_booking_toolkit-contact-social-add').removeAttr('disabled');
        }
    });

    $('body').on('click', '.travel_booking_toolkit-contact-social-add:visible', function(e) {
        e.preventDefault();
        da = $(this).siblings('.travel_booking_toolkit-contact-sortable-links').attr('id');
        suffix = da.match(/\d+/);
        var maximum=0;
        $( '.travel_booking_toolkit-contact-social-icon-wrap:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                maximum = (value > maximum) ? value : maximum;
            }
        });
        var newinput = $('.travel_booking_toolkit-contact-social-template').clone();
        maximum++;
        newinput.find( '.travel_booking_toolkit-contact-social-length' ).attr('name','widget-travel_booking_toolkit_contact_social_links['+suffix+'][social]['+maximum+']');
        newinput.find( '.user-contact-social-profile' ).attr('name','widget-travel_booking_toolkit_contact_social_links['+suffix+'][social_profile]['+maximum+']');
        newinput.html(function(i, oldHTML) {
            return oldHTML.replace(/{{ind}}/g, maximum);
        });
        $(this).siblings('.travel_booking_toolkit-contact-sortable-links').find('.travel_booking_toolkit-contact-social-icon-holder').before(newinput.html()).trigger('change');
    });

    $(document).on('focus','.user-contact-social-profile',function() {
        // if($(this).val()=='')
        // {
            if( $(this).siblings('.travel_booking_toolkit-icons-list').length < 1 )
            {
                var $iconlist = $('.travel_booking_toolkit-icons-wrap').clone();
                $(this).after($iconlist.html());
                $(this).siblings('.travel_booking_toolkit-icons-list').fadeIn('slow');
            }
            
            if ( $(this).siblings('.travel_booking_toolkit-icons-list').find('#remove-icon-list').length < 1 )
            {
                var input = '<span id="remove-icon-list" class="dashicons dashicons-no"></span>';
                $(this).siblings('.travel_booking_toolkit-icons-list:visible').prepend(input);
            }
        // }
    });
    
    $('body').on('click', '.del-contact-travel_booking_toolkit-icon:visible', function(e) {
         $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });
    
    $(document).on('blur','.user-contact-social-profile',function() {
        $(this).siblings('.travel_booking_toolkit-icons-list').fadeOut('slow',function(){
            $(this).remove();
        });
    });
    
    $('body').on('click', '#remove-icon-list', function(e) {
        e.preventDefault();
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
        });
    });

    $('body').on('click', '.cross', function(e) {
        $(this).parent().fadeOut('slow',function(){
            $(this).remove();
            if (in_customizer) {
                $('#add-logo').focus().trigger('change');
            }
        });
        return $(this).focus().trigger('change');
    });
    
    $(document).on('click','.travel_booking_toolkit-icons-list li',function(event) {
        event.preventDefault();
        var prefix = $(this).children('svg').attr('data-prefix');
        var icon = $(this).children('svg').attr('data-icon');
        var val = prefix + ' fa-' + icon;
        $(this).parent().siblings('.user-contact-social-profile').attr('value', icon);
        $(this).parent().parent().siblings('.travel_booking_toolkit-contact-social-length').attr('value','https://'+icon+'.com');

        $(this).parent().parent().siblings('.travel_booking_toolkit-contact-social-length').trigger('change');
        $(this).parent().siblings('.user-contact-social-profile').trigger('change');

        $(this).parent('.travel_booking_toolkit-icons-list').fadeOut('slow', function() {
            $(this).remove();
        });
        
    });

    $(document).on('keyup','.user-contact-social-profile',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.travel_booking_toolkit-icons-list').children('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });
    $('body').on('click','.travel_booking_toolkit-remove-image',function(e) {
        
        var selector = $(this).parent('div').parent('div');
        selector.find('.travel_booking_toolkit-upload').val('').trigger('change');
        selector.find('.travel_booking_toolkit-remove-image').hide();
        selector.find('.travel_booking_toolkit-screenshot').slideUp();
        selector.find('.travel_booking_toolkit-upload-button').val(travel_booking_toolkit_uploader.upload);
        
        return false;
    });

    $(document).on('click', '.travel_booking_toolkit-remove-icon', function() {
        var id = $(this).parents('.widget').attr('id');
        $('#' + id).find('.travel_booking_toolkit-font-group li').removeClass();
        $('#' + id).find('.hidden-icon-input').val('');
        $('#' + id).find('.icon-receiver').html('<i class=""></i>').siblings('a').remove('.travel_booking_toolkit-remove-icon');
        if (in_customizer) {
            $('.hidden-icon-input').trigger('change');
        }
    });

    /** To add remove button if icon is selected in widget update event */
    $(document).on('widget-updated', function(e, widget) {
        // "widget" represents jQuery object of the affected widget's DOM element
        var $this = $('#' + widget[0].id).find('.yes');
            $this.append('<a class="travel_booking_toolkit-remove-icon"></a>');
    });

    travel_booking_toolkit_pro_check_icon();

    /** function to check if icon is selected and saved when loading in widget.php */
    function travel_booking_toolkit_pro_check_icon() {
        $('.icon-receiver').each(function() {
            // var id = $(this).parents('.widget').attr('id');
            if($(this).hasClass('yes'))
            {
                $(this).append('<a class="travel_booking_toolkit-remove-icon"></a>');
            }
        });
    }
    // set var
    var in_customizer = false;

    // check for wp.customize return boolean
    if (typeof wp !== 'undefined') {
        in_customizer = typeof wp.customize !== 'undefined' ? true : false;
    }
    $(document).on('click', '.travel_booking_toolkit-font-group li', function() {
        var id = $(this).parents('.widget').attr('id');
        $('#' + id).find('.travel_booking_toolkit-font-group li').removeClass();
        $('#' + id).find('.icon-receiver').children('a').remove('.travel_booking_toolkit-remove-icon');
        $(this).addClass('selected');
        var prefix =  $(this).parents('.travel_booking_toolkit-font-awesome-list').find('.travel_booking_toolkit-font-group li.selected').children('svg').attr('data-prefix');
        var icon =  $(this).parents('.travel_booking_toolkit-font-awesome-list').find('.travel_booking_toolkit-font-group li.selected').children('svg').attr('data-icon');
        var aa = prefix + ' fa-' + icon;

        $(this).parents('.travel_booking_toolkit-font-awesome-list').siblings('p').find('.hidden-icon-input').val(aa);
        $(this).parents('.travel_booking_toolkit-font-awesome-list').siblings('p').find('.icon-receiver').html('<i class="' + aa + '"></i>');
        $('#' + id).find('.icon-receiver').children('i').after('<a class="travel_booking_toolkit-remove-icon"></a>');

        if (in_customizer) {
            $('.hidden-icon-input').trigger('change');
        }
        return $(this).focus().trigger('change');
    });

     $('body').on('click', '#add-logo:visible', function(e) {
        e.preventDefault();
        da = $(this).siblings('.widget-client-logo-repeater').attr('id');
        suffix = da.match(/\d+/);
        var newinput = $('.travel_booking_toolkit-client-logo-template').clone();
        len=0;
        $(this).siblings('.widget-client-logo-repeater').children( '.link-image-repeat:visible' ).each(function() {
            var value =  $(this).attr( 'data-id' );
            if(!isNaN(value))
            {
                value = parseInt(value);
                len = (value > len) ? value : len;
            }
        });
        len++;
        newinput.html(function(i, oldHTML) {
            newinput.find( '.link-image-repeat' ).attr('data-id',len);
            newinput.find( '.featured-link' ).attr('name','widget-wptravelengine_client_logo_widget['+suffix+'][link]['+len+']');
            newinput.find( '.widget-upload .travel_booking_toolkit-upload' ).attr('name','widget-wptravelengine_client_logo_widget['+suffix+'][image]['+len+']');
        });
        $(this).siblings('.widget-client-logo-repeater').find('.cl-repeater-holder').before(newinput.html());
        return $(this).focus().trigger('change');
    });

    $(document).on('keyup','.wptec-search-icon',function() {
        var value = $(this).val();
        var matcher = new RegExp(value, 'gi');
        $(this).siblings('.travel_booking_toolkit-font-awesome-list').find('li').show().not(function(){
            return matcher.test($(this).find('svg').attr('data-icon'));
        }).hide();
    });
});