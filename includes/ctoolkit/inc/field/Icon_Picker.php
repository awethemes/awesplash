<?php

namespace ctoolkit\field;

/**
 * Class Icon Picker Control
 *
 * @class     Icon_Picker
 * @package   ctoolkit/field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since   1.0
 */
if ( class_exists( 'WP_Customize_Control' ) ):

	/**
	 * Icon_Picker Class
	 */
	class Icon_Picker extends \WP_Customize_Control {

		public $type = 'ctoolkit_icon_picker';

		/**
		 * Render control
		 * @access public
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

			$args = array(
				'type' => $this->type,
				'customize_link' => $this->get_link(),
			);

			if ( !empty( $this->description ) ) {
				printf( '<span class="description customize-control-description">%s</span>', $this->description );
			}

			$this->field( $args, $this->value() );
		}

		/**
		 * Field Icon Picker.
		 *
		 * @param $settings
		 * @param string $value
		 *
		 * @since 1.0
		 * @return string - html string.
		 */
		private function field( $settings, $value = '' ) {

			$attrs = array();

			$css_class = 'ctoolkit-field ctoolkit-icon_picker';

			if ( !empty( $settings['name'] ) ) {
				$attrs[] = 'name="' . $settings['name'] . '"';
			}

			if ( !empty( $settings['id'] ) ) {
				$attrs[] = 'id="' . $settings['id'] . '"';
			}

			if ( !empty( $settings['el_class'] ) ) {
				$css_class .= ' ' . $settings['el_class'];
			}

			$attrs[] = 'data-type="' . $settings['type'] . '"';

			/**
			 * Support Customizer
			 */
			if ( !empty( $settings['customize_link'] ) ) {
				$attrs[] = $settings['customize_link'];
			}

			$source_font = isset( $settings['font'] ) ? $settings['font'] : 'fontawesome';

			if ( !has_filter( 'ctoolkit_source_' . $source_font ) ) {
				$source_font = 'fontawesome';
			}

			$arr = apply_filters( 'ctoolkit_source_' . $source_font, array() );
			?>
			<div class="<?php echo esc_attr( $css_class ) ?>">
				<select class="ctoolkit_value" <?php echo implode( ' ', $attrs ) ?>>
					<?php
					if ( !empty( $arr ) ) {
						echo '<option value="">' . esc_attr__( 'No icon', 'awesplash' ) . '</option>';
						foreach ( $arr as $group => $icons ) {

							if ( !is_array( $icons ) || !is_array( current( $icons ) ) ) {
								$class_key = key( $icons );
								echo '<option value="' . esc_attr( $class_key ) . '" ' . ( strcmp( $class_key, $value ) === 0 ? 'selected' : '' ) . '>' . esc_html( current( $icons ) ) . '</option>' . "\n";
							} else {

								echo '<optgroup label="' . esc_attr( $group ) . '">' . "\n";
								foreach ( $icons as $key => $label ) {
									$class_key = key( $label );
									echo'<option value="' . esc_attr( $class_key ) . '" ' . ( strcmp( $class_key, $value ) === 0 ? 'selected' : '' ) . '>' . esc_html( current( $label ) ) . '</option>' . "\n";
								}
								echo'</optgroup>' . "\n";
							}
						}
					}
					?>
				</select>
			</div>
			<?php
		}

	}

	
endif;