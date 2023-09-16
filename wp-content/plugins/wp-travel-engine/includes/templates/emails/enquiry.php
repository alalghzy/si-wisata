<?php
/**
 * Enquiry notification emails.
 */
$formdata = $args['form_data'];

?>
<table class="main" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="content-wrap aligncenter">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="content-block">
						<h1 class="aligncenter"><?php echo esc_html__( 'New Enquiry', 'wp-travel-engine' ); ?></h1>
					</td>
				</tr>
				<tr>
					<td class="content-block">
						<h3 class="aligncenter"><?php echo esc_html__( 'Enquiry Details', 'wp-travel-engine' ); ?></h3>
					</td>
				</tr>
				<tr>
					<td class="content-block aligncenter">
						<table class="invoice">
							<tr>
								<td style="margin: 0; padding: 5px 0;" valign="top">
									<table class="invoice-items" cellpadding="0" cellspacing="0">
										<?php
										foreach ( $formdata as $key => $data ) :
											$data        = is_array( $data ) ? implode( ', ', $data ) : $data;
											$field_label = wp_travel_engine_get_enquiry_field_label_by_name( $key );
											?>
										<tr>
											<td><?php echo esc_html( $field_label ); ?></td>
											<td class="alignright">
												<?php
												if ( in_array( $key, array( 'package_name', 'enquiry_message' ) ) ) {
													echo wp_kses(
														$data,
														array(
															'a' => array( 'href' => array() ),
															'b' => array(),
														)
														);
												} else {
													echo esc_html( $data );
												}
												?>
											</td>
										</tr>
										<?php endforeach; ?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
