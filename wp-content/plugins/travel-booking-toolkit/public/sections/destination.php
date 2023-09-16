<?php
/**
 * Destination Section
 * 
 * @package WP_Travel_Engine
 */

$defaults          = new Travel_Booking_Toolkit_Dummy_Array;
$obj               = new Travel_Booking_Toolkit_Functions;
$ed_demo           = get_theme_mod( 'ed_destination_demo', true );
$title             = get_theme_mod( 'destination_title', __( 'Popular Destinations', 'travel-booking-toolkit' ) );
$content           = get_theme_mod( 'destination_desc', __( 'How the special deals and discounts to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Deals Section', 'travel-booking-toolkit' ) );
$destination_one   = get_theme_mod( 'destination_one' );
$destination_two   = get_theme_mod( 'destination_two' );
$destination_three = get_theme_mod( 'destination_three' );
$destination_four  = get_theme_mod( 'destination_four' );
$destinations      = array( $destination_one, $destination_two, $destination_three, $destination_four );
$destinations      = array_diff( array_unique( $destinations ), array('') );
$view_all          = get_theme_mod( 'destination_view_all_label', __( 'View All Destinations', 'travel-booking-toolkit' ) );
$view_url          = get_theme_mod( 'destination_view_all_url', '#' );

if( $title || $content || ! empty( $destinations ) || $obj->travel_booking_toolkit_is_wpte_activated() ){ ?>
	<section id="destination-section" class="popular-destination">
		<div class="container">

			<?php if( $title || $content ){ ?>
		        <header class="section-header">
		            <?php 
		                if( $title ) echo '<h2 class="section-title">' . esc_html( travel_booking_toolkit_get_destination_title() ) . '</h2>';
		                if( $content ) echo '<div class="section-content">' . wp_kses_post( travel_booking_toolkit_get_destination_content() ) . '</div>'; 
		            ?>
		        </header>
		    <?php } 

		    if( $obj->travel_booking_toolkit_is_wpte_activated() && $destinations ){ ?>
				<div class="grid">
					<?php foreach( $destinations as $destination ){ 
						$des_obj            = get_term_by( 'id', $destination, 'destination' );

						if( ! empty( $des_obj ) ){
							$des_img_id         = get_term_meta( $des_obj->term_id, 'category-image-id', true );
							$des_trip_count     = $des_obj->count;
							$destionation_count = sprintf( _nx( '%1$s Trip', '%1$s Trips', $des_trip_count, 'Trip Count', 'travel-booking-toolkit' ), number_format_i18n( $des_trip_count ) );

							$destination_image_size =  apply_filters( 'tbt_destination_image_size', 'thumbnail' ); 

							?>
							<div class="col">
								<div class="img-holder">
									<?php 
										if( ! empty( $des_img_id ) && isset( $des_obj->term_id ) ){
											echo '<a href="'. esc_url( get_term_link( $des_obj->term_id ) ) .'">';
											echo wp_get_attachment_image( $des_img_id, $destination_image_size, false, 
	                                        	array( 'alt' => esc_attr( $des_obj->name ))); 
											echo '</a>';
										}
										else{
											echo '<a href="'. esc_url( get_term_link( $des_obj->term_id ) ) .'"><img src="'. TBT_FILE_URL . '/includes/images/destination-image-size.jpg' .'" alt="'. esc_attr( $des_obj->name ) .'"></a>';
										} 
									?>
									<span class="trip-count"><?php echo esc_html( $destionation_count ) ?></span>
								</div>
								<h2 class="trip-title"><a href="<?php echo esc_url( get_term_link( $des_obj->term_id ) ); ?>"><?php echo esc_html( $des_obj->name ); ?></a></h2>
							</div>
						<?php }
					} ?>
				</div>
			<?php } elseif( $ed_demo ) {
				$featured = $defaults->travel_booking_toolkit_default_destination();
				?>
				<div class="grid">
					<?php foreach( $featured as $v ){ ?>
						<div class="col">
							<div class="img-holder">
								<a href="#"><img src="<?php echo esc_url( $v['img'] ); ?>" alt="<?php echo esc_attr( $v['title'] ) ?>"></a>
								<span class="trip-count"><?php echo esc_html( $v['trip_count'] ) ?></span>
							</div>
							<h2 class="trip-title"><a href="#"><?php echo esc_html( $v['title'] ) ?></a></h2>
						</div>
					<?php } ?>
				</div><!-- .grid -->
				<?php
			} 

			if( ! empty( $view_all ) && ! empty( $view_url ) ){ ?>
                <div class="btn-holder">
                    <a href="<?php echo esc_url( $view_url ); ?>" class="primary-btn view-all-btn"><?php echo esc_html( $view_all ); ?></a>
                </div>
                <?php 
            } ?>

		</div>
	</section>
<?php
}