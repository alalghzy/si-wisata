<?php
/**
 * Currency Converter
 */
$fields = \Wp_Travel_Engine_Settings::get_currency_fields();

\wte_admin_form_fields( $fields )->render();
