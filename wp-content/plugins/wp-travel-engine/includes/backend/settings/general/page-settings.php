<?php
/**
 * Page Settings tab for Global Setting
 */

$fields = Wp_Travel_Engine_Settings::get_page_settings_fields();
\wte_admin_form_fields( $fields )->render();
