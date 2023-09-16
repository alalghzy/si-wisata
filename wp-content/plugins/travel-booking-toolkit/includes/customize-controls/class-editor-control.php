<?php
/**
 * Editor Control
 */
class Travel_Booking_Toolkit_Customize_Editor_Control extends WP_Customize_Control {

    public $type = 'textarea';
    /**
    ** Render the content on the theme customizer page
    */
    public function render_content() { ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <?php
            $settings = array(
              'media_buttons' => true,
              'quicktags' => true
              );
            $this->filter_editor_setting_link();
            wp_editor($this->value(), $this->id, $settings );
          ?>
        </label>
    <?php
        do_action('admin_footer');
        do_action('admin_print_footer_scripts');
    }
    public function enqueue() {            
        wp_enqueue_script( 'text-editor', plugin_dir_url( __FILE__ ) . '/js/editor.js', array( 'jquery', 'customize-controls' ), false, true );      
    }

    private function filter_editor_setting_link() {
        add_filter( 'the_editor', function( $output ) { return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 ); } );
    }
}
