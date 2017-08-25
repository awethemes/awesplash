<?php

namespace ctoolkit;

/**
 * Panel Class
 */
class Panel {

	/**
	 * @access private
	 * @var array customize global
	 */
	private $wp_customize;

	/**
	 * @access private
	 * @var string
	 */
	private $id;

	/**
	 * Init
	 */
	public function __construct( $wp_customize, $args ) {

		if ( !empty( $args ) ) {

			$this->id = $args['id'];

			$this->wp_customize = $wp_customize;

			$this->add_panel( $args );
		}
	}

	public function id() {
		return $this->id;
	}

	/**
	 * @access private
	 * Add panel to customizer
	 */
	private function add_panel( $args ) {

		$defaults = array(
			'id' => '',
			'heading' => '',
			'description' => '', // Include html tags such as <p>.
			'priority' => 160, // Mixed with top-level-section hierarchy.
		);
		
		if ( isset( $args['heading'] ) ) {
			$args['title'] = $args['heading'];
			unset( $args['heading'] );
		}
		
		$args = wp_parse_args( $args, $defaults );

		$this->wp_customize->add_panel( $args['id'], $args );
	}

	/**
	 * Add section
	 * 
	 * @param Section|array $section Section Class or array settings
	 */
	public function add_section( $args = array() ) {
		
		$args['panel'] = $this->id();
		
		new Section( $this->wp_customize, $args );
	}

	public function add_sections( $sections = array() ) {
		foreach ( $sections as $section ) {
			$this->add_section( $section );
		}
	}

}
