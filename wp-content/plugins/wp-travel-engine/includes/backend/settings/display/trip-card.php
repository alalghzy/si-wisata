<?php
/**
 * Trip Card Display Settings
 */
$site_url = get_site_url();
$trip_page_url = $site_url . '/trip/';
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', [] );
$new_trip_card = isset( $wp_travel_engine_settings['display_new_trip_listing'] ) && 'yes' == $wp_travel_engine_settings['display_new_trip_listing'];
$new_slider_layout = ! isset( $wp_travel_engine_settings['display_slider_layout'] ) || isset( $wp_travel_engine_settings['display_slider_layout'] ) && '1' == $wp_travel_engine_settings['display_slider_layout'];
$show_wishlist = ! isset( $wp_travel_engine_settings['show_wishlist'] ) || isset( $wp_travel_engine_settings['show_wishlist'] ) && '1' == $wp_travel_engine_settings['show_wishlist'];
$show_map_on_card = ! isset( $wp_travel_engine_settings['show_map_on_card'] ) || isset( $wp_travel_engine_settings['show_map_on_card'] ) && '1' == $wp_travel_engine_settings['show_map_on_card'];
$show_difficulty_taxonomy = ! isset( $wp_travel_engine_settings['show_difficulty_tax'] ) || isset( $wp_travel_engine_settings['show_difficulty_tax'] ) && '1' == $wp_travel_engine_settings['show_difficulty_tax'];
$show_trip_tags = ! isset( $wp_travel_engine_settings['show_trips_tag'] ) || isset( $wp_travel_engine_settings['show_trips_tag'] ) && '1' == $wp_travel_engine_settings['show_trips_tag'];
$show_date_layout = ! isset( $wp_travel_engine_settings['show_date_layout'] ) || isset( $wp_travel_engine_settings['show_date_layout'] ) && '1' == $wp_travel_engine_settings['show_date_layout'];
$show_available_months = ! isset( $wp_travel_engine_settings['show_available_months'] ) || isset( $wp_travel_engine_settings['show_available_months'] ) && '1' == $wp_travel_engine_settings['show_available_months'];
$show_available_dates = ! isset( $wp_travel_engine_settings['show_available_dates'] ) || isset( $wp_travel_engine_settings['show_available_dates'] ) && '1' == $wp_travel_engine_settings['show_available_dates'];
$show_featured_tag = ! isset( $wp_travel_engine_settings['show_featured_tag'] ) || isset( $wp_travel_engine_settings['show_featured_tag'] ) && '1' == $wp_travel_engine_settings['show_featured_tag'];
$new_slider_layout = ! isset( $wp_travel_engine_settings['display_slider_layout'] ) || isset( $wp_travel_engine_settings['display_slider_layout'] ) && '1' == $wp_travel_engine_settings['display_slider_layout'];
$difficulty_tax_url = $site_url .'/wp-admin/edit-tags.php?taxonomy=difficulty&post_type=trip';
$tag_tax_url = $site_url .'/wp-admin/edit-tags.php?taxonomy=trip_tag&post_type=trip';
?>
<div class="wpte-field">
    <div class="wpte-info-block">
        <?php echo sprintf( 'This section includes all the display settings for Trip Card/Listing page. Click <a href="%s" target="_blank">here</a> to see the page.',esc_url( $trip_page_url ), 'wp-travel-engine' );?>
    </div>
</div>

<div class="wpte-field wpte-checkbox advance-checkbox">
    <label class="wpte-field-label" data-wte-update="wte_new_5.5.7" for="wp_travel_engine_settings[display_new_trip_listing]"><?php esc_html_e( 'Display New Trip Layout', 'wp-travel-engine' ); ?></label>
    <div class="wpte-checkbox-wrap">
        <input type="hidden" name="wp_travel_engine_settings[display_new_trip_listing]" value="no" />
        <input type="checkbox" id="wp_travel_engine_settings[display_new_trip_listing]"
            name="wp_travel_engine_settings[display_new_trip_listing]"
            value="yes"
            data-onchange
            data-onchange-toggle-target="[data-display-new-trip-listing]"
            data-onchange-toggle-off-value="no"
            <?php checked( $new_trip_card, true ); ?> />
        <label for="wp_travel_engine_settings[display_new_trip_listing]" class="checkbox-label"></label>
    </div>
    <span class="wpte-tooltip"><?php esc_html_e( 'Enable to display new design in trip listing page.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field-subfields<?php echo $new_trip_card ? '' : ' hidden'; ?>" data-display-new-trip-listing>
	<div class="wpte-field wpte-floated">
		<div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[display_slider_layout]"><?php esc_html_e( 'Show Slider', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[display_slider_layout]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[display_slider_layout]"
                    name="wp_travel_engine_settings[display_slider_layout]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-display_slider_layout]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $new_slider_layout, true ); ?> />
                <label for="wp_travel_engine_settings[display_slider_layout]" class="checkbox-label"></label>
            </div>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_featured_tag]"><?php esc_html_e( 'Show Featured Tag on Card', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_featured_tag]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_featured_tag]"
                    name="wp_travel_engine_settings[show_featured_tag]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_featured_tag]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_featured_tag, true ); ?> />
                <label for="wp_travel_engine_settings[show_featured_tag]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php esc_html_e( 'Enable to show featured tag on card.', 'wp-travel-engine' ); ?></span>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_wishlist]"><?php esc_html_e( 'Show Wishlist', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_wishlist]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_wishlist]"
                value="1"
                name="wp_travel_engine_settings[show_wishlist]"
                data-onchange
                data-onchange-toggle-target="[data-show_wishlist]"
                data-onchange-toggle-off-value="0"
                <?php checked( $show_wishlist, true ); ?>
                />
                <label for="wp_travel_engine_settings[show_wishlist]" class="checkbox-label"></label>
            </div>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_map_on_card]"><?php esc_html_e( 'Show Map', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_map_on_card]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_map_on_card]"
                    name="wp_travel_engine_settings[show_map_on_card]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_map_on_card]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_map_on_card, true ); ?> />
                <label for="wp_travel_engine_settings[show_map_on_card]" class="checkbox-label"></label>
            </div>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_difficulty_tax]"><?php esc_html_e( 'Show Difficulty', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_difficulty_tax]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_difficulty_tax]"
                    name="wp_travel_engine_settings[show_difficulty_tax]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_difficulty_tax]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_difficulty_taxonomy, true ); ?> />
                <label for="wp_travel_engine_settings[show_difficulty_tax]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php echo sprintf('Click <a href="%s">here</a> to add difficulty level.',$difficulty_tax_url, 'wp-travel-engine') ?></span>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_difficulty_tax]"><?php esc_html_e( 'Show Tag', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_trips_tag]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_trips_tag]"
                    name="wp_travel_engine_settings[show_trips_tag]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_trips_tag]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_trip_tags, true ); ?> />
                <label for="wp_travel_engine_settings[show_trips_tag]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php echo sprintf('Click <a href="%s">here</a> to add a tag.',$tag_tax_url, 'wp-travel-engine') ?></span>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_date_layout]"><?php esc_html_e( 'Show Next Departure Dates', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_date_layout]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_date_layout]"
                    name="wp_travel_engine_settings[show_date_layout]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_date_layout]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_date_layout, true ); ?> />
                <label for="wp_travel_engine_settings[show_date_layout]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php esc_html_e( 'Enable to show next departure dates.', 'wp-travel-engine' ); ?></span>
        </div>
    </div>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_available_months]"><?php esc_html_e( 'Show Available Months', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_available_months]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_available_months]"
                    name="wp_travel_engine_settings[show_available_months]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_available_months]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_available_months, true ); ?> />
                <label for="wp_travel_engine_settings[show_available_months]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php esc_html_e( 'Enable to show available months on card.', 'wp-travel-engine' ); ?></span>
        </div>
    </div>
    <?php if ( class_exists( 'WTE_Fixed_Starting_Dates' ) ):?>
    <div class="wpte-field wpte-floated">
        <div class="wpte-field wpte-checkbox advance-checkbox">
            <label class="wpte-field-label" data-wte-update="wte_new_5.5.0" for="wp_travel_engine_settings[show_available_dates]"><?php esc_html_e( 'Show Available Dates', 'wp-travel-engine' ); ?></label>
            <div class="wpte-checkbox-wrap">
                <input type="hidden" name="wp_travel_engine_settings[show_available_dates]" value="0" />
                <input type="checkbox" id="wp_travel_engine_settings[show_available_dates]"
                    name="wp_travel_engine_settings[show_available_dates]"
                    value="1"
                    data-onchange
                    data-onchange-toggle-target="[data-show_available_dates]"
                    data-onchange-toggle-off-value="0"
                    <?php checked( $show_available_dates, true ); ?> />
                <label for="wp_travel_engine_settings[show_available_dates]" class="checkbox-label"></label>
            </div>
            <span class="wpte-tooltip"><?php esc_html_e( 'Enable to show available dates on hover.', 'wp-travel-engine' ); ?></span>
        </div>
    </div>
    <?php endif;?>
</div>
<!-- Trip Duration -->
<?php
$settings = get_option( 'wp_travel_engine_settings', array() );

$trip_duration_type    = array(
    'both'  => 'Days and Nights',
    'days'  => 'Days',
    'nights'=> 'Nights',
);
$set_duration_type = isset( $settings['set_duration_type'] ) ? $settings['set_duration_type'] : 'days';
?>
<div class="wpte-field wpte-select wpte-floated" data-set_duration_type>
    <label for="wpte_set_duration_type" class="wpte-field-label"><?php _e( "Show Trip Duration as", 'wp-travel-engine' ); ?></label>
    <select name="wp_travel_engine_settings[set_duration_type]" id="wpte_set_duration_type" class="wpte-enhanced-select">
        <?php
        foreach ( $trip_duration_type as $key => $val ) :
            ?>
            <option <?php selected( $set_duration_type, $key ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $val ); ?></option>
        <?php
        endforeach;
        ?>
    </select>
    <span class="wpte-tooltip"><?php esc_html_e( 'Choose how the trip duration should be displayed, not applicable for hourly trips.', 'wp-travel-engine' ); ?></span>
</div>
