<?php

/**
 * Add Customizer settings for this plugin
 *
 * @package AweSplash
 * @since 1.0
 */

/**
 * Register customizer
 */
function awesplash_customizer( $wp_customize ) {

	$panel = new ctoolkit\Panel( $wp_customize, array(
		'id' => 'awesplash_panel',
		'heading' => 'AweSplash Settings',
		'description' => ''
			) );

	/**
	 * Generate
	 */
	$panel->add_section( array(
		'id' => 'awesplash_general_settings',
		'heading' => esc_attr__( 'General', 'awesplash' ),
		'fields' => array(
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Enable AweSplash page', 'awesplash' ),
				'name' => 'awesplash_enable',
				'value' => 0,
				'sanitize_callback' => 'absint',
				'transport' => 'postMessage',
				'desc' => esc_html__( 'Checked to turn on/off splash mode.', 'awesplash' )
			),
			array(
				'type' => 'radio',
				'heading' => esc_html__( 'Display options', 'awesplash' ),
				'name' => 'awesplash_display_type',
				'value' => '',
				'options' => array(
					'' => esc_attr( 'Show on front page', 'awesplash' ),
					'all' => esc_attr( 'Show on all page', 'awesplash' ),
				),
				'sanitize_callback' => 'sanitize_text_field',
				'transport' => 'postMessage',
			),
			array(
				'type' => 'text',
				'heading' => esc_html__( 'Set expire days', 'awesplash' ),
				'name' => 'awesplash_expire_days',
				'value' => 30,
				'desc' => esc_html__( 'Days until it shown again. If 0 is selected, Splash Page will be displayed once per session.', 'awesplash' ),
				'sanitize_callback' => 'absint',
				'transport' => 'postMessage',
			),
			//Opt in
			array(
				'name' => 'awesplash_opt_heading',
				'type' => 'heading',
				'heading' => esc_html__( 'Opt-In validation', 'awesplash' ),
			),
			array(
				'name' => 'awesplash_opt_enable',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Enable Opt-In', 'awesplash' ),
				'value' => 1,
				'sanitize_callback' => 'absint',
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.title__opt-in',
					'container_inclusive' => true,
					'render_callback' => 'awesplash_template_opt'
				)
			),
			array(
				'name' => 'awesplash_opt_text',
				'type' => 'textfield',
				'heading' => esc_html__( 'Opt-In text', 'awesplash' ),
				'value' => esc_html__( 'I agree with the terms and conditions.', 'awesplash' ),
				'sanitize_callback' => 'sanitize_text_field',
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.title__opt-in',
					'container_inclusive' => false,
					'render_callback' => 'awesplash_template_opt'
				)
			),
			array(
				'name' => 'awesplash_opt_warning',
				'type' => 'textarea',
				'heading' => esc_html__( 'Opt-In warning', 'awesplash' ),
				'value' => esc_html__( 'You aren\'t agree with conditions', 'awesplash' ),
				'sanitize_callback' => 'esc_textarea',
				'partial' => array(
					'selector' => '.title__opt-in',
					'container_inclusive' => false,
					'render_callback' => 'awesplash_template_opt'
				)
			),
			//Age validaiton
			array(
				'name' => 'awesplash_age_heading',
				'type' => 'heading',
				'heading' => esc_html__( 'Age validation', 'awesplash' ),
			),
			array(
				'name' => 'awesplash_age_enable',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Enable age validation', 'awesplash' ),
				'value' => 0,
				'sanitize_callback' => 'absint',
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.title__age',
					'container_inclusive' => true,
					'render_callback' => 'awesplash_template_age'
				)
			),
			array(
				'name' => 'awesplash_age_text',
				'type' => 'textfield',
				'heading' => esc_html__( 'Opt-In text', 'awesplash' ),
				'value' => esc_html__( 'Enter your Age', 'awesplash' ),
				'sanitize_callback' => 'sanitize_text_field',
			),
			array(
				'name' => 'awesplash_age_min',
				'type' => 'textfield',
				'heading' => esc_html__( 'Minimun Age Required', 'awesplash' ),
				'value' => 18,
				'sanitize_callback' => 'absint'
			),
			array(
				'name' => 'awesplash_age_warning',
				'type' => 'textarea',
				'heading' => esc_html__( 'Awe warning', 'awesplash' ),
				'value' => esc_html__( 'Sorry, you may not view this site.', 'awesplash' ),
				'sanitize_callback' => 'esc_textarea',
			),
		)
	) );

	/**
	 * Heading
	 */
	$panel->add_section( array(
		'id' => 'awesplash_heading_settings',
		'heading' => esc_html__( 'Heading', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_heading_text',
				'type' => 'text',
				'heading' => esc_html__( 'Heading text', 'awesplash' ),
				'value' => esc_html__( 'We are', 'awesplash' ),
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.js-customizer-heading',
					'render_callback' => 'awesplash_template_heading',
					'container_inclusive' => false
				),
				'sanitize_callback' => 'sanitize_text_field'
			),
			array(
				'name' => 'awesplash_heading_style',
				'type' => 'select',
				'heading' => esc_html__( 'Effect style', 'awesplash' ),
				'options' => array(
					'' => esc_attr__( 'Standard', 'awesplash' ),
					'clip' => esc_attr__( 'Type Letters', 'awesplash' ),
					'zoom' => esc_attr__( 'Zoom Out', 'awesplash' ),
					'rotate' => esc_attr__( 'Push Out', 'awesplash' )
				),
				'value' => 'clip',
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.js-customizer-heading',
					'render_callback' => 'awesplash_template_heading',
					'container_inclusive' => true
				),
				'sanitize_callback' => 'awesplash_sanitize_heading_effect',
				'desc' => esc_html__( 'Effect for dynamic text', 'awesplash' )
			),
			array(
				'name' => 'awesplash_heading_text_list',
				'type' => 'multitext',
				'heading' => esc_html__( 'Dynamic text', 'awesplash' ),
				'value' => array( esc_attr__( 'Awethemes', 'awesplash' ), esc_attr__( 'Creative', 'awesplash' ) ),
				'dependency' => array(
					'awesplash_heading_style' => array( 'values' => array( 'clip', 'zoom', 'rotate' ) ),
				),
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.js-customizer-heading',
					'render_callback' => 'awesplash_template_heading',
					'container_inclusive' => false
				),
			),
			array(
				'name' => 'awesplash_heading_typo_enable',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use customize style', 'awesplash' ),
				'value' => 0,
				'sanitize_callback' => 'absint',
			),
			array(
				'name' => 'awesplash_heading_typo',
				'type' => 'typography',
				'heading' => esc_html__( 'Custom style', 'awesplash' ),
				'value' => array(
					'font-family' => '',
					'variants' => '',
					'subsets' => array( 'latin-ext' ),
					'font-size' => '',
					'line-height' => '',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'setting' => array(
					'variant_multiple' => false,
					'subset_multiple' => false
				),
				'dependency' => array(
					'awesplash_heading_typo_enable' => array( 'values' => 1 )
				),
				//'transport' => 'postMessage',
				'sanitize_callback' => 'ctoolkit\sanitize_typography_field'
			),
			array(
				'name' => 'awesplash_heading_color',
				'type' => 'color',
				'heading' => esc_html__( 'Color', 'awesplash' ),
				'value' => '#ffffff',
				'dependency' => array(
					'awesplash_heading_typo_enable' => array( 'values' => 1 )
				),
				'transport' => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
		)
	) );



	/**
	 * Content
	 */
	$panel->add_section( array(
		'id' => 'awesplash_content_settings',
		'heading' => esc_html__( 'Content', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_content',
				'type' => 'textarea',
				'heading' => esc_html__( 'Paragraph', 'awesplash' ),
				'value' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ultricies augue vitae lobortis.', 'awesplash' ),
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.title__description',
					'render_callback' => 'awesplash_template_content',
					'container_inclusive' => true
				),
				'sanitize_callback' => 'esc_textarea',
			),
			array(
				'name' => 'awesplash_content_typo_enable',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use customize style', 'awesplash' ),
				'value' => 0,
				'sanitize_callback' => 'absint',
			),
			array(
				'name' => 'awesplash_content_typo',
				'type' => 'typography',
				'heading' => esc_html__( 'Custom style', 'awesplash' ),
				'value' => array(
					'font-family' => '',
					'variants' => '',
					'subsets' => array( 'latin-ext' ),
					'font-size' => '',
					'line-height' => '',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'setting' => array(
					'variant_multiple' => false,
					'subset_multiple' => false
				),
				'dependency' => array(
					'awesplash_content_typo_enable' => array( 'values' => 1 )
				),
				//'transport' => 'postMessage',
				'sanitize_callback' => 'ctoolkit\sanitize_typography_field'
			),
			array(
				'name' => 'awesplash_content_color',
				'type' => 'color',
				'heading' => esc_html__( 'Color', 'awesplash' ),
				'value' => '#ffffff',
				'dependency' => array(
					'awesplash_content_typo_enable' => array( 'values' => 1 )
				),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'postMessage'
			),
		)
	) );


	/**
	 * Button
	 */
	$panel->add_section( array(
		'id' => 'awesplash_button_settings',
		'heading' => esc_html__( 'Button', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_button_text',
				'type' => 'text',
				'heading' => esc_html__( 'Button text', 'awesplash' ),
				'value' => esc_html__( 'Enter website', 'awesplash' ),
				'transport' => 'postMessage',
				'partial' => array(
					'selector' => '.title__action',
					'container_inclusive' => true,
					'render_callback' => 'awesplash_template_button'
				)
			),
			array(
				'name' => 'awesplash_button_typo_enable',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use customize style', 'awesplash' ),
				'value' => 0,
				'sanitize_callback' => 'absint',
			),
			array(
				'name' => 'awesplash_button_typo',
				'type' => 'typography',
				'heading' => esc_html__( 'Custom style', 'awesplash' ),
				'value' => array(
					'font-family' => '',
					'variants' => '',
					'subsets' => array( 'latin-ext' ),
					'font-size' => '',
					'line-height' => '',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'setting' => array(
					'variant_multiple' => false,
					'subset_multiple' => false
				),
				'dependency' => array(
					'awesplash_button_typo_enable' => array( 'values' => 1 )
				),
				//'transport' => 'postMessage',
				'sanitize_callback' => 'ctoolkit\sanitize_typography_field'
			),
			array(
				'name' => 'awesplash_button_color',
				'type' => 'color',
				'heading' => esc_html__( 'Text color', 'awesplash' ),
				'value' => '#ffffff',
				'dependency' => array(
					'awesplash_button_typo_enable' => array( 'values' => 1 )
				),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'postMessage',
			),
			array(
				'name' => 'awesplash_button_color_hover',
				'type' => 'color',
				'heading' => esc_html__( 'Text color hover', 'awesplash' ),
				'value' => '',
				'dependency' => array(
					'awesplash_button_typo_enable' => array( 'values' => 1 )
				),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'postMessage',
			),
			array(
				'name' => 'awesplash_button_bgcolor',
				'type' => 'color',
				'heading' => esc_html__( 'Background color', 'awesplash' ),
				'value' => '#FD3260',
				'dependency' => array(
					'awesplash_button_typo_enable' => array( 'values' => 1 )
				),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'postMessage',
			),
			array(
				'name' => 'awesplash_button_bgcolor_hover',
				'type' => 'color',
				'heading' => esc_html__( 'Background color hover', 'awesplash' ),
				'value' => '#ecbe00',
				'dependency' => array(
					'awesplash_button_typo_enable' => array( 'values' => 1 )
				),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'postMessage',
			),
		)
	) );


	/**
	 * Background
	 */
	$panel->add_section( array(
		'id' => 'awesplash_background_settings',
		'heading' => esc_html__( 'Background', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_background_type',
				'type' => 'select',
				'heading' => esc_html__( 'Background type', 'awesplash' ),
				'value' => 'eric',
				'options' => array(
					'color' => esc_attr__( 'Solid color', 'awesplash' ),
					'image' => esc_attr__( 'Backgound image', 'awesplash' ),
					'slider' => esc_attr__( 'Backgound slider images', 'awesplash' ),
					'video' => esc_attr__( 'Backgound video', 'awesplash' )
				),
				'value' => 'color',
				'sanitize_callback' => 'awesplash_sanitize_background_type',
			),
			array(
				'name' => 'awesplash_background_color',
				'type' => 'color_picker',
				'heading' => esc_html__( 'Solid color', 'awesplash' ),
				'value' => '#e94a4a',
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'color' )
				),
				'transport' => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			array(
				'name' => 'awesplash_background_image',
				'type' => 'image',
				'heading' => esc_html__( 'Background image', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'image' )
				),
				'sanitize_callback' => 'esc_url',
			),
			array(
				'name' => 'awesplash_background_slider',
				'type' => 'gallery',
				'heading' => esc_html__( 'Background slider', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'slider' )
				),
				'desc' => esc_html__( 'Add images to display background slider.', 'awesplash' )
			),
			array(
				'name' => 'awesplash_background_slider_effect',
				'type' => 'select',
				'options' => array(
					'slide' => esc_attr__( 'Slide', 'awesplash' ),
					'fade' => esc_attr__( 'Fade', 'awesplash' )
				),
				'value' => 'slide',
				'heading' => esc_html__( 'Slider effect', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'slider' )
				),
				'sanitize_callback' => 'awesplash_sanitize_background_slider_effect'
			),
			array(
				'name' => 'awesplash_background_video',
				'type' => 'text',
				'heading' => esc_html__( 'Video url', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'video' )
				),
				'desc' => esc_html__( 'Enter an youtube link or a Vimeo link.', 'awesplash' ),
				'sanitize_callback' => 'esc_url',
			),
			array(
				'name' => 'awesplash_background_video_heading',
				'type' => 'heading',
				'heading' => esc_html__( 'Video options', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'video' )
				)
			),
			array(
				'name' => 'awesplash_background_video_sound',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Mute sound', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'video' )
				),
				'value' => 1,
				'sanitize_callback' => 'absint',
			),
			array(
				'name' => 'awesplash_background_video_autoplay',
				'type' => 'checkbox',
				'heading' => esc_html__( 'Auto play', 'awesplash' ),
				'dependency' => array(
					'awesplash_background_type' => array( 'values' => 'video' )
				),
				'value' => 1,
				'sanitize_callback' => 'absint',
			),
		)
	) );


	/**
	 * Custom JS
	 */
	$panel->add_section( array(
		'id' => 'awesplash_custom_js_settings',
		'heading' => esc_html__( 'Additional JS', 'awesplash' ),
		'description_hidden' => true,
		'priority' => 160,
		'description' => esc_html__( 'Allows you to write Javascript code to footer of your website. Separate Javascript is saved for each of your themes. In the editing area the Tab key enters a tab character. To move below this area by pressing Tab, press the Esc key followed by the Tab key.', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_custom_js',
				'type' => 'textarea',
				'value' => sprintf( "/*\n%s\n*/", esc_html( __( "You can add your own Javascript code here.\n\nClick the help icon above to learn more.", 'awesplash' ) ) ),
				'heading' => '',
				'transport' => 'postMessage',
				'input_attrs' => array(
					'class' => 'custom_code', // Ensures contents displayed as LTR instead of RTL.
				),
	) ) ) );


	/**
	 * Custom Css
	 */
	$panel->add_section( array(
		'id' => 'awesplash_custom_css_settings',
		'heading' => esc_html__( 'Additional CSS', 'awesplash' ),
		'description_hidden' => true,
		'priority' => 161,
		'description' => esc_html__( 'CSS allows you to customize the appearance and layout of your site with code. Separate CSS is saved for each of your themes. In the editing area the Tab key enters a tab character. To move below this area by pressing Tab, press the Esc key followed by the Tab key.', 'awesplash' ),
		'fields' => array(
			array(
				'name' => 'awesplash_custom_css',
				'type' => 'textarea',
				'value' => sprintf( "/*\n%s\n*/", esc_html( __( "You can add your own Css code here.\n\nClick the help icon above to learn more.", 'awesplash' ) ) ),
				'heading' => '',
				'transport' => 'postMessage',
				'input_attrs' => array(
					'class' => 'custom_code', // Ensures contents displayed as LTR instead of RTL.
				),
	) ) ) );
}

/**
 * Hook to Customize Register
 */
add_action( 'customize_register', 'awesplash_customizer', 11 );
