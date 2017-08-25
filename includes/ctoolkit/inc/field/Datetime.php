<?php

namespace ctoolkit\field;

/**
 * Class Datetime Control
 *
 * @class     Datetime
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since   1.0
 */

/**
 * Datetime Class
 */
class Datetime extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_datetime';

	/**
	 * @var array Datetimepicker options
	 */
	public $options = array();

	/**
	 * Constructor.
	 * Supplied `$args` override class property defaults.
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    {@see WP_Customize_Control::__construct}.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );

		if ( empty( $args['options'] ) || !is_array( $args['options'] ) ) {
			$args['options'] = array();
		}

		$this->options = $args['options'];
	}

	/**
	 * Render control
	 * @access public
	 */
	public function render_content() {

		echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

		$args = array(
			'options' => $this->options,
			'type' => $this->type,
			'customize_link' => $this->get_link(),
		);

		if ( !empty( $this->description ) ) {
			printf( '<span class="description customize-control-description">%s</span>', $this->description );
		}

		$this->field( $args, $this->value() );
	}

	public function field( $settings, $value ) {
		$options = !empty( $settings['options'] ) ? $settings['options'] : array();

		$datetimepicker = wp_parse_args( $options, array(
			'format' => '',
			'datepicker' => 1,
			'timepicker' => 1,
			'mask' => 0,
			'inline' => 0,
				) );

		/**
		 * Css Class
		 */
		$css_class = 'ctoolkit-field ctoolkit-datetime';
		if ( !empty( $settings['el_class'] ) ) {
			$css_class .= ' ' . $settings['el_class'];
		}

		/**
		 * Attributes
		 */
		$attrs = array();

		if ( !empty( $settings['name'] ) ) {
			$attrs[] = 'name="' . $settings['name'] . '"';
		}

		if ( !empty( $settings['id'] ) ) {
			$attrs[] = 'id="' . $settings['id'] . '"';
		}

		$attrs[] = 'data-type="' . $settings['type'] . '"';

		/**
		 * Support Customizer
		 */
		if ( !empty( $settings['customize_link'] ) ) {
			$attrs[] = $settings['customize_link'];
		}

		/**
		 * Input default attr
		 */
		$attrs[] = 'class="' . $css_class . '"';
		$attrs[] = 'value="' . $value . '"';

		/**
		 * Datetimepicker options
		 */
		if ( !empty( $datetimepicker ) ) {
			foreach ( $datetimepicker as $key => $val ) {
				if ( $val !== '' ) {
					$attrs[] = sprintf( 'data-%s="%s"', $key, $val );
				}
			}
		}

		printf( '<div class="%s"><input class="ctoolkit_value" type="text" %s/><i class="fa fa-clock-o" aria-hidden="true"></i></div>', $css_class, implode( ' ', $attrs ) );
	}

}
