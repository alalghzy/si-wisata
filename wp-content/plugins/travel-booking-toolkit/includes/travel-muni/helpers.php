<?php 
/**
 * Customize Trip Helpers
*/
function travel_muni_header_customize_trip(){
    $customize_btn_label = get_theme_mod( 'customize_button',__( 'Customize The Trip', 'travel-booking-toolkit' ) );
    $customize_btn_url   = get_theme_mod( 'customize_button_url' );
    if( $customize_btn_label && $customize_btn_url ){
        echo '<div class="btn-book"><a class="btn-primary" href="'.esc_url( $customize_btn_url ).'">'.esc_html( $customize_btn_label ).'</a></div>';
    }
}

/**
 * Header Five
*/
function travel_muni_header_five_mid_wrapper(){
	$phone_label = get_theme_mod( 'phone_label',__( 'Talk to an Expert (David)', 'travel-booking-toolkit' ) );
	$email_label = get_theme_mod( 'email_label',__( 'Quick Questions? Email Us', 'travel-booking-toolkit' ) );
	$phone       = get_theme_mod( 'phone', __( '(000) 999-656-888', 'travel-booking-toolkit' ) );
	$email       = get_theme_mod( 'email', __( 'contact@travelmuni.com', 'travel-booking-toolkit' ) );
	$vip_image   = get_theme_mod( 'header_vip_image', TBT_FILE_URL . '/images/header-5-vibpp.jpg' );
	?>
        <div class="header-m-mid-wrap">
            <div class="contact-wrap-head">
                <div class="vib-whats">
                    <div class="vib-whats-txt">
                        <?php 
                            if( $phone_label ){
                                echo '<h6 class="head5-titl">'.esc_html( $phone_label ).'</h6>';
                            } 
						    if( $phone ){ ?>
						        <div class="contact-phone-wrap">
						                <span class="head-cont-whats">
						                    <svg id="Icons" xmlns="http://www.w3.org/2000/svg" width="19.785" height="19.785" viewBox="0 0 19.785 19.785">
						                      <g id="Color-">
						                        <path id="Whatsapp" d="M709.89,360a9.886,9.886,0,0,0-8.006,15.691l-1.232,3.676,3.8-1.215A9.891,9.891,0,1,0,709.9,360Zm-2.762,5.025c-.192-.459-.337-.477-.628-.488q-.166-.011-.331-.011a1.435,1.435,0,0,0-1.012.354,3.159,3.159,0,0,0-1.012,2.408,5.653,5.653,0,0,0,1.174,2.984,12.385,12.385,0,0,0,4.924,4.35c2.273.942,2.948.855,3.465.744a2.787,2.787,0,0,0,1.942-1.4,2.456,2.456,0,0,0,.169-1.373c-.07-.122-.262-.192-.552-.337s-1.7-.843-1.971-.936a.552.552,0,0,0-.709.215,12.075,12.075,0,0,1-.773,1.023.624.624,0,0,1-.7.11,7.288,7.288,0,0,1-2.32-1.43,8.8,8.8,0,0,1-1.6-1.995c-.169-.291-.017-.459.116-.616.146-.181.285-.308.43-.477a1.756,1.756,0,0,0,.32-.453.591.591,0,0,0-.041-.536c-.069-.145-.651-1.564-.889-2.14Z" transform="translate(-700 -360)" fill="#67c15e" fill-rule="evenodd"></path>
						                      </g>
						                    </svg>
						                </span>
						                <span class="head-cont-vib">
						                    <svg id="Icons" xmlns="http://www.w3.org/2000/svg" width="19.785" height="19.785" viewBox="0 0 19.785 19.785">
						                      <g id="Color-">
						                        <path id="Viber" d="M607.893-810a9.892,9.892,0,0,1,9.893,9.893,9.892,9.892,0,0,1-9.893,9.893A9.892,9.892,0,0,1,598-800.107,9.892,9.892,0,0,1,607.893-810Zm.592,4.029a5.457,5.457,0,0,1,1.813.537,4.93,4.93,0,0,1,1.459,1.052,4.735,4.735,0,0,1,1,1.369,6.54,6.54,0,0,1,.635,2.677c.014.342,0,.418-.074.516a.363.363,0,0,1-.587-.055.955.955,0,0,1-.057-.4,7.086,7.086,0,0,0-.107-1.016,4.673,4.673,0,0,0-1.815-3.014,4.76,4.76,0,0,0-2.749-.97c-.372-.022-.436-.035-.52-.1a.382.382,0,0,1-.014-.547c.092-.084.156-.1.475-.086.166.006.411.026.544.041Zm-4.47.211a1.329,1.329,0,0,1,.235.117,9.47,9.47,0,0,1,1.744,2.228,1.243,1.243,0,0,1,.2.863c-.063.223-.166.34-.63.713a3.409,3.409,0,0,0-.387.345.909.909,0,0,0-.128.441,3.28,3.28,0,0,0,.491,1.372,5.88,5.88,0,0,0,.982,1.154,5.418,5.418,0,0,0,1.289.91c.573.285.923.358,1.179.238a.938.938,0,0,0,.154-.086c.019-.017.17-.2.334-.4.317-.4.389-.463.606-.537a1.049,1.049,0,0,1,.841.076c.215.111.684.4.987.613a14.508,14.508,0,0,1,1.367,1.113.887.887,0,0,1,.1.923,4.325,4.325,0,0,1-1.1,1.371,1.558,1.558,0,0,1-.941.388,1.365,1.365,0,0,1-.737-.154,15.508,15.508,0,0,1-6.677-5.135,14.532,14.532,0,0,1-2.075-3.768c-.276-.759-.289-1.089-.063-1.478a4.343,4.343,0,0,1,.818-.8,2.823,2.823,0,0,1,.923-.553,1.069,1.069,0,0,1,.489.045Zm4.605,1.2a3.782,3.782,0,0,1,2.708,1.619,3.879,3.879,0,0,1,.622,1.73,3.231,3.231,0,0,1,0,.727.444.444,0,0,1-.178.193.436.436,0,0,1-.328-.011c-.151-.076-.2-.2-.2-.525a3.132,3.132,0,0,0-.358-1.453,2.969,2.969,0,0,0-1.091-1.134,3.729,3.729,0,0,0-1.5-.451.5.5,0,0,1-.37-.139.355.355,0,0,1-.029-.441C608-804.6,608.153-804.625,608.62-804.555Zm.416,1.474a1.867,1.867,0,0,1,.933.465,1.931,1.931,0,0,1,.581,1.21c.053.347.031.484-.092.6a.379.379,0,0,1-.458.01c-.094-.071-.123-.145-.145-.346a1.852,1.852,0,0,0-.152-.629,1.108,1.108,0,0,0-.987-.623c-.241-.029-.313-.056-.391-.149a.363.363,0,0,1,.11-.546c.074-.037.105-.041.27-.032A2.6,2.6,0,0,1,609.036-803.081Z" transform="translate(-598 810)" fill="#7f4da0" fill-rule="evenodd"></path>
						                      </g>
						                    </svg>
						                </span>
						            <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) ); ?>" class="head-5-dtls">
						                <?php echo esc_html( $phone ); ?>
						            </a>
						        </div>
						    <?php
						    }
                        ?>
                    </div>
                    <?php if( $vip_image ){
                        ?>
                        <div class="vib-whats-dp">
                            <img src="<?php echo esc_url( $vip_image ); ?>">
                        </div>
                    <?php } ?>
                </div>
                <div class="head-5-contlinks">
                    <?php 
                        if( $email_label ){
                            echo '<h6 class="head5-titl eml-lbl">'.esc_html( $email_label ).'</h6>';
                        } 
                        if( $email ) echo '<div class="contact-email-wrap"><a href="' . esc_url( 'mailto:' . sanitize_email( $email ) ) . '" class="email-link">' . esc_html( $email ) . '</div></a>';
                    ?>
                </div>
            </div>
        </div>
    <?php 
}
/**
 * Footer Middle Div
*/
function travel_muni_footer_middle(){
    $footer_bottom_textarea_left = get_theme_mod( 'footer_bottom_textarea_left',__( 'Travel Muni is a user-friendly, easy-to-use, and powerful travel booking WordPress theme. You can create a professional and SEO-friendly travel website using it.', 'travel-booking-toolkit' ) );
    $footer_phone_label          = get_theme_mod( 'footer_phone_label',__( 'Call us on ...','travel-booking-toolkit' ) );
    $footer_phone                = get_theme_mod( 'footer_phone',__( '+1 014701573', 'travel-booking-toolkit' ) );
    $footer_whatsapp             = get_theme_mod( 'footer_whatsapp',__( '+1 9990605892', 'travel-booking-toolkit' ) );
    $footer_viber                = get_theme_mod( 'footer_viber',__( '+1 999001573', 'travel-booking-toolkit' ) );
    $footer_email_label          = get_theme_mod( 'footer_email_label',__( 'Write us at...','travel-booking-toolkit' ) );
    $footer_email                = get_theme_mod( 'footer_email',__( 'contact@yourdomain.com, sales@example.com', 'travel-booking-toolkit' ) );
    if ( $footer_bottom_textarea_left || $footer_phone_label || $footer_phone || $footer_whatsapp || $footer_viber || $footer_email_label || $footer_email ){
        ?>
        <div class="footer-m">
            <div class="container">
                <div class="footer-m-wrap column-3">
                    <?php if( $footer_bottom_textarea_left ){ ?>
                        <div class="col col-2x">
                            <section class="widget">
                                <?php echo wpautop( wp_kses_post( $footer_bottom_textarea_left ) ); ?>
                            </section>
                        </div>
                    <?php } if( $footer_phone_label || $footer_phone || $footer_whatsapp || $footer_viber ){ ?>
                        <div class="col col-o">
                        <section class="widget widget_contact_numbers">
                            <?php if( $footer_phone_label ) echo '<h2 class="widget-title">'. esc_html( $footer_phone_label ) .'</h2>'; ?>
                            <?php if( $footer_phone || $footer_whatsapp || $footer_viber ){ ?>
                                <ul>
                                <?php if( $footer_phone ){ ?>
                                    <li>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.786" height="17.746" viewBox="0 0 17.786 17.746">
									        <g id="call-answer_2_" data-name="call-answer (2)" transform="translate(0 -0.394)">
									        <g id="Group_1351" data-name="Group 1351" transform="translate(0 0.394)">
									            <g id="Group_1350" data-name="Group 1350" transform="translate(0 0)">
									            <path id="Path_23555" data-name="Path 23555" d="M17.387,14.43,14.64,11.683a1.432,1.432,0,0,0-2.019.038L11.237,13.1l-.273-.152a13.771,13.771,0,0,1-3.329-2.407A13.848,13.848,0,0,1,5.223,7.21c-.051-.093-.1-.182-.149-.267L6,6.016l.457-.457A1.431,1.431,0,0,0,6.5,3.54L3.75.792A1.431,1.431,0,0,0,1.73.83l-.774.779.021.021A4.477,4.477,0,0,0,.34,2.755,4.665,4.665,0,0,0,.056,3.9C-.307,6.9,1.067,9.651,4.8,13.38c5.155,5.154,9.308,4.765,9.488,4.746a4.641,4.641,0,0,0,1.144-.288,4.461,4.461,0,0,0,1.121-.635l.017.015.784-.768A1.433,1.433,0,0,0,17.387,14.43Z" transform="translate(0 -0.394)" fill="#00b98b"/>
									            </g>
									        </g>
									        </g>
									    </svg>
                                        <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $footer_phone ) ); ?>" class="footer-tel-link">
                                            <?php echo esc_html( $footer_phone ); ?>
                                        </a>
                                    </li>
                                <?php }if( $footer_whatsapp ){ ?>
                                    <li>
                                        <svg id="Icons" xmlns="http://www.w3.org/2000/svg" width="19.785" height="19.785" viewBox="0 0 19.785 19.785">
							            <g id="Color-">
							            <path id="Whatsapp" d="M709.89,360a9.886,9.886,0,0,0-8.006,15.691l-1.232,3.676,3.8-1.215A9.891,9.891,0,1,0,709.9,360Zm-2.762,5.025c-.192-.459-.337-.477-.628-.488q-.166-.011-.331-.011a1.435,1.435,0,0,0-1.012.354,3.159,3.159,0,0,0-1.012,2.408,5.653,5.653,0,0,0,1.174,2.984,12.385,12.385,0,0,0,4.924,4.35c2.273.942,2.948.855,3.465.744a2.787,2.787,0,0,0,1.942-1.4,2.456,2.456,0,0,0,.169-1.373c-.07-.122-.262-.192-.552-.337s-1.7-.843-1.971-.936a.552.552,0,0,0-.709.215,12.075,12.075,0,0,1-.773,1.023.624.624,0,0,1-.7.11,7.288,7.288,0,0,1-2.32-1.43,8.8,8.8,0,0,1-1.6-1.995c-.169-.291-.017-.459.116-.616.146-.181.285-.308.43-.477a1.756,1.756,0,0,0,.32-.453.591.591,0,0,0-.041-.536c-.069-.145-.651-1.564-.889-2.14Z" transform="translate(-700 -360)" fill="#67c15e" fill-rule="evenodd"/>
							            </g>
							        </svg>
                                        <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $footer_whatsapp ) ); ?>" class="footer-wa-link">
                                            <?php echo esc_html( $footer_whatsapp ); ?>
                                        </a>
                                    </li>
                                <?php }if( $footer_viber ){ ?>
                                    <li>
                                        <svg id="Icons" xmlns="http://www.w3.org/2000/svg" width="19.785" height="19.785" viewBox="0 0 19.785 19.785">
							            <g id="Color-">
							            <path id="Viber" d="M607.893-810a9.892,9.892,0,0,1,9.893,9.893,9.892,9.892,0,0,1-9.893,9.893A9.892,9.892,0,0,1,598-800.107,9.892,9.892,0,0,1,607.893-810Zm.592,4.029a5.457,5.457,0,0,1,1.813.537,4.93,4.93,0,0,1,1.459,1.052,4.735,4.735,0,0,1,1,1.369,6.54,6.54,0,0,1,.635,2.677c.014.342,0,.418-.074.516a.363.363,0,0,1-.587-.055.955.955,0,0,1-.057-.4,7.086,7.086,0,0,0-.107-1.016,4.673,4.673,0,0,0-1.815-3.014,4.76,4.76,0,0,0-2.749-.97c-.372-.022-.436-.035-.52-.1a.382.382,0,0,1-.014-.547c.092-.084.156-.1.475-.086.166.006.411.026.544.041Zm-4.47.211a1.329,1.329,0,0,1,.235.117,9.47,9.47,0,0,1,1.744,2.228,1.243,1.243,0,0,1,.2.863c-.063.223-.166.34-.63.713a3.409,3.409,0,0,0-.387.345.909.909,0,0,0-.128.441,3.28,3.28,0,0,0,.491,1.372,5.88,5.88,0,0,0,.982,1.154,5.418,5.418,0,0,0,1.289.91c.573.285.923.358,1.179.238a.938.938,0,0,0,.154-.086c.019-.017.17-.2.334-.4.317-.4.389-.463.606-.537a1.049,1.049,0,0,1,.841.076c.215.111.684.4.987.613a14.508,14.508,0,0,1,1.367,1.113.887.887,0,0,1,.1.923,4.325,4.325,0,0,1-1.1,1.371,1.558,1.558,0,0,1-.941.388,1.365,1.365,0,0,1-.737-.154,15.508,15.508,0,0,1-6.677-5.135,14.532,14.532,0,0,1-2.075-3.768c-.276-.759-.289-1.089-.063-1.478a4.343,4.343,0,0,1,.818-.8,2.823,2.823,0,0,1,.923-.553,1.069,1.069,0,0,1,.489.045Zm4.605,1.2a3.782,3.782,0,0,1,2.708,1.619,3.879,3.879,0,0,1,.622,1.73,3.231,3.231,0,0,1,0,.727.444.444,0,0,1-.178.193.436.436,0,0,1-.328-.011c-.151-.076-.2-.2-.2-.525a3.132,3.132,0,0,0-.358-1.453,2.969,2.969,0,0,0-1.091-1.134,3.729,3.729,0,0,0-1.5-.451.5.5,0,0,1-.37-.139.355.355,0,0,1-.029-.441C608-804.6,608.153-804.625,608.62-804.555Zm.416,1.474a1.867,1.867,0,0,1,.933.465,1.931,1.931,0,0,1,.581,1.21c.053.347.031.484-.092.6a.379.379,0,0,1-.458.01c-.094-.071-.123-.145-.145-.346a1.852,1.852,0,0,0-.152-.629,1.108,1.108,0,0,0-.987-.623c-.241-.029-.313-.056-.391-.149a.363.363,0,0,1,.11-.546c.074-.037.105-.041.27-.032A2.6,2.6,0,0,1,609.036-803.081Z" transform="translate(-598 810)" fill="#7f4da0" fill-rule="evenodd"/>
							            </g>
							        </svg>
                                        <a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $footer_viber ) ); ?>" class="footer-viber-link">
                                            <?php echo esc_html( $footer_viber ); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            <?php } ?>
                        </section>
                        </div>
                    <?php }if( $footer_email_label || $footer_email ){
                    $emails = explode( ',', $footer_email);
                     ?>
                        <div class="col col-o">
                            <section class="widget widget_custom_email">
                                <?php if( $footer_email_label ) echo '<h2 class="widget-title">'. esc_html( $footer_email_label ) .'</h2>'; 
                                    if( $footer_email ){ ?>
                                        <ul>
                                             <?php if( $emails ){ 
                                                foreach ($emails as $email ) {
                                                 ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( 'mailto:'.$email ); ?>" class="email-link">
                                                            <?php echo esc_html( $email ); ?>
                                                        </a>
                                                    </li>
                                               <?php
                                                }
                                            } ?>
                                         </ul>
                                    <?php }  
                                ?>
                            </section>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Footer Payments
*/
function travel_muni_footer_payments(){
    $payments_label = get_theme_mod( 'payments_label',__( 'Payments:', 'travel-booking-toolkit' ) );
    $payments_image = get_theme_mod( 'payments_image',get_template_directory_uri().'/images/payment-gateway.png' );
    if( $payments_label && $payments_image ){ ?>
        <div class="payments-showcase">
            <?php if( $payments_label ) echo '<span>'.esc_html( $payments_label ).'</span>'; ?>
            <?php if( $payments_image ) echo '<img src="'.esc_url( $payments_image ).'" alt="'.esc_html( 'Payments Image','travel-muni' ).'"/>'; ?>
        </div>
    <?php }
}

if( ! function_exists( 'travel_muni_recommendation_section' ) ) :
/**
 * Recommendation Section
*/
function travel_muni_recommendation_section( $section_title, $section_desc, $repeater ){
    if( $section_title || $section_desc || $repeater ){ ?>
        <div class="container">
            <div class="all-clients-main-wrap">
                <?php if( $section_title || $section_desc ){ ?>
                    <div class="section-content-wrap algnlft">
                        <?php
                            if( $section_title ) echo '<h2 class="section-title">'.esc_html( $section_title ).'</h2>';
                            if( $section_desc ) echo '<div class="section-desc">'.wpautop( wp_kses_post( $section_desc ) ).'</div>';
                        ?>
                    </div>
                <?php }if( $repeater ){ ?>
                <div class="clients-logo-wrap">
                    <?php foreach( $repeater as $singlerepeater ){ 
                        $repeaterimage = ( isset( $singlerepeater['image'] ) && $singlerepeater['image'] ) ? $singlerepeater['image'] : '';
                        $repeaterlink = ( isset( $singlerepeater['link'] ) && $singlerepeater['link'] ) ? $singlerepeater['link'] : '';
                        ?>
                        <div class="clients-logo-single">
                            <figure>
                                <a href="<?php echo esc_url( $repeaterlink ); ?>"><?php echo wp_get_attachment_image( $repeaterimage, 'full' ); ?></a>
                            </figure>
                        </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php
    }
}
endif;


function travel_muni_get_categories( $select = true, $taxonomy = 'category', $slug = false ){    
    /* Option list of all categories */
    $categories = array();
    
    $args = array( 
        'hide_empty' => false,
        'taxonomy'   => $taxonomy 
    );
    
    $catlists = get_terms( $args );
    if( $catlists && ! is_wp_error( $catlists ) ){
        if( $select ) $categories[''] = __( 'Choose Category', 'travel-booking-toolkit' );
        foreach( $catlists as $category ){
            if( $slug ){
                $categories[$category->slug] = $category->name;
            }else{
                $categories[$category->term_id] = $category->name;    
            }        
        }
    }
    return $categories;
}

function travel_muni_get_posts( $post_type = 'post', $slug = false ){    
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => $post_type,
        'post_status'      => 'publish',
        'suppress_filters' => true 
    );
    $posts_array = get_posts( $args );
    
    // Initate an empty array
    $post_options = array();
    $post_options[''] = __( ' -- Choose -- ', 'travel-booking-toolkit' );
    if ( ! empty( $posts_array ) ) {
        foreach ( $posts_array as $posts ) {
            if( $slug ){
                $post_options[ $posts->post_title ] = $posts->post_title;
            }else{
                $post_options[ $posts->ID ] = $posts->post_title;    
            }
        }
    }
    return $post_options;
    wp_reset_postdata();
}

/**
 * Function to list comments from wte-trip-review plugin
*/
function travel_muni_get_trip_review_comment(){
    
    $comment_args = array( 
        'post_type' => 'trip',
        'status'    => 'approve',
    );
    $comments_qry = new WP_Comment_Query( $comment_args );
    // Initate an empty array
    $comment_options = array();
    $comment_options[''] = __( ' -- Choose -- ', 'travel-booking-toolkit' );
    if ( ! empty( $comments_qry ) ) {
        $comments = $comments_qry->comments;
        foreach ( $comments as $comment ) {
            $comment_meta = get_comment_meta( $comment->comment_ID );
            $comment_options[ $comment->comment_ID ] = ! empty( $comment_meta['title'][0] ) ? $comment_meta['title'][0] : __( 'No comment title', 'travel-booking-toolkit' );
        }
    }
    return $comment_options;
}