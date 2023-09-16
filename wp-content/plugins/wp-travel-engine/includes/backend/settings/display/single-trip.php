<?php
/**
 * Single Trip Display Setting
 *
 * @since 5.5.7
 */

$settings                     = get_option( 'wp_travel_engine_settings', array() );

$checked       = ! isset( $settings['show_related_trips'] ) || 'yes' == $settings['show_related_trips'];
$section_title = ! empty( $settings['related_trips_section_title'] ) ? $settings['related_trips_section_title'] : __( 'Related trips you might interested in', 'wp-travel-engine' );
$no_of_trips   = ! empty( $settings['no_of_related_trips'] ) ? (int) $settings['no_of_related_trips'] : 3;

$related_new_trip_card      = isset( $settings['related_display_new_trip_listing'] ) && 'yes' == $settings['related_display_new_trip_listing'];
$related_difficulty_tax_url = $site_url . '/wp-admin/edit-tags.php?taxonomy=difficulty&post_type=trip';
$related_tag_tax_url        = $site_url . '/wp-admin/edit-tags.php?taxonomy=trip_tag&post_type=trip';
?>
<!-- Hide Booking Form -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[booking]"><?php esc_html_e( 'Hide Booking Form', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[booking]" value="">
		<input type="checkbox"
			id="wp_travel_engine_settings[booking]"
			class="hide-booking"
			name="wp_travel_engine_settings[booking]"
			value="1"
			<?php checked( isset( $settings['booking'] ) && $settings['booking'] !== '', true ); ?>
		/>
		<label for="wp_travel_engine_settings[booking]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'If checked, booking form in the trip detail page will be disabled.', 'wp-travel-engine' ); ?></span>
</div>
<!-- Hide Enquiry Form -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" for="wp_travel_engine_settings[enquiry]"><?php esc_html_e( 'Hide Enquiry Form', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[enquiry]" value="">
		<input type="checkbox"
			id="wp_travel_engine_settings[enquiry]"
			class="hide-enquiry"
			name="wp_travel_engine_settings[enquiry]"
			value="1"
			<?php checked( isset( $settings['enquiry'] ) && $settings['enquiry'] !== '', true ); ?>
		/>
		<label for="wp_travel_engine_settings[enquiry]"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'If checked, enquiry form in the trip detail page will be disabled.', 'wp-travel-engine' ); ?></span>
</div>

<?php
$make_tabs_sticky = isset( $settings['wte_sticky_tabs'] ) || 'yes' === $settings['wte_sticky_tabs'];
?>
<!-- Make tab sticky -->
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wte_sticky_tabs"><?php esc_html_e( 'Make Tabs Sticky and Scrollable', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[wte_sticky_tabs]" value="no" >
		<input type="checkbox" id="wte_sticky_tabs" class="" value="yes"
		name="wp_travel_engine_settings[wte_sticky_tabs]"
		<?php checked( $make_tabs_sticky, true ); ?>
		/>
		<label for="wte_sticky_tabs"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'If checked, the trip content will be scrollable.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_trips]"><?php esc_html_e( 'Show Related Trips', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[show_related_trips]" value="no" />
		<input type="checkbox" id="wp_travel_engine_settings[show_related_trips]"
			name="wp_travel_engine_settings[show_related_trips]"
			value="yes"
			data-onchange
			data-onchange-toggle-target="[data-show-related-trips]"
			data-onchange-toggle-off-value="no"
			<?php checked( $checked, true ); ?> />
		<label for="wp_travel_engine_settings[show_related_trips]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable to display related trips in the trip pages.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field-subfields" data-show-related-trips>
	<div class="wpte-field wpte-floated<?php echo $checked ? '' : ' hidden'; ?>">
		<label class="wpte-field-label" for="related_trips_section_title"><?php echo esc_html( 'Related Section Title' ); ?></label>
		<input type="text" name="wp_travel_engine_settings[related_trips_section_title]" id="related_trips_section_title" value="<?php echo esc_attr( $section_title ); ?>">
	</div>

	<div class="wpte-field wpte-floated <?php echo $checked ? '' : ' hidden'; ?>">
		<label class="wpte-field-label" for="no_of_related_trips"><?php echo esc_html( 'No. of Related Trips to display' ); ?></label>
		<input type="number" name="wp_travel_engine_settings[no_of_related_trips]" id="no_of_related_trips"  value="<?php echo esc_attr( $no_of_trips ); ?>">
	</div>

	<div class="wpte-field wpte-select wpte-floated">
		<label for="wpte_related_trips_show_by" class="wpte-field-label"><?php _e( 'Show Related Trips By', 'wp-travel-engine' ); ?></label>
		<select name="wp_travel_engine_settings[related_trip_show_by]" id="wpte_related_trips_show_by" class="wpte-enhanced-select">
		<?php
		$trip_taxonomies      = get_taxonomies( array( 'object_type' => array( WP_TRAVEL_ENGINE_POST_TYPE ) ), 'objects' );
		$related_trip_show_by = ! empty( $settings['related_trip_show_by'] ) ? $settings['related_trip_show_by'] : 'activities';
		$post_type_object     = get_post_type_object( WP_TRAVEL_ENGINE_POST_TYPE );
		echo wp_kses(
			array_reduce(
				$trip_taxonomies,
				function( $carry, $item ) use ( $related_trip_show_by ) {
					$selected = selected( $related_trip_show_by, $item->name, false );
					return $carry . "<option value=\"{$item->name}\" {$selected}>{$item->label}</option>";
				},
				''
			),
			array(
				'option' => array(
					'value'    => array(),
					'selected' => array(),
				),
			)
		);
		?>
		</select>
	</div>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.7" for="wp_travel_engine_settings[related_display_new_trip_listing]"><?php esc_html_e( 'Enable New Layout for Related Trips', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[related_display_new_trip_listing]" value="no" />
		<input type="checkbox" id="wp_travel_engine_settings[related_display_new_trip_listing]"
			name="wp_travel_engine_settings[related_display_new_trip_listing]"
			value="yes"
			data-onchange
			data-onchange-toggle-target="[data-related-display-new-trip-listing]"
			data-onchange-toggle-off-value="no"
			<?php checked( $related_new_trip_card, true ); ?> />
		<label for="wp_travel_engine_settings[related_display_new_trip_listing]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Enable to display new design in related trip section.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field-subfields<?php echo $related_new_trip_card ? '' : ' hidden'; ?>" data-related-display-new-trip-listing>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_trip_carousel]"><?php esc_html_e( 'Show Slider', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_trip_carousel]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_trip_carousel]"
					name="wp_travel_engine_settings[show_related_trip_carousel]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_trip_carousel]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_trip_carousel'] ) || '1' == $settings['show_related_trip_carousel'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_trip_carousel]" class="checkbox-label"></label>
			</div>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_featured_tag]"><?php esc_html_e( 'Show Featured Tag on Card', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_featured_tag]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_featured_tag]"
					name="wp_travel_engine_settings[show_related_featured_tag]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_featured_tag]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_featured_tag'] ) || '1' == $settings['show_related_featured_tag'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_featured_tag]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Enable to show featured tag on card.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_wishlist]"><?php esc_html_e( 'Show Wishlist', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_wishlist]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_wishlist]"
				value="1"
				name="wp_travel_engine_settings[show_related_wishlist]"
				data-onchange
				data-onchange-toggle-target="[data-show_related_wishlist]"
				data-onchange-toggle-off-value="0" <?php checked( ! isset( $settings['show_related_wishlist'] ) || '1' == $settings['show_related_wishlist'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_wishlist]" class="checkbox-label"></label>
			</div>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_map]"><?php esc_html_e( 'Show Map', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_map]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_map]"
					name="wp_travel_engine_settings[show_related_map]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_map]"
					data-onchange-toggle-off-value="0" <?php checked( ! isset( $settings['show_related_map'] ) || '1' == $settings['show_related_map'], true ); ?>/>
					<label for="wp_travel_engine_settings[show_related_map]" class="checkbox-label"></label>
			</div>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_difficulty_tax]"><?php esc_html_e( 'Show Difficulty', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_difficulty_tax]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_difficulty_tax]"
					name="wp_travel_engine_settings[show_related_difficulty_tax]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_difficulty_tax]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_difficulty_tax'] ) || '1' == $settings['show_related_difficulty_tax'], true ); ?>/>
					<label for="wp_travel_engine_settings[show_related_difficulty_tax]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php echo sprintf( 'Click <a href="%s">here</a> to add difficulty level.', $related_difficulty_tax_url, 'wp-travel-engine' ); ?></span>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_trip_tags]"><?php esc_html_e( 'Show Tag', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_trip_tags]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_trip_tags]"
					name="wp_travel_engine_settings[show_related_trip_tags]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_trip_tags]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_trip_tags'] ) || '1' == $settings['show_related_trip_tags'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_trip_tags]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php echo sprintf( 'Click <a href="%s">here</a> to add a tag.', $related_tag_tax_url, 'wp-travel-engine' ); ?></span>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_date_layout]"><?php esc_html_e( 'Show Next Departure Dates', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_date_layout]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_date_layout]"
					name="wp_travel_engine_settings[show_related_date_layout]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_date_layout]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_date_layout'] ) || '1' == $settings['show_related_date_layout'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_date_layout]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Enable to show next departure dates.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_available_months]"><?php esc_html_e( 'Show Available Months', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_available_months]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_available_months]"
					name="wp_travel_engine_settings[show_related_available_months]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_available_months]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_available_months'] ) || '1' == $settings['show_related_available_dates'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_available_months]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Enable to show available months on card.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>
    <?php if ( class_exists( 'WTE_Fixed_Starting_Dates' ) ):?>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_related_available_dates]"><?php esc_html_e( 'Show Available Dates', 'wp-travel-engine' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="hidden" name="wp_travel_engine_settings[show_related_available_dates]" value="0" />
				<input type="checkbox" id="wp_travel_engine_settings[show_related_available_dates]"
					name="wp_travel_engine_settings[show_related_available_dates]"
					value="1"
					data-onchange
					data-onchange-toggle-target="[data-show_related_available_dates]"
					data-onchange-toggle-off-value="0"
					<?php checked( ! isset( $settings['show_related_available_dates'] ) || '1' == $settings['show_related_available_dates'], true ); ?>/>
				<label for="wp_travel_engine_settings[show_related_available_dates]" class="checkbox-label"></label>
			</div>
			<span class="wpte-tooltip"><?php esc_html_e( 'Enable to show available dates on hover.', 'wp-travel-engine' ); ?></span>
		</div>
	</div>
	<?php endif;?>
</div>
<?php
$show_trip_facts              = isset( $settings['show_trip_facts'] ) && 'yes' === $settings['show_trip_facts'];
$show_trip_facts_sidebar      = isset( $settings['show_trip_facts_sidebar'] ) && 'yes' === $settings['show_trip_facts_sidebar'];
$show_trip_facts_content_area = isset( $settings['show_trip_facts_content_area'] ) && 'yes' === $settings['show_trip_facts_content_area'];
?>
<div class="wpte-field wpte-checkbox advance-checkbox">
	<label class="wpte-field-label" data-wte-update="wte_new_5.5.7" for="wp_travel_engine_settings[show_trip_facts]"><?php esc_html_e( 'Show Trip Info', 'wp-travel-engine' ); ?></label>
	<div class="wpte-checkbox-wrap">
		<input type="hidden" name="wp_travel_engine_settings[show_trip_facts]" value="no" >
		<input type="checkbox" id="wp_travel_engine_settings[show_trip_facts]" name="wp_travel_engine_settings[show_trip_facts]" value="yes"
		data-onchange
		data-onchange-toggle-target="[data-show_trip_facts]"
		data-onchange-toggle-off-value="no"
		<?php checked( $show_trip_facts, true ); ?>>
		<label for="wp_travel_engine_settings[show_trip_facts]" class="checkbox-label"></label>
	</div>
	<span class="wpte-tooltip"><?php esc_html_e( 'Check to display the trip info section in the trip single sidebar.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field-subfields <?php echo $show_trip_facts ? '' : ' hidden'; ?>" data-show_trip_facts>
	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label" for="wp_travel_engine_settings[show_trip_facts_sidebar]"><?php esc_html_e( 'Show Trip Infos on Sidebar', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[show_trip_facts_sidebar]" value="no" >
			<input type="checkbox" id="wp_travel_engine_settings[show_trip_facts_sidebar]" name="wp_travel_engine_settings[show_trip_facts_sidebar]" value="yes" <?php checked( $show_trip_facts_sidebar, true ); ?>>
			<label for="wp_travel_engine_settings[show_trip_facts_sidebar]" class="checkbox-label"></label>
		</div>
		<span class="wpte-tooltip"><?php esc_html_e( 'Check to display the trip info section in the trip single sidebar.', 'wp-travel-engine' ); ?></span>
	</div>
	<div class="wpte-field wpte-checkbox advance-checkbox">
		<label class="wpte-field-label" for="wp_travel_engine_settings[show_trip_facts_content_area]"><?php esc_html_e( 'Show Trip Infos on Main Content Area', 'wp-travel-engine' ); ?></label>
		<div class="wpte-checkbox-wrap">
			<input type="hidden" name="wp_travel_engine_settings[show_trip_facts_content_area]" value="no" >
			<input type="checkbox" id="wp_travel_engine_settings[show_trip_facts_content_area]" name="wp_travel_engine_settings[show_trip_facts_content_area]" value="yes" <?php checked( $show_trip_facts_content_area, true ); ?>>
			<label for="wp_travel_engine_settings[show_trip_facts_content_area]" class="checkbox-label"></label>
		</div>
		<span class="wpte-tooltip"><?php esc_html_e( 'Check to display the trip info section in the trip content area.', 'wp-travel-engine' ); ?></span>
	</div>
</div>
