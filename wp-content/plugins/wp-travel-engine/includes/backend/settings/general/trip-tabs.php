<?php
/**
 * General Trip Tabs file
 */
$default_tabs          = wte_get_default_settings_tab();
$wp_travel_engine_tabs = get_option( 'wp_travel_engine_settings', array() );
$saved_tabs            = isset( $wp_travel_engine_tabs['trip_tabs'] ) && ! empty( $wp_travel_engine_tabs['trip_tabs'] ) ? $wp_travel_engine_tabs['trip_tabs'] : $default_tabs;

if ( ! empty( $saved_tabs ) ) {
	$maxlen   = max( array_keys( $saved_tabs['name'] ) );
	$arr_keys = array_keys( $saved_tabs['name'] );
	?>
	<div class="wpte-repeater-wrap">
		<div class="wpte-repeater-heading">
			<div class="wpte-repeater-title"><?php esc_html_e( 'Tab Name', 'wp-travel-engine' ); ?></div>
			<div class="wpte-repeater-title"><?php esc_html_e( 'Tab Icon', 'wp-travel-engine' ); ?></div>
			<div class="wpte-system-btns-title wpte-repeater-title"><?php esc_html_e( 'Show/Hide', 'wp-travel-engine' ); ?></div>
		</div>
		<div class="wpte-repeater-block-holder wte-global-tabs-holder">
			<?php
			foreach ( $arr_keys as $key => $value ) {
				if ( array_key_exists( $value, $saved_tabs['name'] ) ) {
					$tab_icon = isset( $saved_tabs['icon'][ $value ] ) ? esc_attr( $saved_tabs['icon'][ $value ] ) : '';
					$field_id = isset( $saved_tabs['field'][ $value ] ) ? esc_attr( $saved_tabs['field'][ $value ] ) : '';
					$tab_id   = isset( $saved_tabs['id'][ $value ] ) ? esc_attr( $saved_tabs['id'][ $value ] ) : '';
					$enabled  = isset( $saved_tabs['enable'][ $value ] ) ? $saved_tabs['enable'][ $value ] : 'yes';
					?>
			<div class="wpte-repeater-block wpte-sortable wpte-glb-tab-row">
				<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][id][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_settings[trip_tabs][id][<?php echo esc_attr( $value ); ?>]"
					value="<?php echo esc_attr( $tab_id ); ?>">
				<input type="hidden" class="trip-tabs-field" name="wp_travel_engine_settings[trip_tabs][field][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_settings[trip_tabs][field][<?php echo esc_attr( $value ); ?>]"
					value="<?php echo esc_attr( $field_id ); ?>">
				<div class="wpte-field">
					<input type="text" name="wp_travel_engine_settings[trip_tabs][name][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_settings[trip_tabs][name][<?php echo esc_attr( $value ); ?>]"
						value="<?php echo ( isset( $saved_tabs['name'][ $value ] ) ? esc_attr( stripslashes( $saved_tabs['name'][ $value ] ) ) : '' ); ?>">
				</div>
				<div class="wpte-icons-holder wpte-floated">
					<button class="wpte-add-icon"><?php echo ! empty( $tab_icon ) ? esc_html__( 'Update tab icon', 'wp-travel-engine' ) : esc_html__( 'Add tab icon', 'wp-travel-engine' ); ?></button>
					<span class="wpte-icon-preview">
						<span class="wpte-icon-holdr">
							<?php wptravelengine_svg_by_fa_icon( $tab_icon ); ?>
						</span>
						<?php if( ! empty( $tab_icon ) ):?>
						<button class="wpte-remove-icn-btn"><?php echo esc_html( 'Remove' ); ?></button>
						<?php endif;?>
					</span>
					<input type="hidden" class="trip-tabs-icon" name="wp_travel_engine_settings[trip_tabs][icon][<?php echo esc_attr( $value ); ?>]" id="wp_travel_engine_settings[trip_tabs][icon][<?php echo esc_attr( $value ); ?>]"
						value="<?php echo esc_attr( $tab_icon ); ?>">
				</div>
				<div class="wpte-system-btns">
					<div class="wpte-field wpte-checkbox advance-checkbox">
						<input type="hidden" name="wp_travel_engine_settings[trip_tabs][enable][<?php echo esc_attr( $value ); ?>]" value="no">
						<div class="wpte-checkbox-wrap">
							<input type="checkbox" id="wp_travel_engine_settings[trip_tabs][enable][<?php echo esc_attr( $value ); ?>]" name="wp_travel_engine_settings[trip_tabs][enable][<?php echo esc_attr( $value ); ?>]" value="yes" <?php checked( $enabled, 'yes' ); ?>>
							<label for="wp_travel_engine_settings[trip_tabs][enable][<?php echo esc_attr( $value ); ?>]" class="checkbox-label"></label>
						</div>
					</div>
					<?php
					$def_tabs = array(
						'2' => 'itinerary',
						'3' => 'cost',
						'4' => 'dates',
						'5' => 'faqs',
						'6' => 'map',
					);
					$field    = isset( $wp_travel_engine_tabs['trip_tabs']['field'][ $tab_id ] ) ? $wp_travel_engine_tabs['trip_tabs']['field'][ $tab_id ] : '';
					if ( '1' !== $tab_id && ! in_array( $field, $def_tabs, true ) ) :
						?>
					<button class="wpte-delete wpte-remove-glb-tab"></button>
					<?php endif; ?>
				</div>
			</div> <!-- .wpte-repeater-block -->
					<?php
				}
			}
			?>
		</div>

		<div class="wpte-add-btn-wrap">
			<button class="wpte-add-btn wpte-add-glb-tab"><?php esc_html_e( 'Add Tab', 'wp-travel-engine' ); ?></button>
		</div>
	</div> <!-- .wpte-repeater-wrap -->
	<script type="text/html" id="tmpl-wpte-glb-tabs-row">
		<div class="wpte-repeater-block wpte-sortable wpte-glb-tab-row">
			<input type="hidden" class="trip-tabs-id" name="wp_travel_engine_settings[trip_tabs][id][{{data.key}}]" id="wp_travel_engine_settings[trip_tabs][id][{{data.key}}]"
				value="{{data.key}}">
			<input type="hidden" class="trip-tabs-field" name="wp_travel_engine_settings[trip_tabs][field][{{data.key}}]" id="wp_travel_engine_settings[trip_tabs][field][{{data.key}}]"
				value="wp_editor">
			<div class="wpte-field">
				<input placeholder="<?php echo esc_attr__( 'Tab Label', 'wp-travel-engine' ); ?>" type="text" name="wp_travel_engine_settings[trip_tabs][name][{{data.key}}]" id="wp_travel_engine_settings[trip_tabs][name][{{data.key}}]"
					value="">
			</div>
			<div class="wpte-icons-holder wpte-floated">
				<button class="wpte-add-icon"><?php echo esc_html__( 'Add tab icon', 'wp-travel-engine' ); ?></button>
				<span class="wpte-icon-preview">
					<span class="wpte-icon-holdr">
						<i class=""></i>
					</span>
				</span>
				<input type="hidden" class="trip-tabs-icon" name="wp_travel_engine_settings[trip_tabs][icon][{{data.key}}]" id="wp_travel_engine_settings[trip_tabs][icon][{{data.key}}]" value="">
			</div>
			<div class="wpte-system-btns">
				<div class="wpte-field wpte-checkbox advance-checkbox">
					<input type="hidden" name="wp_travel_engine_settings[trip_tabs][enable][{{data.key}}]" value="no">
					<div class="wpte-checkbox-wrap">
						<input type="checkbox" id="wp_travel_engine_settings[trip_tabs][enable][{{data.key}}]" name="wp_travel_engine_settings[trip_tabs][enable][{{data.key}}]" value="yes" checked>
						<label for="wp_travel_engine_settings[trip_tabs][enable][{{data.key}}]" class="checkbox-label"></label>
					</div>
				</div>
				<button class="wpte-delete wpte-remove-glb-tab"></button>
			</div>
		</div> <!-- .wpte-repeater-block -->
	</script>
	<?php
}
