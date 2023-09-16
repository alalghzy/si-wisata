<?php
/**
 * Global post call inside the edit metabox.
 *
 * @package WP_Travel_Engine
 *
 * @access Admin
 */
// POST
global $post;

$wp_travel_engine_setting          = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
$wp_travel_engine_enquiry_formdata = get_post_meta( $post->ID, 'wp_travel_engine_enquiry_formdata', true );
$wte_old_enquiry_details           = isset( $wp_travel_engine_setting['enquiry'] ) ? $wp_travel_engine_setting['enquiry'] : array();

?>
<div class="wpte-main-wrap wpte-edit-enquiry">
	<div class="wpte-block-wrap">
		<div class="wpte-block">
			<div class="wpte-block-content">
				<ul class="wpte-list">
					<?php
					if ( ! empty( $wp_travel_engine_enquiry_formdata ) ) :
						foreach ( $wp_travel_engine_enquiry_formdata as $key => $data ) :
							$data       = is_array( $data ) ? implode( ', ', $data ) : $data;
							$data_label = wp_travel_engine_get_enquiry_field_label_by_name( $key );

							if ( 'package_name' === $key ) {
								$data_label = esc_html__( 'Package Name', 'wp-travel-engine' );
							}
							?>
							<li>
								<b><?php echo esc_html( $data_label ); ?></b>
								<span>
									<?php echo wp_kses_post( $data ); ?>
								</span>
							</li>
							<?php
							endforeach;
						else :
							if ( ! empty( $wte_old_enquiry_details ) ) :
								if ( isset( $wte_old_enquiry_details['pname'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Package Name', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['pname'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['name'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Name', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['name'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['email'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Email', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['email'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['country'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Country', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['country'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['contact'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Contact', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['contact'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['adults'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Adults', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['adults'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['children'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Children', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['children'] ); ?>
										</span>
									</li>
									<?php
								endif;
								if ( isset( $wte_old_enquiry_details['message'] ) ) :
									?>
									<li>
										<b><?php esc_html_e( 'Message', 'wp-travel-engine' ); ?></b>
										<span>
													<?php echo wp_kses_post( $wte_old_enquiry_details['message'] ); ?>
										</span>
									</li>
									<?php
								endif;
							endif;
						endif;
						?>
				</ul>
			</div>
		</div> <!-- .wpte-block -->
	</div> <!-- .wpte-block-wrap -->
</div><!-- .wpte-main-wrap -->
<?php
