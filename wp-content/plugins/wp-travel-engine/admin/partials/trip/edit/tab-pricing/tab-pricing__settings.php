<?php
/**
 * Tab Pricings and Dates sub-content - Settings.
 *
 * @since 5.0.0
 */
?>
<div class="wpte-form-block">
	<div class="wpte-title-wrap">
		<h2 class="wpte-title"><?php esc_html_e( 'Date Settings', 'wp-travel-engine' ); ?></h2>
	</div> <!-- .wpte-title-wrap -->
</div>
<div
	class="wpte-field wpte-text wpte-floated"
	style="margin-top: 40px;">
	<label class="wpte-field-label"><?php esc_html_e( 'Section Title', 'wp-travel-engine' ); ?></label>
	<input
		type="text"
		name="WTE_Fixed_Starting_Dates_setting[availability_title]"
		value=""
		placeholder="<?php echo esc_attr__( 'Enter Here', 'wp-travel-engine' ); ?>"
		data-parsley-id="44" />
	<span
		class="wpte-tooltip"><?php esc_html_e( 'Enter title for the Availability section.', 'wp-travel-engine' ); ?></span>
</div>
<div class="wpte-field wpte-onoff-block">
	<a
		href="Javascript:void(0);"
		class="wpte-onoff-toggle ">
		<label
			for="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
			class="wpte-field-label"><?php esc_html_e( 'Hide Fixed Trip Starts Dates section', 'wp-travel-engine' ); ?><span class="wpte-onoff-btn"></span></label>
	</a>
	<input
		type="checkbox"
		id="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
		name="WTE_Fixed_Starting_Dates_setting[departure_dates][section]"
		value="1"
		data-parsley-multiple="WTE_Fixed_Starting_Dates_settingdeparture_datessection"
		data-parsley-id="58">
	<span class="wpte-tooltip">
	<?php
	esc_html_e(
		'Check this if you want to disable fixed trip starting dates section between featured image/slider and trip
		content sections.',
		'wp-travel-engine'
	);
	?>
	</span>
</div>
<div class="wpte-shortcode">
	<span class="wpte-tooltip"><?php echo wp_kses( sprintf( __( 'To display fixed starting dates in page/post use the following %1$sShortcode.%2$s', 'wp-travel-engine' ), '<b>', '</b>' ), array( 'b' => array() ) ); ?></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input id="wpte-copy-fsd-shortcode" readonly="" type="text" value="[WTE_Fixed_Starting_Dates id='96']" data-parsley-id="60">
		<button data-copyid="wpte-copy-fsd-shortcode" class="wpte-copy-btn">Copy</button>
	</div>
</div>
<div class="wpte-shortcode">
	<span class="wpte-tooltip"><?php echo wp_kses( sprintf( __( 'To display fixed starting dates in theme/template, please use below %1$sPHP Funtion.%2$s', 'wp-travel-engine' ), '<b>', '</b>' ), array( 'b' => array() ) ); ?></span>
	<div class="wpte-field wpte-field-gray wpte-floated">
		<input id="wpte-copy-fsd-shortcode-phpfxn" readonly="" type="text" value="&lt;?php echo do_shortcode(&quot;[WTE_Fixed_Starting_Dates id=96]&quot;); ?&gt;" data-parsley-id="62">
		<button data-copyid="wpte-copy-fsd-shortcode-phpfxn" class="wpte-copy-btn"><?php esc_html_e( 'Copy', 'wp-travel-engine' ); ?></button>
	</div>
</div>
