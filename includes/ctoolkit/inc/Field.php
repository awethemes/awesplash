<?php

namespace ctoolkit;

if ( !class_exists( 'Field' ) ) {

	/**
	 * Customize_Field Class
	 */
	class Field {

		/**
		 * @access private
		 * @var array customize global
		 */
		private $wp_customize;

		/**
		 * Init
		 */
		public function __construct( $wp_customize, $args = array() ) {

			$this->wp_customize = $wp_customize;

			//Add setting
			$this->add_setting( $args );

			//Add control
			$this->add_control( $args );

			//Add partial
			$this->add_partial( $args );

			if ( isset( $args['dependency'] ) ) {

				global $ctoolkit_customizer_dependency;

				$key = key( $args['dependency'] );

				if ( !isset( $ctoolkit_customizer_dependency[$key] ) ) {
					$ctoolkit_customizer_dependency[$key] = array();

					$master = $wp_customize->get_control( $key );
					$multiple = !empty( $master->multiple ) ? '_multiple' : '';
					$ctoolkit_customizer_dependency[$key]['type'] = $master->type . $multiple;
				}

				$ctoolkit_customizer_dependency[$key]['elements'][$args['name']] = $args['dependency'][$key];
			}
		}

		private function add_control( $args ) {

			if ( isset( $args['heading'] ) ) {
				$args['label'] = $args['heading'];
				unset( $args['heading'] );
			}

			if ( isset( $args['options'] ) ) {
				$args['choices'] = $args['options'];
				unset( $args['options'] );
			}

			if ( isset( $args['desc'] ) ) {
				$args['description'] = $args['desc'];
				unset( $args['desc'] );
			}

			$defaults = array(
				'label' => '',
				'section' => '',
				'type' => '',
				'multiple' => 0,
				'priority' => '',
				'choices' => array(),
				'fields' => array(),
				'description' => ''
			);

			$control_args = wp_parse_args( $args, $defaults );

			if ( $control_args['section'] instanceof Section ) {
				$control_args['section'] = $control_args['section']->id();
			}

			if ( $control_args['type'] == 'textfield' ) {
				$control_args['type'] = 'text';
			} elseif ( $control_args['type'] == 'image_picker' ) {
				$control_args['type'] = 'image';
			} else if ( $control_args['type'] == 'color_picker' ) {
				$control_args['type'] = 'color';
			}

			$control_name = isset( $args['name'] ) ? $args['name'] : '';

			// Get the name of the class we're going to use.
			$class_name = $this->control_class_name( $control_args );
			
			//Add to global registed fields
			global $ctoolkit_registered_fields;
			$ctoolkit_registered_fields[] = $control_args['type'];

			if ( $class_name == 'Customize_Select_Control' ) {
				unset( $control_args['type'] );
			}
			
			// Add the control.
			$this->wp_customize->add_control( new $class_name( $this->wp_customize, $control_name, $control_args ) );
		}

		private function add_setting( $args ) {

			$field_type = $args['type'];

			if ( isset( $args['value'] ) ) {
				$args['default'] = $args['value'];
				unset( $args['value'] );
			}

			if ( isset( $args['setting_type'] ) ) {
				$args['type'] = $args['setting_type'];
			} else {
				unset( $args['type'] );
			}

			if ( isset( $args['id'] ) ) {
				unset( $args['id'] );
			}

			$args['name'] = isset( $args['name'] ) ? $args['name'] : '';

			/**
			 * Default settings
			 * @type Array
			 */
			$defaults = get_class_vars( 'WP_Customize_Setting' );

			$args = wp_parse_args( $args, $defaults );

			if ( $field_type == 'checkbox' && !empty( $args['multiple'] ) ) {
				if ( empty( $args['sanitize_callback'] ) ) {
					$args['sanitize_callback'] = 'ctoolkit_sanitize_checkbox_multiple';
				}

				if ( empty( $args['sanitize_js_callback'] ) ) {
					$args['sanitize_js_callback'] = 'ctoolkit_sanitize_checkbox_multiple';
				}
			}

			$this->wp_customize->add_setting( $args['name'], $args );
		}

		public function add_partial( $args ) {
			if ( !empty( $args['partial'] ) ) {
				$this->wp_customize->selective_refresh->add_partial( $args['name'], $args['partial'] );
			}
		}

		private function control_class_name( $args ) {

			$class_name = 'WP_Customize_Control';

			$type = $args['type'];

			if ( $type == 'checkbox' && absint( $args['multiple'] ) === 1 ) {
				$type = 'ctoolkit_multicheck';
			} else {
				if ( !in_array( $args['type'], array( 'image', 'cropped_image', 'upload', 'color' ) ) ) {
					$type = 'ctoolkit_' . $type;
				}
			}

			global $ctoolkit_control_types;
			
			if ( !empty( $ctoolkit_control_types ) && array_key_exists( $type, $ctoolkit_control_types ) ) {
				$class_name = $ctoolkit_control_types[$type];
			}

			return $class_name;
		}

	}

}
