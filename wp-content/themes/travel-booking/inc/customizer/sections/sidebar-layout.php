<?php
/**
 * Sidebar Layout Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_general_sidebar_layout_section' ) ) :

    /**
     * Add general sidebar layout controls
     */
    function travel_booking_customize_register_general_sidebar_layout_section( $wp_customize ) {

        /** General Sidebar Layout Settings */
        $wp_customize->add_section(
            'layout_settings',
            array(
                'title'    => __( 'Layout Settings', 'travel-booking' ),
                'priority' => 30,
            )
        );
        
        /** Page Sidebar layout */
        $wp_customize->add_setting( 
            'page_sidebar_layout', 
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'travel_booking_sanitize_radio'
            ) 
        );
        
        $wp_customize->add_control(
    		new Travel_Booking_Radio_Image_Control(
    			$wp_customize,
    			'page_sidebar_layout',
    			array(
    				'section'	  => 'layout_settings',
    				'label'		  => __( 'Page Sidebar Layout', 'travel-booking' ),
    				'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in repective page.', 'travel-booking' ),
    				'choices'	  => array(
    					'no-sidebar'    => get_template_directory_uri() . '/images/no-sidebar.png',
    					'left-sidebar'  => get_template_directory_uri() . '/images/left-sidebar.png',
                        'right-sidebar' => get_template_directory_uri() . '/images/right-sidebar.png',
    				)
    			)
    		)
    	);
        
        /** Post Sidebar layout */
        $wp_customize->add_setting( 
            'post_sidebar_layout', 
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'travel_booking_sanitize_radio'
            ) 
        );
        
        $wp_customize->add_control(
    		new Travel_Booking_Radio_Image_Control(
    			$wp_customize,
    			'post_sidebar_layout',
    			array(
    				'section'	  => 'layout_settings',
    				'label'		  => __( 'Post Sidebar Layout', 'travel-booking' ),
    				'description' => __( 'This is the general sidebar layout for posts. You can override the sidebar layout for individual post in repective post.', 'travel-booking' ),
    				'choices'	  => array(
    					'no-sidebar'    => get_template_directory_uri() . '/images/no-sidebar.png',
    					'left-sidebar'  => get_template_directory_uri() . '/images/left-sidebar.png',
                        'right-sidebar' => get_template_directory_uri() . '/images/right-sidebar.png',
    				)
    			)
    		)
    	);

        /** Default Sidebar layout */
        $wp_customize->add_setting( 
            'default_sidebar_layout', 
            array(
                'default'           => 'right-sidebar',
                'sanitize_callback' => 'travel_booking_sanitize_radio'
            ) 
        );
        
        $wp_customize->add_control(
            new Travel_Booking_Radio_Image_Control(
                $wp_customize,
                'default_sidebar_layout',
                array(
                    'section'     => 'layout_settings',
                    'label'       => __( 'Default Sidebar Layout', 'travel-booking' ),
                    'description' => __( 'This is the general sidebar layout for whole site.', 'travel-booking' ),
                    'choices'     => array(
                        'no-sidebar'    => get_template_directory_uri() . '/images/no-sidebar.png',
                        'left-sidebar'  => get_template_directory_uri() . '/images/left-sidebar.png',
                        'right-sidebar' => get_template_directory_uri() . '/images/right-sidebar.png',
                    )
                )
            )
        );
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_general_sidebar_layout_section' );