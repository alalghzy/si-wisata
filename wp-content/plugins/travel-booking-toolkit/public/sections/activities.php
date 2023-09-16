<?php
/**
 * Activities Section
 * 
 * @package WP_Travel_Engine
 */

$defaults         = new Travel_Booking_Toolkit_Dummy_Array;
$obj              = new Travel_Booking_Toolkit_Functions;
$ed_demo          = get_theme_mod( 'ed_activities_demo', true );
$title            = get_theme_mod( 'activities_title', __( 'Browse Activities', 'travel-booking-toolkit' ) );
$content          = get_theme_mod( 'activities_desc', __( 'Show activities to your customers here. You can customize this section from Appearance > Customize > Home Page Settings > Activities Section.', 'travel-booking-toolkit' ) );
$activities_one   = get_theme_mod( 'activities_one' );
$activities_two   = get_theme_mod( 'activities_two' );
$activities_three = get_theme_mod( 'activities_three' );
$activities_four  = get_theme_mod( 'activities_four' );
$activities_five  = get_theme_mod( 'activities_five' );
$activities_six   = get_theme_mod( 'activities_six' );
$activities_seven = get_theme_mod( 'activities_seven' );
$activities_eight = get_theme_mod( 'activities_eight' );
$activities_array = array( $activities_one, $activities_two, $activities_three, $activities_four, $activities_five, $activities_six, $activities_seven, $activities_eight );

$activities_array = array_diff( array_unique( $activities_array ), array('') );

if( $title || $content || ! empty( $activities_array ) || $obj->travel_booking_toolkit_is_wpte_activated()  ){ ?>
	<!-- activities-section -->
	<section id="activities-section" class="activities-section">
		<div class="container">

			<?php if( $title || $content ){ ?>
		        <header class="section-header">
		            <?php 
		                if( $title ) echo '<h2 class="section-title">' . esc_html( travel_booking_toolkit_get_activities_title() ) . '</h2>';
		                if( $content ) echo '<div class="section-content">' . wp_kses_post( travel_booking_toolkit_get_activities_content() ) . '</div>'; 
		            ?>
		        </header>
		    <?php } 

		    if( $obj->travel_booking_toolkit_is_wpte_activated() && $activities_array ){ ?>
				<div class="grid">
					<?php foreach( $activities_array as $activities ){ 
						$act_obj = get_term_by( 'id', $activities, 'activities' );
						
						if( ! empty( $act_obj ) ){
							$act_img_id         = get_term_meta( $act_obj->term_id, 'category-image-id', true );
							$activities_image_size =  apply_filters( 'tbt_activities_image_size', 'thumbnail' ); 

							?>
							<div class="col">
								<div class="img-holder">
									<?php 
										if( ! empty( $act_img_id ) && isset( $act_obj->term_id ) ){
											
											echo '<a href="'. esc_url( get_term_link( $act_obj->term_id ) ) .'">';
											echo wp_get_attachment_image( $act_img_id, $activities_image_size, false, 
	                                        	array( 'alt' => esc_attr( $act_obj->name ))); 
											echo '</a>';
										} 
										else{

											echo '<a href="'. esc_url( get_term_link( $act_obj->term_id ) ) .'"><img src="'. esc_url( TBT_FILE_URL . '/includes/images/destination-image-size.jpg' ) .'" alt="'. esc_attr( $act_obj->name ) .'"></a>';
										}
									?>
									
								</div>
								<h2 class="activities-title"><a href="<?php echo esc_url( get_term_link( $act_obj->term_id ) ); ?>"><?php echo esc_html( $act_obj->name ); ?></a></h2>
							</div>
						<?php }
						} 
					?>
				</div><!-- .grid -->
			<?php } elseif( $ed_demo ) {
				$featured = $defaults->travel_booking_toolkit_default_activities(); ?>
				<div class="grid">
					<?php foreach( $featured as $v ){ ?>
						<div class="col">
							<div class="img-holder">
								<img src="#" alt=""><a href="#"><img src="<?php echo esc_url( $v['img'] ); ?>" alt="<?php echo esc_attr( $v['title'] ) ?>"></a>
							</div>
							<h2 class="activities-title"><a href="#"><?php echo esc_html( $v['title'] ) ?></a></h2>
						</div>
						<?php
					}?>
				</div><!-- .grid -->
			<?php } ?>
		</div>
	</section>
<?php }