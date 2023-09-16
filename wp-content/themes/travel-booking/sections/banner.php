<?php
/**
 * Banner Section
 * 
 * @package Travel_Booking
 */

$enable_banner  = get_theme_mod( 'ed_banner_section', true );
$banner_title   = get_theme_mod( 'banner_title', __( 'Book unique homes and experiences all over the world.', 'travel-booking' ) );
$button_label   = get_theme_mod( 'banner_btn_label', __( 'GET STARTED', 'travel-booking' ) );
$button_url     = get_theme_mod( 'banner_btn_url', '#' );
$banner_content = get_custom_header_markup();

$class = has_header_video() ? 'video-banner' : '';

if( $enable_banner && ! empty( $banner_content ) ){ ?>  
    <div id="banner-section" class="banner <?php echo esc_attr( $class ); ?>">
    	<?php 
            the_custom_header_markup(); 
            
            if( $banner_title || ( $button_label && $button_url ) ){ ?>	
                <div class="banner-text">
            		<?php 
                        if( $banner_title ) echo '<h2 class="title">' . esc_html( $banner_title ) . '</h2>';
                        if( $button_label && $button_url ) echo '<a href="'. esc_url( $button_url ) .'" class="primary-btn">'. esc_html( $button_label ) .'</a>';
                    ?>
            	</div>            
            <?php 
            }
        ?>
    </div> <!-- banner ends -->
<?php
}