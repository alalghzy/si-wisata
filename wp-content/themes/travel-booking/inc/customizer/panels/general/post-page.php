<?php
/**
 * Post page Section
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_customize_register_post_page_section' ) ) :

    /**
     * Add post page settings
     */
    function travel_booking_customize_register_post_page_section( $wp_customize ) {

        /** Post Page settings */
        $wp_customize->add_section(
            'post_page_settings',
            array(
                'title'    => __( 'Post Page Settings', 'travel-booking' ),
                'priority' => 20,
                'panel'    => 'general_settings',
            )
        );
            
        /** Excerpt Length */
        $wp_customize->add_setting( 
            'excerpt_length', 
            array(
                'default'           => 30,
                'sanitize_callback' => 'travel_booking_sanitize_number_absint'
            ) 
        );
        
        $wp_customize->add_control(
            'excerpt_length',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Excerpt Length', 'travel-booking' ),
                'description' => __( 'Automatically generated excerpt length (in words).', 'travel-booking' ),
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 100,
                    'step' => 5,
                )                 
            )
        );

        /** Read More label */
        $wp_customize->add_setting(
            'readmore',
            array(
                'default'           => __( 'Read More', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
    		'readmore',
    		array(
    			'section' => 'post_page_settings',
    			'label'	  => __( 'Read More Label', 'travel-booking' ),
                'type'    => 'text'
    		)		
    	);
        
        $wp_customize->selective_refresh->add_partial( 'readmore', array(
            'selector'        => '.site-main .entry-footer .btn-holder .btn-more',
            'render_callback' => 'travel_booking_get_readmore_btn',
        ) );
        
        /** Enable/Disable Related Posts */
        $wp_customize->add_setting(
            'ed_related',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Related Posts', 'travel-booking' ),
                'description' => __( 'Enable to show related posts in single post page.', 'travel-booking' ),
                'type'        => 'checkbox'
            )       
        );
        
        /** Related Title */
        $wp_customize->add_setting(
            'related_title',
            array(
                'default'           => __( 'You may also like.', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'related_title',
            array(
                'section' => 'post_page_settings',
                'label'   => __( 'Related Post Title', 'travel-booking' ),
                'type'    => 'text'
            )       
        );
        
        $wp_customize->selective_refresh->add_partial( 'related_title', array(
            'selector'        => '.site-main .recent-posts-area.related h2.section-title',
            'render_callback' => 'travel_booking_get_related_title',
        ) );

        /** Enable/Disable Recent Posts */
        $wp_customize->add_setting(
            'ed_recent',
            array(
                'default'           => true,
                'sanitize_callback' => 'travel_booking_sanitize_checkbox',
            )
        );
        
        $wp_customize->add_control(
            'ed_recent',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Recent Posts', 'travel-booking' ),
                'description' => __( 'Enable to show recent posts in single post page.', 'travel-booking' ),
                'type'        => 'checkbox'
            )       
        );
        
        /** Recent Title */
        $wp_customize->add_setting(
            'recent_title',
            array(
                'default'           => __( 'Recent Posts', 'travel-booking' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'recent_title',
            array(
                'section' => 'post_page_settings',
                'label'   => __( 'Recent Post Title', 'travel-booking' ),
                'type'    => 'text'
            )       
        );
        
        $wp_customize->selective_refresh->add_partial( 'recent_title', array(
            'selector'        => '.site-main .recent-posts-area h2.section-title',
            'render_callback' => 'travel_booking_get_recent_title',
        ) );

    }
endif;
add_action( 'customize_register', 'travel_booking_customize_register_post_page_section' );