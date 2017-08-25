<?php

namespace ctoolkit\field;

/**
 * Class Autocomplete Control
 *
 * @class     Autocomplete
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since   1.0
 */

/**
 * Autocomplete Class
 */
class Autocomplete extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_autocomplete';

	/**
	 * @var string Placeholder field
	 */
	private $placeholder = '';

	/**
	 * @var array Setting field data
	 */
	private $data = array();

	/**
	 * @var int Min Length
	 */
	private $min_length;

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

		$this->data = isset( $args['data'] ) ? $args['data'] : array();

		$this->placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

		$this->min_length = isset( $args['min_length'] ) ? $args['min_length'] : 3;

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render control
	 * @access public
	 */
	public function render_content() {

		echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';



		$args = array(
			'type' => $this->type,
			'customize_link' => $this->get_link(),
			'data' => $this->data,
			'placeholder' => $this->placeholder,
			'min_length' => $this->min_length
		);

		if ( !empty( $this->description ) ) {
			printf( '<span class="description customize-control-description">%s</span>', $this->description );
		}

		$this->field( $args, $this->value() );
	}

	private function field( $settings, $value ) {
		$output = '';

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

		$ajax_type = 'post_type';
		$ajax_value = array( 'post' );

		$min_length = 3;

		if ( !empty( $settings['data'] ) && is_array( $settings['data'] ) ) {
			$ajax_type = key( $settings['data'] );
			if ( !empty( $settings['data'][$ajax_type] ) && is_array( $settings['data'][$ajax_type] ) ) {
				$ajax_value = $settings['data'][$ajax_type];
			}
		}

		$ajax_value = implode( ',', $ajax_value );

		if ( isset( $settings['min_length'] ) ) {
			$min_length = absint( $settings['min_length'] );
		}

		$css_class = '';

		if ( !empty( $settings['el_class'] ) ) {
			$css_class = $settings['el_class'];
		}

		$output .= sprintf( '<div class="ctoolkit-field ctoolkit-autocomplete ' . $css_class . '" data-ajax_type="' . $ajax_type . '" data-ajax_value="' . $ajax_value . '" data-min_length="' . $min_length . '">' );

		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		}

		$output .= sprintf( '<input type="hidden" class="ctoolkit_value" value="%s" %s/>', esc_attr( $value ), implode( ' ', $attrs ) );

		$placeholder = sprintf( __( 'Please enter %d or more characters', 'awesplash' ), $min_length );

		if ( isset( $settings['placeholder'] ) ) {
			$placeholder = $settings['placeholder'];
		}

		$multiple = !empty( $settings['multiple'] ) ? 'multiple' : '';

		$output .= sprintf( '<select %s placeholder="%s">', $multiple, $placeholder );

		if ( !empty( $value ) ) {

			$value = explode( ',', $value );

			foreach ( $value as $id ) {
				if ( $ajax_type == 'post_type' ) {
					$post = get_post( $id );
					$output .= sprintf( '<option value="%s" %s>%s</option>', $post->ID, selected( $post->ID, $id, false ), get_the_title( $post ) );
				} else if ( $ajax_type == 'taxonomy' ) {
					$term = get_term( $id );
					if ( $term ) {
						$output .= sprintf( '<option value="%s" %s>%s</option>', $term->term_id, selected( $term->term_id, $id, false ), $term->name );
					}
				}
			}
		}
		$output .= '</select></div>';

		echo $output;
	}

}
