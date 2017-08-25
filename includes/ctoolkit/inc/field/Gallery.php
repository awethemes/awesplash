<?php

namespace ctoolkit\field;

/**
 * Class Gallery Control
 *
 * @class     Gallery
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since 1.1
 */

/**
 * Gallery Class
 */
class Gallery extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_gallery';

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

	private function field( $settings, $value ) {

		$attrs = array();

		if ( !empty( $settings['name'] ) ) {
			$attrs[] = 'name="' . $settings['name'] . '"';
		}

		if ( !empty( $settings['id'] ) ) {
			$attrs[] = 'id="' . $settings['id'] . '"';
		}

		/**
		 * Support Customizer
		 */
		if ( !empty( $settings['customize_link'] ) ) {
			$attrs[] = $settings['customize_link'];
		}

		$attrs[] = 'data-type="' . $settings['type'] . '"';

		$multiple = isset( $settings['multiple'] ) ? absint( $settings['multiple'] ) : 1;

		$uniqid = uniqid();
		?>
		<div class="ctoolkit-field ctoolkit-image_picker" data-multiple="<?php echo esc_attr( $multiple ) ?>" id="ctoolkit-image-<?php echo esc_attr( $uniqid ) ?>">
			<?php
			printf( '<input type="hidden" class="attach_images ctoolkit_value" value="%s" %s/>', $value, implode( ' ', $attrs ) );

			$value = explode( ',', trim( $value ) );
			?>
			<div class="attached_images">

				<ul class="image_list">
					<?php
					if ( !empty( $value[0] ) && sizeof( $value ) > 0 ) {
						foreach ( $value as $str ) {
							$arr = explode( '|', $str );
							if ( !empty( $arr[0] ) && sizeof( $arr ) > 0 ) {
								$id = $arr[0];
								?>
								<li class="added" data-id="<?php echo esc_attr( $id ) ?>">
									<div class="inner">
										<?php echo wp_get_attachment_image( $id, 'thumbnail' ) ?>
									</div>
									<a href="#" class="remove" title="<?php echo esc_attr__( 'Remove', 'awesplash' ) ?>"></a>
								</li>
								<?php
							}
						}
					}
					?>

				</ul>

				<a class="add_images" href="#" title="<?php echo esc_attr__( 'Add images', 'awesplash' ) ?>"><?php echo esc_attr__( 'Add images', 'awesplash' ) ?></a>

			</div>
		</div>
		<?php
	}

}
