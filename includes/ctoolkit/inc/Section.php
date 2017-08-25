<?php

namespace ctoolkit;

/**
 * Section Class
 */
class Section {

	/**
	 * @access public
	 * @var array customize settings
	 */
	private $settings = array();

	/**
	 * @access private
	 * @var array customize global
	 */
	private $wp_customize;

	/**
	 * Section ID
	 * @var $id string
	 */
	private $id;

	/**
	 * Init
	 */
	public function __construct( $wp_customize, $args ) {

		if ( !empty( $args ) ) {

			$defaults = array(
				'panel' => '',
				'id' => '',
				'heading' => '',
				'description' => '',
				'priority' => 160,
				'capability' => 'edit_theme_options',
				'theme_supports' => '', // Rarely needed.
				'fields' => array()
			);


			$this->settings = wp_parse_args( $args, $defaults );

			$this->id = $this->settings['id'];

			$this->wp_customize = $wp_customize;

			$this->ouput();
		}
	}

	public function id() {
		return $this->id;
	}

	/**
	 * Section Output
	 */
	private function ouput() {

		$args = $this->settings;

		if ( isset( $args['heading'] ) ) {
			$args['title'] = $args['heading'];
			unset( $args['heading'] );
		}

		if ( $args['panel'] instanceof Panel ) {
			$args['panel'] = $args['panel']->id();
		}

		$this->wp_customize->add_section( $args['id'], $args );

		$this->add_fields( $args['fields'] );
	}

	public function add_fields( $fields = array() ) {
		foreach ( $fields as $field ) {
			$this->add_field( $field );
		}
	}

	public function add_field( $args = array() ) {
		$args['section'] = $this->id();
		new Field( $this->wp_customize, $args );
	}

}
