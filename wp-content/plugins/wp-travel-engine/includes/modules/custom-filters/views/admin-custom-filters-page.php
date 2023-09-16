<?php
/**
 * Custom Filters Page.
 */
$label        = '';
$slug         = '';
$singular     = __( 'Category', 'wp-travel-engine' );
$hierarchical = true;
$show = true;
$mode         = 'add';

if ( ! empty( $_GET['edit_filter'] ) ) {
	$filter = wte_clean( wp_unslash( wte_array_get( get_option( 'wte_custom_filters', array() ), $_GET['edit_filter'] ) ) );
	if ( $filter ) {
		$mode = 'edit';
		extract( $filter );
	}
}

$nonce = wp_create_nonce( '_add_filter_nonce' );
?>
<div id="setting-error-tgmpa"></div>
<div class="wrap nosubsub">
	<h1><?php esc_html_e( 'Custom Filters', 'wp-travel-engine' ); ?></h1>
	<div id="col-container" class="wp-clearfix">
		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap">
					<h2><?php ! empty( $_GET['edit_filter'] ) ? esc_html_e( 'Update Filter', 'wp-travel-engine' ) : esc_html_e( 'Add new filter', 'wp-travel-engine' ); ?></h2>
					<form action="" method="post">
						<input type="hidden" name="wte_action" value="add_filter">
						<input type="hidden" name="object_type" value="<?php echo esc_attr( WP_TRAVEL_ENGINE_POST_TYPE ); ?>">
						<input type="hidden" id="_add_filter_nonce" name="_nonce" value="<?php echo esc_attr( $nonce ); ?>">
						<div class="form-field">
							<label for="label"><?php esc_html_e( 'Filter/Category Label', 'wp-travel-engine' ); ?></label>
							<input type="text" name="filter_label" id="fliter-label" value="<?php echo esc_attr( $label ); ?>" />
							<p><?php esc_html_e( 'General name for the filter, usually plural.', 'wp-travel-engine' ); ?></p>
						</div>
						<div class="form-field">
							<label for="label"><?php esc_html_e( 'Filter/Category Slug', 'wp-travel-engine' ); ?></label>
							<input <?php wte_readonly( 'edit', $mode ); ?>  type="text" name="filter_slug" id="filter-slug" value="<?php echo esc_attr( $slug ); ?>">
							<p><?php esc_html_e( 'Slug for filter or Category.', 'wp-travel-engine' ); ?></p>
						</div>
						<div class="form-field">
							<label for="label"><?php esc_html_e( 'Hierarchical', 'wp-travel-engine' ); ?></label>
							<input type="checkbox" name="filter_is_hierarchical" id="label" value="yes" <?php checked( true, $hierarchical ); ?> />
							<p><?php esc_html_e( 'If checked the new filter will be treated as WordPress deafult categories else as tags.', 'wp-travel-engine' ); ?></p>
						</div>
						<div class="form-field">
							<label for="label"><?php esc_html_e( 'Show in filters', 'wp-travel-engine' ); ?></label>
							<input type="checkbox" name="show_in_filters" id="label" value="yes" <?php checked( true, $show ); ?> />
							<p><?php esc_html_e( 'If checked the filter will be available as filter by option.', 'wp-travel-engine' ); ?></p>
						</div>
						<p class="submit">
							<input class="button button-primary" type="submit" name="submit" value="<?php ! empty( $_GET['edit_filter'] ) ? esc_html_e( 'Update Filter', 'wp-travel-engine' ) : esc_html_e( 'Add new filter', 'wp-travel-engine' ); ?>">
						</p>
					</form>
				</div>
			</div>
		</div>
		<div id="col-right">
			<div class="col-wrap">
				<form action="">
					<table class="wp-list-table widefat fixed striped table-view-list">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Label', 'wp-travel-engine' ); ?></th>
								<th><?php esc_html_e( 'Slug', 'wp-travel-engine' ); ?></th>
								<th><?php esc_html_e( 'Hierarchical', 'wp-travel-engine' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$filters = get_option( 'wte_custom_filters', array() );
							if ( count( $filters ) > 0 ) :
								foreach ( $filters as $_filter ) :
									?>
									<tr>
										<td>
											<strong><a href="<?php
											echo esc_url( add_query_arg(
												[
													'taxonomy' => $_filter['slug'],
													'post_type' => WP_TRAVEL_ENGINE_POST_TYPE,
												],
												admin_url('edit-tags.php')
											) );
											?>"><?php echo esc_html( $_filter['label'] ); ?></a></strong>
											<div class="row-actions">
												<span class="edit"><a href="
												<?php
												echo esc_url(
													add_query_arg(
														array(
															'post_type' => 'trip',
															'page' => 'custom-filters',
															'edit_filter' => $_filter['slug'],
															'_nonce' => $nonce,
														),
														admin_url( 'edit.php' )
													)
												);
												?>
												"><?php esc_html_e( 'Edit', 'wp-travel-engine' ); ?></a></span>|
												<span class="delete"><a href="
												<?php
												echo esc_url(
													add_query_arg(
														array(
															'post_type' => 'trip',
															'page' => 'custom-filters',
															'delete_filter' => $_filter['slug'],
															'_nonce' => $nonce,
														),
														admin_url( 'edit.php' )
													)
												);
												?>
												"><?php esc_html_e( 'Delete', 'wp-travel-engine' ); ?></a></span>
											</div>
										</td>
										<td><?php echo esc_html( $_filter['slug'] ); ?></td>
										<td><?php echo $_filter['hierarchical'] ? __( 'Yes', 'wp-travel-engine' ) : __( 'No', 'wp-travel-engine' ); ?></td>
									</tr>
									<?php
								endforeach;
							else :
								?>
								<tr class="no-items">
									<td class="colspanchange" colspan="3"><?php esc_html_e( 'No filters found', 'wp-travel-engine' ); ?></td>
								</tr>
								<?php
							endif;
							?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
