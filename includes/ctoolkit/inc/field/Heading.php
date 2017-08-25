<?php
namespace ctoolkit\field;

/**
 * Class Heading Control
 *
 * @class     Heading
 * @package   ctoolkit\field
 * @category  Class
 * @author    vutuansw <vutuan.sw@gmail.com>
 * @license   GPLv3
 * @since 1.1
 */

/**
 * Heading Class
 */
class Heading extends \WP_Customize_Control {

	/**
	 * @var string Field type
	 */
	public $type = 'ctoolkit_heading';

	/**
	 * @var array Heading default options
	 */
	public $default;

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
	}

	/**
	 * Render control
	 * @access public
	 */
	public function render_content() {
		/**
		 * Just show label
		 */
		echo '<span class="customize-control-title">' . esc_attr( $this->label ) . '</span>';
	}

}
