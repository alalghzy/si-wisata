( function( api ) {

	// Extends our custom "example-1" section.
	api.sectionConstructor['pro-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );

jQuery(document).ready(function($) {
	/* Move widgets to their respective sections */
	wp.customize.section( 'sidebar-widgets-about' ).panel( 'home_page_setting' );
    wp.customize.section( 'sidebar-widgets-about' ).priority( '20' );

    wp.customize.section( 'sidebar-widgets-cta-one' ).panel( 'home_page_setting' );
    wp.customize.section( 'sidebar-widgets-cta-one' ).priority( '40' );

    wp.customize.section( 'sidebar-widgets-cta-two' ).panel( 'home_page_setting' );
    wp.customize.section( 'sidebar-widgets-cta-two' ).priority( '80' );
    
    // Scroll to section
    $('body').on('click', '#sub-accordion-panel-home_page_setting .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        scrollToSection( section_id );
    });
    
});

function scrollToSection( section_id ){
    var preview_section_id = "banner_section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {
        
        case 'accordion-section-header_image':
        preview_section_id = "banner-section";
        break;

        case 'accordion-section-sidebar-widgets-about':
        preview_section_id = "about-section";
        break;

        case 'accordion-section-popular_section':
        preview_section_id = "popular-section";
        break;

        case 'accordion-section-sidebar-widgets-cta-one':
        preview_section_id = "cta-one-section";
        break;

        case 'accordion-section-featured_section':
        preview_section_id = "featured-trip-section";
        break;

        case 'accordion-section-deal_section':
        preview_section_id = "deals-section";
        break;

        case 'accordion-section-destination_section':
        preview_section_id = "destination-section";
        break;

        case 'accordion-section-sidebar-widgets-cta-two':
        preview_section_id = "cta-two-section";
        break;

        case 'accordion-section-activities_section':
        preview_section_id = "activities-section";
        break;

        case 'accordion-section-blog_section':
        preview_section_id = "blog-section";
        break;
    }

    if( $contents.find('#'+preview_section_id).length > 0 && $contents.find('.home').length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + preview_section_id ).offset().top
        }, 1000);
    }

    // Flush cache
    $('body').on('click', '.flush-it', function(event) {
        $.ajax ({
            url     : travel_booking_cdata.ajax_url,  
            type    : 'post',
            data    : 'action=flush_local_google_fonts',    
            nonce   : travel_booking_cdata.nonce,
            success : function(results){
                //results can be appended in needed
                $( '.flush-it' ).val(travel_booking_cdata.flushit);
            },
        });
    });
}