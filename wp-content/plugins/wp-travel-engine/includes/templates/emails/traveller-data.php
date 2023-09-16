<?php
/**
 * Traveller notification emails.
 */
echo '<hr/>';
?>

<table class="invoice-items" width="100%" cellpadding="0" cellspacing="0" borde>
<tr>
    <td class="title-holder" style="margin: 0;" valign="top">
        <h3 class="alignleft"><?php echo esc_html__( 'Traveller Details', 'wp-travel-engine' ); ?></h3>
    </td>
    <td></td>
</tr>
<?php
$pno = $args['numbers'];
$personal_options = $args['data'];
    for ( $i = 1; $i <= $pno; $i++ ) {
        ?>
        <tr>
            <td>
                <h3 class="alignleft"><?php printf( esc_html__( 'Traveller %1$s', 'wp-travel-engine' ), (int) $i ); ?></h3>
            </td>
        </tr>
        <?php if ( isset( $personal_options['travelers'] ) ) : ?>
            <tr>
                <td class="title-holder" style="margin: 0;" valign="top">
                    <h3 class="alignleft"><?php echo esc_html__( 'Traveller information', 'wp-travel-engine' ); ?></h3>
                </td>
                <td></td>
            </tr>
            <?php foreach ( $personal_options['travelers'] as $key => $value ) :
                if ( ! isset( $value[ $i ] ) ) :
                    continue;
                endif;

                $ti_key = 'traveller_' . $key;

                if ( 'fname' === $key ) {
                    $ti_key = 'traveller_first_name';
                } elseif ( 'lname' === $key ) {
                    $ti_key = 'traveller_last_name';
                } elseif ( 'passport' === $key ) {
                    $ti_key = 'traveller_passport_number';
                }
                $data_label = wp_travel_engine_get_traveler_info_field_label_by_name( $ti_key );
                $data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;
                if ( is_array( $data_value ) ) {
                    $data_value = implode( ',', $data_value );
                }
                if ( $data_value ) :
                ?>
                <tr>
                    <td><?php echo esc_html( $data_label ); ?></td>
                    <?php
                    if ( 'dob' === $key ) :
                        $data_value = wte_get_formated_date( $data_value );
                    endif;
                    ?>
                    <td class="alignright"><?php echo esc_html( $data_value ); ?></td>
                </tr>
                <?php endif;
            endforeach;
        endif;?>

        <?php if ( isset( $personal_options['relation'] ) ) : ?>
            <tr>
                <td class="title-holder" style="margin: 0;" valign="top">
                    <h3 class="alignleft"><?php echo esc_html__( 'Emergency Contact', 'wp-travel-engine' ); ?></h3>
                </td>
            </tr>
            <?php foreach ( $personal_options['relation'] as $key => $value ) :
                if ( ! isset( $value[ $i ] ) ) :
                    continue;
                endif;

                $ti_key = 'traveller_emergency_' . $key;

                if ( 'fname' === $key ) {
                    $ti_key = 'traveller_emergency_first_name';
                } elseif ( 'lname' === $key ) {
                    $ti_key = 'traveller_emergency_last_name';
                } elseif ( 'passport' === $key ) {
                    $ti_key = 'traveller_emergency_passport_number';
                }
                $data_label = wp_travel_engine_get_relationship_field_label_by_name( $ti_key );
                $data_value = isset( $value[ $i ] ) && ! empty( $value[ $i ] ) ? $value[ $i ] : false;

                if ( is_array( $data_value ) ) {
                    $data_value = implode( ',', $data_value );
                }
                if ( $data_value ) :
                ?>
                <tr>
                    <td><?php echo esc_html( $data_label ); ?></td>
                    <?php
                    if ( 'dob' === $key ) :
                        $data_value = wte_get_formated_date( $data_value );
                    endif;
                    ?>
                    <td class="alignright"><?php echo esc_html( $data_value ); ?></td>
                </tr>
                <?php endif;
            endforeach;
        endif;
        ?>
        <?php
    }
    ?>
</table>    
<?php
echo '<hr/>';

