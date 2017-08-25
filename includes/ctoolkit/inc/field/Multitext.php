<?php

namespace ctoolkit\field;

/**
 * Class Multitext Control
 *
 * @class     Multitext
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since 1.1
 */

/**
 * Multitext Class
 */
class Multitext extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_multitext';

	/**
	 * Render control
	 * @access public
	 */
	public function render_content() {

		$args = array(
			'type' => $this->type,
			'customize_link' => $this->get_link(),
		);

		echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

		if ( !empty( $this->description ) ) {
			printf( '<span class="description customize-control-description">%s</span>', $this->description );
		}

		$this->field( $args, $this->value() );
	}

	public function field( $settings, $value ) {
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

		$data = $value;
		
		if ( is_string( $value ) && !empty( $value ) ) {
			$data = json_decode( urldecode( $value ) );
		}

		if ( is_array( $value ) ) {
			$value = urlencode( json_encode( $value ) );
		}
		?>
		<div class="ctoolkit-field ctoolkit-multitext">

			<?php printf( '<input type="hidden" class="ctoolkit_value" value="%s" %s/>', $value, implode( ' ', $attrs ) ); ?>
			<ul>
				<?php
				if ( count( $data ) ) {
					foreach ( $data as $val ) {
						$this->field_item( $val );
					}
				} else {
					$this->field_item( '' );
				}
				?>
			</ul>
			<div class="bottom-row">
				<a href="#" class="addnew"><?php echo esc_html__( 'Add new', 'awesplash' ) ?></a>
			</div>
		</div>
		<?php
	}

	public function field_item( $value = '' ) {
		?>
		<li class="multitext-item">
			<input type="text" class="widefat" value="<?php echo esc_attr( $value ) ?>"/>
			<a class="remove" href="#" title="<?php echo esc_attr__( 'Remove', 'awesplash' ) ?>"><?php echo esc_html__( 'Remove', 'awesplash' ) ?></a>
			<a class="short" href="#" title="<?php echo esc_attr__( 'Short', 'awesplash' ) ?>"><?php echo esc_html__( 'Short', 'awesplash' ) ?></a>
		</li>
		<?php
	}

}
