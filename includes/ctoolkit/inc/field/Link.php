<?php

namespace ctoolkit\field;


/**
 * Class Link Control
 *
 * @class     Link
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since   1.0
 */

/**
 * Link Class
 */
class Link extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_link';

	/**
	 * Render control
	 * @access public
	 */
	public function render_content() {

		echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';

		$args = array(
			'type' => $this->type,
			'customize_link' => $this->get_link()
		);
		if ( !empty( $this->description ) ) {
			printf( '<span class="description customize-control-description">%s</span>', $this->description );
		}

		$this->field( $args, $this->value() );
	}

	private function field( $settings, $value ) {

		/**
		 * @var string Css Class
		 */
		$css_class = 'ctoolkit-field ctoolkit-link';

		if ( !empty( $settings['el_class'] ) ) {
			$css_class .= ' ' . $settings['el_class'];
		}


		/**
		 * @var array Attributes
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
		

		$link = \ctoolkit\build_link( $value );

		$json_value = htmlentities( json_encode( $link ), ENT_QUOTES, 'utf-8' );

		$input_value = htmlentities( $value, ENT_QUOTES, 'utf-8' );
		?>
		<div class="<?php echo esc_attr( $css_class ) ?>" id="ctoolkit-link-<?php echo esc_attr( uniqid() ) ?>">

			<?php printf( '<input type="hidden" class="ctoolkit_value" value="%1$s" data-json="%2$s" %3$s/>', $input_value, $json_value, implode( ' ', $attrs ) ); ?>

			<a href="#" class="button link_button"><?php echo esc_attr__( 'Select URL', 'awesplash' ) ?></a> 
			<span class="group_title">
				<span class="link_label_title link_label"><?php echo esc_attr__( 'Link Text:', 'awesplash' ) ?></span> 
				<span class="title-label"><?php echo isset( $link['title'] ) ? esc_attr( $link['title'] ) : ''; ?></span> 
			</span>
			<span class="group_url">
				<span class="link_label"><?php echo esc_attr__( 'URL:', 'awesplash' ) ?></span> 
				<span class="url-label">
					<?php
					echo isset( $link['url'] ) ? esc_url( $link['url'] ) : '';
					echo isset( $link['target'] ) ? ' ' . esc_attr( $link['target'] ) : '';
					?> 
				</span>
			</span>
		</div>
		<?php
	}

}
