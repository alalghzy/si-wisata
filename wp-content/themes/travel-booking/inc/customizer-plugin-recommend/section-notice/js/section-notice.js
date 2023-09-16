/* global section_notice_data */
( function( api ) {

    // Extends our custom "customizer-notice" section.
    api.sectionConstructor['customizer-notice'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );


// shorthand no-conflict safe document-ready function
jQuery(function($) {
    // Hook into the "notice-my-class" class we added to the notice, so
    // Only listen to YOUR notices being dismissed
    $( document ).on( 'click', '.travel-booking-notice .notice-dismiss', function () {
        var control_id = $( this ).parent().attr('id').replace('accordion-section-','');
        $.ajax({
            url: section_notice_data.ajaxurl,
            type: 'POST',
            data: {
                action: 'dismiss_section_notice',
                control: control_id
            },
            success: function(data) {
                $( '#accordion-section-' + data ).fadeOut(300, function() { $(this).remove(); });
            }
        } );
    } );
});