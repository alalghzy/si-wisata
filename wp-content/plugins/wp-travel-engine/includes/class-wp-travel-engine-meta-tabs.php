<?php
class Wp_Travel_Engine_Meta_Tabs {

	private static $args;

	public static function create( $args ) {
		$defaults   = array(
			'container_id'       => 'ws-jquery-tab-' . rand(),
			'container_class'    => 'clearfix',
			'tab_heading_class'  => 'nav-tab-wrapper',
			'tab_heading_id'     => 'ws-jquery-tab-header-' . rand(),
			'content_wrap_class' => '',
			'content_wrap_id'    => '',
			'tabs'               => array(),
			'echo'               => true,
		);
		self::$args = wp_parse_args( $args, $defaults );

		if ( ! self::$args['echo'] ) {
			return self::generate();
		}

		echo self::generate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	private static function generate() {
		$args    = self::$args;
		$output  = '';
		$output .= sprintf( '<div id="%s"  class="%s">', $args['container_id'], $args['container_class'] );
		$output .= self::tabs();
		$output .= '</div>';
		return $output;

	}

	private static function tabs() {
		$args = self::$args;
		if ( ! empty( $args ) ) {
			$output  = '';
			$output .= sprintf( '<h2 id="%s" class="%s">', $args['tab_heading_id'], $args['tab_heading_class'] );
			foreach ( $args['tabs'] as $key => $tab ) {
				$class   = isset( $tab['class'] ) ? $tab['class'] : '';
				$class  .= ( isset( $tab['is_active'] ) && $tab['is_active'] ) ? ' nav-tab-active' : '';
				$output .= sprintf( '<a href="javascript:void(0);" class="nav-tab nb-tab-trigger %s" data-configuration="%s">%s</a>', $class, $tab['id'], $tab['title'] );
			}
			$output .= '</h2>';

			$output .= self::content();

			return $output;
		}

		return false;
	}

	private static function content() {
		$args = self::$args;
		if ( ! empty( $args ) ) {
			$output  = '';
			$output .= sprintf( '<div id="%s" class="%s">', $args['content_wrap_id'], $args['content_wrap_class'] );
			foreach ( $args['tabs'] as $key => $tab ) {
				$style   = ( ! isset( $tab['is_active'] ) || ! $tab['is_active'] ) ? ' display:none;' : '';
				$output .= sprintf( '<div class="nb-%s-configurations nb-configurations" style="%s">', $tab['id'], $style );
				$output .= $tab['content'];
				$output .= '</div>';
			}
			$output .= '</div>';

			return $output;
		}

		return false;
	}
}
