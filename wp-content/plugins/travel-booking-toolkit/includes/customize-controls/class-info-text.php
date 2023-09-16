<?php
/**
 * Customizer info text control
 */
class Travel_Booking_Toolkit_Info_Text extends Wp_Customize_Control {
			
    public function render_content(){ ?>
        <span class="customize-control-title">
            <?php echo esc_html( $this->label ); ?>
        </span>

        <?php if( $this->description ){ ?>
            <span class="description customize-control-description">
            <?php echo wp_kses_post($this->description); ?>
            </span>
        <?php }
    }
}
