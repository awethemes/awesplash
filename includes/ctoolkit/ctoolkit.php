<?php

/**
 * cToolkit is an embed WordPress Customizer Toolkit 
 * 
 * @author vutuansw <vutuan.sw@gmail.com>
 * @link https://github.com/vutuansw/ctoolkit/
 * @package ctoolkit
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @version 1.1
 */

namespace ctoolkit;

class cToolkit {

	private $version;

	public function __construct() {

		$this->version = '1.1';

		$this->defined();
		$this->includes();
		$this->hook();
	}

	public function hook() {
		add_action( 'customize_register', array( $this, 'customize_fields' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Defined
	 * @since 1.0
	 */
	public function defined() {
		define( 'CTOOLKIT_VERSION', $this->version );
		define( 'CTOOLKIT_DIR', plugin_dir_path( __FILE__ ) );
		define( 'CTOOLKIT_URL', plugin_dir_url( __FILE__ ) );

		global $ctoolkit_customizer_dependency;
		$ctoolkit_customizer_dependency = array();
	}

	public function includes() {
		include CTOOLKIT_DIR . 'inc/fontawesome.php';
		include CTOOLKIT_DIR . 'inc/Fonts.php';
		include CTOOLKIT_DIR . 'inc/helpers.php';
		include CTOOLKIT_DIR . 'inc/Panel.php';
		include CTOOLKIT_DIR . 'inc/Section.php';
		include CTOOLKIT_DIR . 'inc/Field.php';
	}

	/**
	 * Register Customize fields
	 * @return void
	 */
	public function customize_fields() {

		$this->register_customize_field( 'Icon_Picker' );
		$this->register_customize_field( 'Typography' );
		$this->register_customize_field( 'Link' );
		$this->register_customize_field( 'Autocomplete' );
		$this->register_customize_field( 'Datetime' );
		$this->register_customize_field( 'Gallery' );
		$this->register_customize_field( 'Heading' );
		$this->register_customize_field( 'Multitext' );
		$this->set_control_types();
	}

	/**
	 * Set customizer control types
	 * @return void
	 */
	private function set_control_types() {

		global $ctoolkit_control_types;

		$ctoolkit_control_types = apply_filters( 'ctoolkit_control_types', array(
			'image' => 'WP_Customize_Image_Control',
			'cropped_image' => 'WP_Customize_Cropped_Image_Control',
			'upload' => 'WP_Customize_Upload_Control',
			'color' => 'WP_Customize_Color_Control',
			'ctoolkit_icon_picker' => '\ctoolkit\field\Icon_Picker',
			'ctoolkit_select' => '\ctoolkit\field\Select',
			'ctoolkit_multicheck' => '\ctoolkit\field\Multicheck',
			'ctoolkit_link' => '\ctoolkit\field\Link',
			'ctoolkit_datetime' => '\ctoolkit\field\Datetime',
			'ctoolkit_typography' => '\ctoolkit\field\Typography',
			'ctoolkit_autocomplete' => '\ctoolkit\field\Autocomplete',
			'ctoolkit_heading' => '\ctoolkit\field\Heading',
			'ctoolkit_gallery' => '\ctoolkit\field\Gallery',
			'ctoolkit_multitext' => '\ctoolkit\field\Multitext',
				) );

		// Make sure the defined classes actually exist.
		foreach ( $ctoolkit_control_types as $key => $classname ) {

			if ( !class_exists( $classname ) ) {
				unset( $ctoolkit_control_types[$key] );
			}
		}
	}

	/**
	 * Register and load customize field
	 * @return void
	 */
	private function register_customize_field( $control_class ) {

		$path = CTOOLKIT_DIR . 'inc/field/' . $control_class . '.php';

		if ( is_readable( $path ) ) {
			include $path;
			global $wp_customize;
			$class = '\ctoolkit\field\/' . $control_class;
			$wp_customize->register_control_type( str_replace( '/', '', $class ) );
		}
	}

	/**
	 * Enqueue admin scripts
	 * @since 1.0
	 * @return void
	 */
	public function admin_scripts( $hook_suffix ) {

		$min = WP_DEBUG ? '' : '.min';

		global $ctoolkit_registered_fields;

		/**
		 * Init register nav menu meta item
		 */
		if ( !empty( $ctoolkit_registered_fields ) ) {

			$ctoolkit_registered_fields = array_unique( $ctoolkit_registered_fields );
			wp_enqueue_style( 'ctoolkit-admin', CTOOLKIT_URL . 'assets/css/admin' . $min . '.css', null, CTOOLKIT_VERSION );
			wp_enqueue_style( 'font-awesome', CTOOLKIT_URL . 'assets/css/font-awesome' . $min . '.css', null, '4.7.0' );
			wp_enqueue_script( 'ctoolkit-libs', CTOOLKIT_URL . 'assets/js/libs' . $min . '.js', array( 'jquery' ), CTOOLKIT_VERSION );
			
			$localize = array();

			foreach ( $ctoolkit_registered_fields as $type ) {
				switch ( $type ) {
					case 'icon_picker':
						wp_enqueue_script( 'font-iconpicker', CTOOLKIT_URL . 'assets/vendors/fonticonpicker/js/jquery.fonticonpicker' . $min . '.js', array( 'jquery' ), CTOOLKIT_VERSION );
						wp_enqueue_style( 'font-iconpicker', CTOOLKIT_URL . 'assets/vendors/fonticonpicker/css/jquery.fonticonpicker' . $min . '.css', null, CTOOLKIT_VERSION );
						break;
					case 'link':

						$screens = apply_filters( 'ctoolkit_link_on_screens', array( 'post.php', 'post-new.php' ) );
						if ( !in_array( $hook_suffix, $screens ) ) {
							wp_enqueue_style( 'editor-buttons' );
							wp_enqueue_script( 'wplink' );

							add_action( 'in_admin_header', '\ctoolkit\link_editor_hidden' );
							add_action( 'customize_controls_print_footer_scripts', '\ctoolkit\link_editor_hidden' );
						}
						break;
					case 'select':
					case 'typography':
					case 'autocomplete':

						wp_enqueue_script( 'selectize', CTOOLKIT_URL . 'assets/vendors/selectize/selectize' . $min . '.js', array( 'jquery' ), CTOOLKIT_VERSION );
						wp_enqueue_style( 'selectize', CTOOLKIT_URL . 'assets/vendors/selectize/selectize' . $min . '.css', null, CTOOLKIT_VERSION );
						wp_enqueue_style( 'selectize-skin', CTOOLKIT_URL . 'assets/vendors/selectize/selectize.default' . $min . '.css', null, CTOOLKIT_VERSION );

						if ( $type == 'typography' ) {
							$localize['subsets'] = Fonts::get_google_font_subsets();

							$localize['variants'] = Fonts::get_all_variants();

							$localize['fonts'] = Fonts::get_all_fonts_reordered();
						}

						break;
					case 'datetime':
						wp_enqueue_script( 'datetimepicker', CTOOLKIT_URL . 'assets/vendors/datetimepicker/jquery.datetimepicker.full' . $min . '.js', array( 'jquery' ), CTOOLKIT_VERSION );
						wp_enqueue_style( 'datetimepicker', CTOOLKIT_URL . 'assets/vendors/datetimepicker/jquery.datetimepicker' . $min . '.css', null, CTOOLKIT_VERSION );
						break;
					default :
						do_action( 'ctoolkit_admin_' . $type . '_scripts' );
						break;
				}
			}

			wp_localize_script( 'ctoolkit-libs', 'ctoolkit_var', apply_filters( 'ctoolkit_localize_var', $localize ) );
		}
	}

	/**
	 * Binds the JS listener to make Customizer control
	 *
	 * @since 1.0
	 */
	public function customize_scripts() {

		$min = WP_DEBUG ? '' : '.min';

		global $ctoolkit_customizer_dependency;

		wp_enqueue_script( 'ctoolkit-customize-field', CTOOLKIT_URL . 'assets/js/customize-fields' . $min . '.js', array( 'customize-controls' ), CTOOLKIT_VERSION, true );

		if ( !empty( $ctoolkit_customizer_dependency ) ) {
			wp_localize_script( 'ctoolkit-customize-field', 'ctoolkit_customizer_dependency', $ctoolkit_customizer_dependency );
		}
	}

}

$cToolkit = new cToolkit();
