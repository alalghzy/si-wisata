<?php
/**
 * 404 page Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_404_page_section' ) ) :

    /** 
     * 404 Page Section 
     */
    function travel_booking_customize_register_404_page_section( $wp_customize ) {
                                                                                      
        $wp_customize->add_section(
            '404_page_section',
            array(
                'title'    => __( '404 Page Settings', 'travel-booking' ),
                'priority' => 20,
                'panel'    => 'general_settings',
            )
        );

        if( travel_booking_is_wpte_activated() && tb_is_tbt_activated() ){

            /** Enable/Disable Popular in 404 page */
            $wp_customize->add_setting(
                'ed_404_popular',
                array(
                    'default'           => true,
                    'sanitize_callback' => 'travel_booking_sanitize_checkbox',
                )
            );
            
            $wp_customize->add_control(
                'ed_404_popular',
                array(
                    'section'     => '404_page_section',
                    'label'       => __( 'Enable Popular on 404 Page', 'travel-booking' ),
                    'type'        => 'checkbox'
                )       
            );

            /** Popular Section Demo Content */
            $wp_customize->add_setting(
                '404_popular_ed_demo',
                array(
                    'default'           => false,
                    'sanitize_callback' => 'travel_booking_sanitize_checkbox',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_ed_demo',
                array(
                    'label'       => __( 'Enable Demo Content', 'travel-booking' ),
                    'description' => __( 'If there is no Popular Posts selected, demo content will be displayed. Uncheck to hide demo content of this section.', 'travel-booking' ),
                    'section'     => '404_page_section',
                    'type'        => 'checkbox',
                )
            );
            
            /** Popular Section Text */
            $wp_customize->add_setting(
                '404_popular_text',
                array(
                    'default'           => __( 'Popular Trips', 'travel-booking' ),
                    'sanitize_callback' => 'sanitize_text_field',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_text',
                array(
                    'label'   => __( 'Popular Title', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'text',
                )
            );

            /** Popular Trip One */
            $wp_customize->add_setting(
                '404_popular_trip_one',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_one',
                array(
                    'label'   => __( 'Popular Trip One', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );
            
            /** Popular Trip Two */
            $wp_customize->add_setting(
                '404_popular_trip_two',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_two',
                array(
                    'label'   => __( 'Popular Trip Two', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );
            
            /** Popular Trip Three */
            $wp_customize->add_setting(
                '404_popular_trip_three',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_three',
                array(
                    'label'   => __( 'Popular Trip Three', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );

            /** Popular Trip Four */
            $wp_customize->add_setting(
                '404_popular_trip_four',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_four',
                array(
                    'label'   => __( 'Popular Trip Four', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );

            /** Popular Trip Five */
            $wp_customize->add_setting(
                '404_popular_trip_five',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_five',
                array(
                    'label'   => __( 'Popular Trip Five', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );

            /** Popular Trip Six */
            $wp_customize->add_setting(
                '404_popular_trip_six',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'travel_booking_sanitize_select',
                )
            );
            
            $wp_customize->add_control(
                '404_popular_trip_six',
                array(
                    'label'   => __( 'Popular Trip Six', 'travel-booking' ),
                    'section' => '404_page_section',
                    'type'    => 'select',
                    'choices' => travel_booking_get_posts( 'trip' )
                )
            );
        } else {
            $popular_section_404 = sprintf( 
                /* translators: 1: anchor link start, 2: anchor link end, 3: bold tag start, 4: bold tag end  */
                __( 'Please install/activate %1$sWP Travel Engine%2$s %3$sand%4$s WP Travel Engine - Companion plugin to add Popular section.', 'travel-booking' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>', '<b>', '</b>' 
            );

            $wp_customize->add_setting( '404_popular_info',
                array(
                    'default'           => '',
                    'sanitize_callback' => 'wp_kses_post',
                )
            );

            $wp_customize->add_control( 
                new Travel_Booking_Note_Control( 
                $wp_customize,
                '404_popular_info', 
                    array(
                        'label'       => __( 'Install and Activate Recommended Plugin.' , 'travel-booking' ),
                        'section'     => '404_page_section',
                        'description' => $popular_section_404
                    )
                )
            );
        }
    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_404_page_section' );