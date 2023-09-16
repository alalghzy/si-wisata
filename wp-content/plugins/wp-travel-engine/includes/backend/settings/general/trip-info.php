<?php
/**
 * Trip info tab settings / content.
 *
 * @package WP_Travel_Engine
 */
// Get settings.
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', array() );
?>
<div class="wpte-repeater-wrap">
	<div class="wpte-repeater-heading">
		<div class="wpte-repeater-title"><?php esc_html_e( 'Field Name', 'wp-travel-engine' ); ?></div>
		<div class="wpte-repeater-title"><?php esc_html_e( 'Field Icon', 'wp-travel-engine' ); ?></div>
		<div class="wpte-repeater-title"><?php esc_html_e( 'Field Type', 'wp-travel-engine' ); ?></div>
		<div class="wpte-repeater-title"><?php esc_html_e( 'Field Placeholder', 'wp-travel-engine' ); ?></div>
		<div class="wpte-repeater-title"></div>
	</div>

	<div class="wpte-repeater-block-holder wpte-glb-trp-infos-holdr">
		<?php
		$trip_facts = wptravelengine_get_trip_facts_options();
		if ( ! empty( $trip_facts['fid'] ) ) {

			// Get vars
			$arr_keys = array_keys( $trip_facts['field_id'] );
			$len      = count( $trip_facts['field_id'] );
			$i        = 1;
			$fields   = wte_functions()->trip_facts_field_options();

			foreach ( $arr_keys as $key => $value ) {
				$fact_icon = isset( $trip_facts['field_icon'][ $value ] ) ? esc_attr( $trip_facts['field_icon'][ $value ] ) : '';
				?>
				<div class="wpte-repeater-block wpte-sortable wpte-glb-trp-infos-row">
					<div class="wpte-field">
						<input type="hidden" name="wp_travel_engine_settings[trip_facts][fid][<?php echo esc_attr( $value ); ?>]" value="<?php echo isset( $trip_facts['fid'][ $value ] ) ? esc_attr( $trip_facts['fid'][ $value ] ) : ''; ?>">
						<input type="text" name="wp_travel_engine_settings[trip_facts][field_id][<?php echo esc_attr( $value ); ?>]" value="<?php echo isset( $trip_facts['field_id'][ $value ] ) ? esc_attr( $trip_facts['field_id'][ $value ] ) : ''; ?>" required>
					</div>
					<div class="wpte-icons-holder wpte-floated">
						<button class="wpte-add-icon"><?php echo ! empty( $fact_icon ) ? esc_html__( 'Update fact icon', 'wp-travel-engine' ) : esc_html__( 'Add fact icon', 'wp-travel-engine' ); ?></button>
						<span class="wpte-icon-preview">
							<span class="wpte-icon-holdr">
								<?php wptravelengine_svg_by_fa_icon( $fact_icon ); ?>
							</span>
							<?php if( ! empty( $fact_icon ) ):?>
								<button class="wpte-remove-icn-btn"><?php echo esc_html( 'Remove' ); ?></button>
							<?php endif;?>
						</span>
						<input class="trip-tabs-icon" type="hidden" name="wp_travel_engine_settings[trip_facts][field_icon][<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $fact_icon ); ?>">
					</div>
					<?php
					if ( isset( $fields[ $trip_facts['field_type'][ $value ] ] ) ) :
						?>
					<div class="wpte-trp-inf-fieldtyp wpte-field">
						<select id="wp_travel_engine_settings[trip_facts][field_type][<?php echo esc_attr( $value ); ?>]" name="wp_travel_engine_settings[trip_facts][field_type][<?php echo esc_attr( $value ); ?>]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select">
							<option value=" "><?php esc_html_e( 'Choose input type&hellip;', 'wp-travel-engine' ); ?></option>
							<?php
							$fields         = wte_functions()->trip_facts_field_options();
							$selected_field = esc_attr( $trip_facts['field_type'][ $value ] );
							foreach ( $fields as $key => $val ) {
								echo '<option value="' . ( ! empty( $key ) ? esc_attr( $key ) : 'Please select' ) . '" ' . selected( $selected_field, $val, false ) . '>' . esc_html( $key ) . '</option>';
							}
							?>
						</select>
					</div>
					<?php else : ?>
						<div class="wpte-trp-inf-fieldtyp wpte-field">
							<input type="text"
								readonly name="wp_travel_engine_settings[trip_facts][field_type][<?php echo esc_attr( $value ); ?>]"
								id="trip_facts_field_type_<?php echo esc_attr( $value ); ?>"
								value="<?php echo esc_attr( $trip_facts['field_type'][ $value ] ); ?>"
							/>
						</div>
					<?php endif; ?>
					<div class="wpte-field">
						<div class="select-options">
							<textarea id="wp_travel_engine_settings[trip_facts][select_options][<?php echo esc_attr( $value ); ?>]" name="wp_travel_engine_settings[trip_facts][select_options][<?php echo esc_attr( $value ); ?>]" rows="2" cols="25" required placeholder="<?php esc_attr_e( 'Enter drop-down values separated by commas', 'wp-travel-engine' ); ?>"><?php echo isset( $trip_facts['select_options'][ $value ] ) ? esc_html( $trip_facts['select_options'][ $value ] ) : ''; ?></textarea>
						</div>
						<div class="input-placeholder">
							<input type="text" name="wp_travel_engine_settings[trip_facts][input_placeholder][<?php echo esc_attr( $value ); ?>]" value="<?php echo isset( $trip_facts['input_placeholder'][ $value ] ) ? esc_attr( $trip_facts['input_placeholder'][ $value ] ) : ''; ?>">
						</div>
					</div>
					<div class="wpte-system-btns">
						<button class="wpte-delete wpte-remove-glb-ti"></button>
					</div>
				</div>
				<?php
			}
		}

		$default_trip_facts = wptravelengine_get_trip_facts_default_options();
		if ( is_array( $default_trip_facts ) ) {
			foreach ( $default_trip_facts as $_id => $_trip_fact ) {
				$fact_icon = isset( $_trip_fact['field_icon'] ) ? esc_attr( $_trip_fact['field_icon'] ) : '';
				?>
				<div class="wpte-repeater-block wpte-sortable wpte-glb-trp-infos-row">
					<div class="wpte-field">
						<input type="hidden" name="wp_travel_engine_settings[default_trip_facts][<?php echo $_id; ?>][fid]" value="<?php echo isset( $_trip_fact['fid'] ) ? esc_attr( $_trip_fact['fid'] ) : ''; ?>">
						<input type="text" name="wp_travel_engine_settings[default_trip_facts][<?php echo $_id; ?>][field_id]" value="<?php echo isset( $_trip_fact['field_id'] ) ? esc_attr( $_trip_fact['field_id'] ) : ''; ?>" required>
					</div>
					<div class="wpte-icons-holder wpte-floated">
						<button class="wpte-add-icon"><?php echo ! empty( $fact_icon ) ? esc_html__( 'Update fact icon', 'wp-travel-engine' ) : esc_html__( 'Add fact icon', 'wp-travel-engine' ); ?></button>
						<span class="wpte-icon-preview">
							<span class="wpte-icon-holdr">
								<?php wptravelengine_svg_by_fa_icon( $fact_icon ); ?>
							</span>
							<?php if( ! empty( $fact_icon ) ):?>
								<button class="wpte-remove-icn-btn"><?php echo esc_html( 'Remove' ); ?></button>
							<?php endif;?>
						</span>
						<input class="trip-tabs-icon" type="hidden" name="wp_travel_engine_settings[default_trip_facts][<?php echo $_id; ?>][field_icon]" value="<?php echo esc_attr( $fact_icon ); ?>">
					</div>
					<div class="wpte-trp-inf-fieldtyp wpte-field">
						<input type="text"
							readonly name="wp_travel_engine_settings[default_trip_facts][<?php echo $_id; ?>][field_type]"
							id="trip_facts_field_type_<?php echo esc_attr( $_id ); ?>"
							value="<?php echo esc_attr( $_trip_fact['field_type'] ); ?>"
						/>
					</div>
					<div class="wpte-field">
						<div class="input-placeholder">
							<input readonly type="text" name="wp_travel_engine_settings[default_trip_facts][<?php echo $_id; ?>][input_placeholder]" value="<?php echo isset( $_trip_fact['input_placeholder'] ) ? esc_attr( $_trip_fact['input_placeholder'] ) : ''; ?>">
						</div>
					</div>
					<div class="wpte-system-btns">
						<div class="wpte-field wpte-checkbox advance-checkbox">
							<input type="hidden" name="wp_travel_engine_settings[default_trip_facts][<?php echo esc_attr( $_id ); ?>][enabled]" value="no">
							<div class="wpte-checkbox-wrap">
								<input <?php checked( $_trip_fact['enabled'], 'yes' ); ?> type="checkbox" id="wp_travel_engine_settings[default_trip_facts][<?php echo esc_attr( $_id ); ?>][enabled]" name="wp_travel_engine_settings[default_trip_facts][<?php echo esc_attr( $_id ); ?>][enabled]" value="yes">
								<label for="wp_travel_engine_settings[default_trip_facts][<?php echo esc_attr( $_id ); ?>][enabled]" class="checkbox-label"></label>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
</div> <!-- .wpte-repeater-wrap -->
<div class="wpte-add-btn-wrap">
	<button class="wpte-add-btn wpte-add-glb-trp-info"><?php esc_html_e( 'Add trip info', 'wp-travel-engine' ); ?></button>
</div>
<script type="text/html" id="tmpl-wpte-add-trip-info-block">
	<div class="wpte-repeater-block wpte-sortable wpte-glb-trp-infos-row">
		<div class="wpte-field">
			<input type="hidden" name="wp_travel_engine_settings[trip_facts][fid][{{data.key}}]" value="{{data.key}}">
			<input type="text" name="wp_travel_engine_settings[trip_facts][field_id][{{data.key}}]" value="" required>
		</div>
		<div class="wpte-icons-holder wpte-floated">
			<button class="wpte-add-icon"><?php echo esc_html__( 'Add fact icon', 'wp-travel-engine' ); ?></button>
			<span class="wpte-icon-preview">
				<span class="wpte-icon-holdr">
					<i class=""></i>
				</span>
			</span>
			<input class="trip-tabs-icon" type="hidden" name="wp_travel_engine_settings[trip_facts][field_icon][{{data.key}}]" value="">
		</div>
		<div class="wpte-trp-inf-fieldtyp wpte-field">
			<select id="wp_travel_engine_settings[trip_facts][field_type][{{data.key}}]" name="wp_travel_engine_settings[trip_facts][field_type][{{data.key}}]" data-placeholder="<?php esc_attr_e( 'Choose a field type&hellip;', 'wp-travel-engine' ); ?>" class="wc-enhanced-select">
					<option value=" "><?php esc_html_e( 'Choose input type&hellip;', 'wp-travel-engine' ); ?></option>
				<?php
					$obj    = new Wp_Travel_Engine_Functions();
					$fields = $obj->trip_facts_field_options();
				foreach ( $fields as $key => $val ) {
					echo '<option value="' . ( ! empty( $key ) ? esc_attr( $key ) : 'Please select' ) . '">' . esc_html( $key ) . '</option>';
				}
				?>
			</select>
		</div>
		<div class="wpte-field">
			<div style="display:none" class="select-options">
				<textarea id="wp_travel_engine_settings[trip_facts][select_options][{{data.key}}]" name="wp_travel_engine_settings[trip_facts][select_options][{{data.key}}]" rows="2" cols="25" required placeholder="<?php esc_attr_e( 'Enter drop-down values separated by commas', 'wp-travel-engine' ); ?>"></textarea>
			</div>
			<div class="input-placeholder">
				<input type="text" name="wp_travel_engine_settings[trip_facts][input_placeholder][{{data.key}}]" value="">
			</div>
		</div>
		<div class="wpte-system-btns">
			<button class="wpte-delete wpte-remove-glb-ti"></button>
		</div>
	</div>
</script>
<!-- Global Trip Highlights -->
<div>
	<h2><?php echo esc_html__( 'Global Trip Highlights', 'wp-travel-engine' ); ?></h2>
	<input type="hidden" name="wp_travel_engine_settings[trip_highlights]">
	<ul id="wte-trip-higlights-holder">
	</ul>
	<div class="wpte-add-btn-wrap">
		<button class="wpte-add-btn wpte-add-trip-highlights"><?php esc_html_e( 'Add trip highlight', 'wp-travel-engine' ); ?></button>
	</div>
	<script type="text/html" id="tmpl-wpte-highlight-item">
	<#
		var index = data.index,
		highlight = data.highlight,
		help = data.helpText;
	#>
		<li style="display:flex" id="wte-highlight-{{index}}">
			<div class="wpte-field wpte-floated" style="flex:0 0 25%;">
				<input type="text" name="wp_travel_engine_settings[trip_highlights][{{index}}][highlight]" value="{{highlight}}" placeholder="<?php echo esc_attr__( 'Trip highlight', 'wp-travel-engine' ); ?>"/>
			</div>
			<div class="wpte-field wpte-floated" style="flex:0 0 25%; margin-left: 15px;">
				<input type="text" name="wp_travel_engine_settings[trip_highlights][{{index}}][help]" value="{{help}}" placeholder="<?php echo esc_attr__( 'Help Text', 'wp-travel-engine' ); ?>"/>
			</div>
			<button class="wpte-delete wpte-delete-trip-highlights" data-target="#wte-highlight-{{index}}"></button>
		</li>
	</script>
	<?php
	$highlights     = isset( $wp_travel_engine_settings['trip_highlights'] ) && is_array( $wp_travel_engine_settings['trip_highlights'] ) ? $wp_travel_engine_settings['trip_highlights'] : array();
	$var_highlights = 'var highlights = ' . wp_json_encode( $highlights ) . ";\n";
	?>
	<script>
		;(function() {
			<?php echo $var_highlights; // phpcs:ignore ?>
			var highlightHolder = document.getElementById( 'wte-trip-higlights-holder' );
			var addBtn = document.querySelector( '.wpte-add-trip-highlights' );

			document.addEventListener('click', function(e) {
				if( e.target.classList.contains('wpte-delete-trip-highlights') )
					document.querySelector(e.target.dataset.target).remove()
			})
			addBtn.addEventListener('click', function(e) {
				e.preventDefault()
				var hlCounts = highlightHolder.querySelector('li') && highlightHolder.querySelectorAll('li').length || 0
				var div = document.createElement('div')
				div.innerHTML = wp.template('wpte-highlight-item')({
					index: hlCounts,
					highlight: '',
					help: ''
				})
				highlightHolder.appendChild(div.firstElementChild)
			})

			window.addEventListener('load', function() {
				var index = 0;
				var _html = Object.values(highlights).length > 0 && Object.values( highlights ).reduce(function(acc, curr){
					var _acc = ''
					if(typeof acc !== 'string') {
						_acc = wp.template('wpte-highlight-item')({
							index: index,
							highlight: acc.highlight,
							helpText: acc.help
						}) + window.wp.template('wpte-highlight-item')({
							index: ++index,
							highlight: curr.highlight,
							helpText: curr.help
						})
						return _acc
					}
					return acc + window.wp.template('wpte-highlight-item')({
							index: ++index,
							highlight: curr.highlight,
							helpText: curr.help
						})
				}) || ''

				highlightHolder.innerHTML = _html
			})
		})();
	</script>
</div>
