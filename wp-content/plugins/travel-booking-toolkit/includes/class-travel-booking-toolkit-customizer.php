<?php
/**
* Register all the sections for travel agency theme.
*
*
* @package    Travel_Booking_Toolkit
* @subpackage Travel_Booking_Toolkit/includes
*/
add_action( 'customize_register',  'travel_booking_toolkit_customize_register' );
/**
 * Front Page Settings
 *
 * @package Travel_Booking_Toolkit
 */
function travel_booking_toolkit_customize_register( $wp_customize ) {
	
	$obj      = new Travel_Booking_Toolkit_Functions;
    $defaults = new Travel_Booking_Toolkit_Dummy_Array;

    /** Popular Section */
    $wp_customize->add_section( 'popular_section', array(
        'title'    => __( 'Popular Packages', 'travel-booking-toolkit' ),
        'priority' => 30,
        'panel'    => 'home_page_setting',
    ) );

    /** Enable/Disable Popular Section */
    $wp_customize->add_setting(
        'ed_popular_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
     'ed_popular_section',
     array(
         'section' => 'popular_section',
         'label'   => __( 'Enable Popular Packages Section', 'travel-booking-toolkit' ),
            'type'    => 'checkbox'
     )       
    );
    
    /** Section Title */
    $wp_customize->add_setting(
        'popular_title',
        array(
            'default'           => __( 'Popular Packages', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'popular_title',
        array(
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'section' => 'popular_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'popular_title', array(
        'selector'        => '.popular-package .section-header .section-title',
        'render_callback' => 'travel_booking_toolkit_get_popular_section_title',
    ) );
    
    /** Section Description */
    $wp_customize->add_setting(
        'popular_desc',
        array(
            'default' => __( 'This is the best place to show your most sold and popular travel packages. You can modify this section from Appearance > Customize > Front Page Settings > Popular Packages.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'popular_desc',
        array(
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'section' => 'popular_section',
            'type'    => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'popular_desc', array(
        'selector'        => '.popular-package .section-header .section-content',
        'render_callback' => 'travel_booking_toolkit_get_popular_section_content',
    ) ); 
        
    if( $obj->travel_booking_toolkit_is_wpte_activated() ){

        /** Popular Note */
        $wp_customize->add_setting( 'popular_note',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control( new Travel_Booking_Toolkit_Info_Text( $wp_customize,
            'popular_note', 
                array(
                    'section'     => 'popular_section',
                    'description' => __( 'We recommend to choose all the options here for a flawless design.', 'travel-booking-toolkit' )
                )
            )
        );
        
        /** Enable/Disable Popular Section */
        $wp_customize->add_setting(
            'ed_popular_demo',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_popular_demo',
            array(
                'section'     => 'popular_section',
                'label'       => __( 'Enable Popular Package Demo Content', 'travel-booking-toolkit' ),
                'description' => __( 'If there are no Popular Package Posts selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
                'type'        => 'checkbox'
            )       
        );
    
        
        /** Popular Trip Note */
        $wp_customize->add_setting( 'popular_tripnote',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control( new Travel_Booking_Toolkit_Info_Text( $wp_customize,
            'popular_tripnote', 
                array(
                    'section'     => 'popular_section',
                    'description' => __( 'Go to Trips > Add New trips. Then you will be able to select a trip from the dropdown below.', 'travel-booking-toolkit' ),
                )
            )
        );
        
        /** Popular Post One */
        $wp_customize->add_setting(
            'popular_post_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'popular_post_one',
            array(
                'label' => __( 'Popular Package One', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type' => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Popular Post Two */
        $wp_customize->add_setting(
            'popular_post_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'popular_post_two',
            array(
                'label' => __( 'Popular Package Two', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type' => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Popular Post Three */
        $wp_customize->add_setting(
            'popular_post_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'popular_post_three',
            array(
                'label' => __( 'Popular Package Three', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type' => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Read More Label */
        $wp_customize->add_setting(
            'popular_readmore_label',
            array(
                'default'           => __( 'View Details', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'popular_readmore_label',
            array(
                'label'   => __( 'Read More Label', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type'    => 'text',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'popular_readmore_label', array(
            'selector'        => '.popular-package .col .btn-holder a.primary-btn.readmore-btn',
            'render_callback' => 'travel_booking_toolkit_get_popular_readmore',
        ) );

        /** View All Label */
        $wp_customize->add_setting(
            'popular_view_all_label',
            array(
                'default'           => __( 'View All Packages', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'popular_view_all_label',
            array(
                'label'   => __( 'View All Label', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'popular_view_all_label', array(
            'selector'        => '.popular-package .btn-holder a.primary-btn.view-all-btn',
            'render_callback' => 'travel_booking_toolkit_get_popular_view_all',
        ) );
        
        /** View All URL */
        $wp_customize->add_setting(
            'popular_view_all_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'popular_view_all_url',
            array(
                'label'   => __( 'View All URL', 'travel-booking-toolkit' ),
                'section' => 'popular_section',
                'type'    => 'text',
            )
        );
    
    }else{                
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'popular_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                 )
             );
        
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Recommend_Control(
                    $wp_customize, 'popular_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'popular_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    /** Featured Section */
    $wp_customize->add_section( 'featured_section', array(
        'title'    => __( 'Featured Section', 'travel-booking-toolkit' ),
        'priority' => 50,
        'panel'    => 'home_page_setting',
    ) );
    
    /** Enable/Disable Feature Section */
    $wp_customize->add_setting(
        'ed_feature_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
     'ed_feature_section',
     array(
         'section' => 'featured_section',
         'label'   => __( 'Enable Feature Trip Section', 'travel-booking-toolkit' ),
            'type'    => 'checkbox'
     )       
    );
    
    /** Section Title */
    $wp_customize->add_setting(
        'feature_title',
        array(
            'default'           => __( 'Featured Trip', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'feature_title',
        array(
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'section' => 'featured_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'feature_title', array(
        'selector'        => '.featured-trip .section-header h2.section-title',
        'render_callback' => 'travel_booking_toolkit_get_featured_title',
    ) );
    
    /** Section Description */
    $wp_customize->add_setting(
        'feature_desc',
        array(
            'default'           => __( 'This is the best place to show your other travel packages. You can modify this section from Appearance > Customize > Front Page Settings > Featured Section.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'feature_desc',
        array(
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'section' => 'featured_section',
            'type'    => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'feature_desc', array(
        'selector'        => '.featured-trip .section-header .section-content',
        'render_callback' => 'travel_booking_toolkit_get_featured_content',
    ) ); 
        
    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
        
        /** Enable/Disable Popular Section */
        $wp_customize->add_setting(
            'ed_featured_demo',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
        'ed_featured_demo',
             array(
                 'section'     => 'featured_section',
                 'label'       => __( 'Enable Featured Demo Content', 'travel-booking-toolkit' ),
                    'description' => __( 'If there is no Featured Trip Category selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
                    'type'        => 'checkbox'
             )       
         );
        
        /** Trip Type */
        $wp_customize->add_setting(
            'trip_type',
            array(
                'default'           => 'select_cat',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'trip_type',
            array(
                'label'       => __( 'Choose Trips From', 'travel-booking-toolkit' ),
                'section'     => 'featured_section',
                'type'        => 'select',
                'choices'     => array(
                    'select_cat'   => __( 'Featured Trip Category', 'travel-booking-toolkit' ),  
                    'select_trips' => __( 'Latest Trips', 'travel-booking-toolkit' ),  
                ),
            )
        );

        /** Featured Category */
        $wp_customize->add_setting(
            'featured_cat',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'featured_cat',
            array(
                'label'       => __( 'Choose Featured Trip Category', 'travel-booking-toolkit' ),
                'description' => __( 'Go to Trips > Activities and add. Then you will be able to select a trip activities from the dropdown.', 'travel-booking-toolkit' ),
                'section'     => 'featured_section',
                'type'        => 'select',
                'choices'     => $obj->travel_booking_toolkit_get_categories( true, 'activities', false ),
                'active_callback' => 'travel_booking_toolkit_trip_ac',
            )
        );

               
        /** No. of Trips */
        $wp_customize->add_setting(
            'no_of_trips',
            array(
                'default'           => '6',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'no_of_trips',
            array(
                'label' => __( 'No. of Trips', 'travel-booking-toolkit' ),
                'section' => 'featured_section',
                'type' => 'select',
                'choices' => array(
                    '3' => __( '3', 'travel-booking-toolkit' ),
                    '6' => __( '6', 'travel-booking-toolkit' ),
                )
            )
        );

        $number_of_trips = get_theme_mod( 'no_of_trips', 6 );
        for( $i=1; $i<=$number_of_trips; $i++ ){
            /** Featured Category */
            $wp_customize->add_setting(
                'choose_trip_'. $i,
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                'choose_trip_'. $i,
                array(
                    'label'       => sprintf( __( 'Latest Trip #%1$s', 'travel-booking-toolkit' ), $i ),
                    'section'     => 'featured_section',
                    'type'        => 'select',
                    'choices'     => $obj->travel_booking_toolkit_get_posts( 'trip' ),
                    'active_callback' => 'travel_booking_toolkit_trip_ac',
                )
            );
        }
        
        /** Read More Label */
        $wp_customize->add_setting(
            'featured_readmore',
            array(
                'default'           => __( 'View Detail', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'featured_readmore',
            array(
                'label'   => __( 'Read More label', 'travel-booking-toolkit' ),
                'section' => 'featured_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'featured_readmore', array(
            'selector'        => '.featured-trip .col .text-holder .btn-holder a.primary-btn.readmore-btn',
            'render_callback' => 'travel_booking_toolkit_get_featured_label',
        ) );
        
        /** View All Label */
        $wp_customize->add_setting(
            'featured_view_all',
            array(
                'default'           => __( 'View All Packages', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'featured_view_all',
            array(
                'label'   => __( 'View All label', 'travel-booking-toolkit' ),
                'section' => 'featured_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'featured_view_all', array(
            'selector'        => '.featured-trip .btn-holder a.primary-btn.view-all-btn',
            'render_callback' => 'travel_booking_toolkit_get_featured_view_all_label',
        ) );

        /** View All Label */
        $wp_customize->add_setting(
            'featured_view_all_link',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'featured_view_all_link',
            array(
                'label'   => __( 'View All Url', 'travel-booking-toolkit' ),
                'description'   => __( 'Please insert custom url link to show all trips.', 'travel-booking-toolkit' ),
                'section' => 'featured_section',
                'type'    => 'url',
                'active_callback' => 'travel_booking_toolkit_trip_ac',
            )
        );
        
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'featured_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );

            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Recommend_Control(
                    $wp_customize, 'featured_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'featured_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recommended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    /** Deals and Discount Section */
    $wp_customize->add_section( 'deal_section', array(
        'title'    => __( 'Deals Section', 'travel-booking-toolkit' ),
        'priority' => 60,
        'panel'    => 'home_page_setting',
    ) ); 
    
    /** Enable/Disable Feature Section */
    $wp_customize->add_setting(
        'ed_deal_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'ed_deal_section',
        array(
            'section' => 'deal_section',
            'label'   => __( 'Enable Deals & Discount Section', 'travel-booking-toolkit' ),
            'type'    => 'checkbox'
        )       
    );

    /** Section Title */
    $wp_customize->add_setting(
        'deal_title',
        array(
            'default'           => __( 'Deals and Discounts', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'deal_title',
        array(
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'section' => 'deal_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'deal_title', array(
        'selector'        => '.deals-section .section-header h2.section-title',
        'render_callback' => 'travel_booking_toolkit_get_deal_title',
    ) );
    
    /** Section Description */
    $wp_customize->add_setting(
        'deal_desc',
        array(
            'default' => __( 'how the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Deals Section.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'deal_desc',
        array(
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'section' => 'deal_section',
            'type'    => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'deal_desc', array(
        'selector'        => '.deals-section .section-header .section-content',
        'render_callback' => 'travel_booking_toolkit_get_deal_content',
    ) ); 
    
    if( $obj->travel_booking_toolkit_is_wpte_activated() ){
        
        /** Enable/Disable Popular Section */
        $wp_customize->add_setting(
            'ed_deal_demo',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_deal_demo',
            array(
                'section'     => 'deal_section',
                'label'       => __( 'Enable Deal Demo Content', 'travel-booking-toolkit' ),
                'description' => __( 'If there are no Deal Post selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
                'type'        => 'checkbox'
            )       
        );
        
        /** Deal Trip Note */
        $wp_customize->add_setting( 'deal_tripnote',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control( new Travel_Booking_Toolkit_Info_Text( $wp_customize,
            'deal_tripnote', 
                array(
                    'section'     => 'deal_section',
                    'description' => __( 'Go to Trips > Add New trips. Then you will be able to select a trip from the dropdown below.', 'travel-booking-toolkit' ),
                )
            )
        );
        
        /** Deal Post One */
        $wp_customize->add_setting(
            'deal_post_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'deal_post_one',
            array(
                'label'   => __( 'Deal Post One', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Deal Post Two */
        $wp_customize->add_setting(
            'deal_post_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'deal_post_two',
            array(
                'label'   => __( 'Deal Post Two', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Deal Post Three */
        $wp_customize->add_setting(
            'deal_post_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'deal_post_three',
            array(
                'label'   => __( 'Deal Post Three', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );

        /** Deal Post Four */
        $wp_customize->add_setting(
            'deal_post_four',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'deal_post_four',
            array(
                'label'   => __( 'Deal Post Four', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_posts( 'trip' )
            )
        );
        
        /** Read More Label */
        $wp_customize->add_setting(
            'deal_readmore',
            array(
                'default'           => __( 'View Details', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'deal_readmore',
            array(
                'label'   => __( 'Read More label', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'deal_readmore', array(
            'selector'        => '.deals-section .grid .col .text-holder .btn-holder a.primary-btn.readmore-btn',
            'render_callback' => 'travel_booking_toolkit_get_dealbtn_label',
        ) );
        
        /** View All Label */
        $wp_customize->add_setting(
            'deal_view_all_label',
            array(
                'default'           => __( 'View All Deals', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'deal_view_all_label',
            array(
                'label'   => __( 'View All Label', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'deal_view_all_label', array(
            'selector'        => '.deals-section .btn-holder a.primary-btn.view-all-btn',
            'render_callback' => 'travel_booking_toolkit_get_deal_view_all_label',
        ) );
        
        /** View All URL */
        $wp_customize->add_setting(
            'deal_view_all_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'deal_view_all_url',
            array(
                'label'   => __( 'View All URL', 'travel-booking-toolkit' ),
                'section' => 'deal_section',
                'type'    => 'text',
            )
        );
    
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'deal_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
    
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Recommend_Control(
                    $wp_customize, 'deal_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'deal_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recomended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    /** Destination Section */
    $wp_customize->add_section( 'destination_section', array(
        'title'    => __( 'Destination Section', 'travel-booking-toolkit' ),
        'priority' => 70,
        'panel'    => 'home_page_setting',
    ) ); 
    
    /** Enable/Disable Destination Section */
    $wp_customize->add_setting(
        'ed_destination_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
     'ed_destination_section',
     array(
         'section' => 'destination_section',
         'label'   => __( 'Enable Destination Section', 'travel-booking-toolkit' ),
            'type'    => 'checkbox'
     )       
    );
    
    /** Section Title */
    $wp_customize->add_setting(
        'destination_title',
        array(
            'default'           => __( 'Popular Destinations', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'destination_title',
        array(
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'section' => 'destination_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'destination_title', array(
        'selector'        => '.popular-destination .section-header h2.section-title',
        'render_callback' => 'travel_booking_toolkit_get_destination_title',
    ) );
    
    /** Section Description */
    $wp_customize->add_setting(
        'destination_desc',
        array(
            'default' => __( 'How the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Destination Section.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'destination_desc',
        array(
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'section' => 'destination_section',
            'type'    => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'destination_desc', array(
        'selector'        => '.popular-destination .section-header .section-content',
        'render_callback' => 'travel_booking_toolkit_get_destination_content',
    ) );

    if( $obj->travel_booking_toolkit_is_wpte_activated() ){

         /** Enable/Disable Popular Destination Demo */
        $wp_customize->add_setting(
            'ed_destination_demo',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_destination_demo',
            array(
                'section'     => 'destination_section',
                'label'       => __( 'Enable Destination Demo Content', 'travel-booking-toolkit' ),
                'description' => __( 'If there are no Destination terms selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
                'type'        => 'checkbox'
            )       
        );
        
        /** Popular Destination Note */
        $wp_customize->add_setting( 'destination_tripnote',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control( new Travel_Booking_Toolkit_Info_Text( $wp_customize,
            'destination_tripnote', 
                array(
                    'section'     => 'destination_section',
                    'description' => __( 'Go to Trips > Add New trips > Add destination. Then you will be able to select a destination from the dropdown below.', 'travel-booking-toolkit' ),
                )
            )
        );
        
        /** Destination One */
        $wp_customize->add_setting(
            'destination_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'destination_one',
            array(
                'label'   => __( 'Destination One', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'destination', false )
            )
        );
        
        /** Destination Two */
        $wp_customize->add_setting(
            'destination_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'destination_two',
            array(
                'label'   => __( 'Destination Two', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'destination', false )
            )
        );
        
        /** Destination Three */
        $wp_customize->add_setting(
            'destination_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'destination_three',
            array(
                'label'   => __( 'Destination Three', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'destination', false )
            )
        );

        /** Destination Four */
        $wp_customize->add_setting(
            'destination_four',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'destination_four',
            array(
                'label'   => __( 'Destination Four', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'destination', false )
            )
        );

        /** View All Label */
        $wp_customize->add_setting(
            'destination_view_all_label',
            array(
                'default'           => __( 'View All Destinations', 'travel-booking-toolkit' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'destination_view_all_label',
            array(
                'label'   => __( 'View All Label', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'text',
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'destination_view_all_label', array(
            'selector'        => '.popular-destination .btn-holder a.primary-btn.view-all-btn',
            'render_callback' => 'travel_booking_toolkit_get_destination_view_all',
        ) );
        
        /** View All URL */
        $wp_customize->add_setting(
            'destination_view_all_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'destination_view_all_url',
            array(
                'label'   => __( 'View All URL', 'travel-booking-toolkit' ),
                'section' => 'destination_section',
                'type'    => 'text',
            )
        );

    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'destination_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
    
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Recommend_Control(
                    $wp_customize, 'destination_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'destination_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recomended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

    /** Activities Section */
    $wp_customize->add_section( 'activities_section', array(
        'title'    => __( 'Activities Section', 'travel-booking-toolkit' ),
        'priority' => 90,
        'panel'    => 'home_page_setting',
    ) ); 
    
    /** Enable/Disable Activities Section */
    $wp_customize->add_setting(
        'ed_activities_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
     'ed_activities_section',
     array(
         'section' => 'activities_section',
         'label'   => __( 'Enable Activities Section', 'travel-booking-toolkit' ),
            'type'    => 'checkbox'
     )       
    );
    
    /** Section Title */
    $wp_customize->add_setting(
        'activities_title',
        array(
            'default'           => __( 'Browse Activities', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'activities_title',
        array(
            'label'   => __( 'Section Title', 'travel-booking-toolkit' ),
            'section' => 'activities_section',
            'type'    => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'activities_title', array(
        'selector'        => '.activities-section .section-header .section-title',
        'render_callback' => 'travel_booking_toolkit_get_activities_title',
    ) );
    
    /** Section Description */
    $wp_customize->add_setting(
        'activities_desc',
        array(
            'default' => __( 'This is the best place to tell your visitors what travel services your company provide. You can modify this section from Appearance > Customize > Home Page Settings > Activities Section on your WordPress.', 'travel-booking-toolkit' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'activities_desc',
        array(
            'label'   => __( 'Section Description', 'travel-booking-toolkit' ),
            'section' => 'activities_section',
            'type'    => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'activities_desc', array(
        'selector'        => '.activities-section .section-header .section-content',
        'render_callback' => 'travel_booking_toolkit_get_activities_content',
    ) );

    if( $obj->travel_booking_toolkit_is_wpte_activated() ){

        /** Enable/Disable Activities Demo */
        $wp_customize->add_setting(
            'ed_activities_demo',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_activities_demo',
            array(
                'section'     => 'activities_section',
                'label'       => __( 'Enable Activities Demo Content', 'travel-booking-toolkit' ),
                'description' => __( 'If there are no Activities terms selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking-toolkit' ),
                'type'        => 'checkbox'
            )       
        );
        
        /** Activities Note */
        $wp_customize->add_setting( 'activities_demo_note',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control( new Travel_Booking_Toolkit_Info_Text( $wp_customize,
            'activities_demo_note', 
                array(
                    'section'     => 'activities_section',
                    'description' => __( 'Go to Trips > Add New trips > Add activities. Then you will be able to select a activities from the dropdown below.', 'travel-booking-toolkit' ),
                )
            )
        );
        
        /** Activities One */
        $wp_customize->add_setting(
            'activities_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_one',
            array(
                'label'   => __( 'Activities One', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );
        
        /** Activities Two */
        $wp_customize->add_setting(
            'activities_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_two',
            array(
                'label'   => __( 'Activities Two', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );
        
        /** Activities Three */
        $wp_customize->add_setting(
            'activities_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_three',
            array(
                'label'   => __( 'Activities Three', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );

        /** Activities Four */
        $wp_customize->add_setting(
            'activities_four',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_four',
            array(
                'label'   => __( 'Activities Four', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );

        /** Activities Five */
        $wp_customize->add_setting(
            'activities_five',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_five',
            array(
                'label'   => __( 'Activities Five', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );

        /** Activities Six */
        $wp_customize->add_setting(
            'activities_six',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_six',
            array(
                'label'   => __( 'Activities Six', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );

        /** Activities Seven */
        $wp_customize->add_setting(
            'activities_seven',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_seven',
            array(
                'label'   => __( 'Activities Seven', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );

        /** Activities Eight */
        $wp_customize->add_setting(
            'activities_eight',
            array(
                'default'           => '',
                'sanitize_callback' => 'travel_booking_toolkit_sanitize_select',
            )
        );
        
        $wp_customize->add_control(
            'activities_eight',
            array(
                'label'   => __( 'Activities Eight', 'travel-booking-toolkit' ),
                'section' => 'activities_section',
                'type'    => 'select',
                'choices' => $obj->travel_booking_toolkit_get_categories( true, 'activities', false )
            )
        );
    }else{
        if( class_exists( 'Travel_Booking_Toolkit_Plugin_Recommend_Control' ) ){
            $wp_customize->add_setting(
                'activities_note', array(
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
    
            $wp_customize->add_control(
                new Travel_Booking_Toolkit_Plugin_Recommend_Control(
                    $wp_customize, 'activities_note', array(
                        'label'       => __( 'Instructions', 'travel-booking-toolkit' ),
                        'section'     => 'activities_section',
                        'capability'  => 'install_plugins',
                        'plugin_slug' => 'wp-travel-engine',
                        'description' => __( 'Please install the recomended plugin "WP Travel Engine" for setting of this section.', 'travel-booking-toolkit' )
                    )
                )
            );
        }
    }

}

/**
 * Sanitization Functions
*/
function travel_booking_toolkit_sanitize_checkbox( $checked ){
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function travel_booking_toolkit_sanitize_select( $input, $setting ){
    // Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function travel_booking_toolkit_trip_ac( $control ){
    
    $trip_type       = $control->manager->get_setting( 'trip_type' )->value();
    $number_of_trips = $control->manager->get_setting( 'no_of_trips' )->value();
    $number_of_trips = isset( $number_of_trips ) && $number_of_trips > 0 ? $number_of_trips : 6;

    $control_id      = $control->id;

    if ( $control_id == 'featured_cat' && $trip_type == 'select_cat' ) return true;

    for ( $i=1; $i <= $number_of_trips ; $i++ ) { 
        if ( $control_id == 'choose_trip_'. $i && $trip_type == 'select_trips' ) return true;
    }

    if ( $control_id == 'featured_view_all_link' && $trip_type == 'select_trips' ) return true;

    return false;
}