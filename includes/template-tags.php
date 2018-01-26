<?php
/**
 * Custom template tags for this plugin
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AweSplash
 * @since 1.0.0
 */
/**
 * Awesplash page template
 */
if ( !function_exists( 'awesplash_template_heading_text_list' ) ):

	/**
	 * Template heading text list
	 * @since 1.0.0
	 */
	function awesplash_template_heading_text_list( $value, $style ) {
		if ( !empty( $value ) ) {
			$list = $value;

			if ( is_string( $value ) ) {

				$list = json_decode( urldecode( $value ), true );
			}

			if ( is_array( $list ) ) {
				$textcenter = $style == 'zoom' ? 'text-center' : '';
				echo '<strong class="hero-section__words ' . esc_attr( $textcenter ) . '">';
				foreach ( $list as $index => $title ) {
					$is_visible = $index == 0 ? 'is-visible' : '';
					printf( '<span class="title__effect %s">%s</span>', $is_visible, $title );
				}
				echo '</strong>';
			}
		}
	}

endif;

if ( !function_exists( 'awesplash_template_heading' ) ):

	/**
	 * Template heading
	 * @since 1.0.0
	 */
	function awesplash_template_heading() {
		$style = awesplash_sanitize_heading_effect( get_theme_mod( 'awesplash_heading_style' ) );
		?>
		<div class="js-customizer-heading">
			<h2 class="title__heading <?php echo esc_attr( $style ) ?>">
				<span><?php echo esc_html( get_theme_mod( 'awesplash_heading_text', __( 'We are', 'awesplash' ) ) ) ?></span>
				<?php
				if ( $style != '' && $dynamic_text = get_theme_mod( 'awesplash_heading_text_list', array( esc_attr__( 'Awethemes', 'awesplash' ), esc_attr__( 'Creative', 'awesplash' ) ) ) ) {
					awesplash_template_heading_text_list( $dynamic_text, $style );
				}
				?>
			</h2>
		</div>
		<?php
	}

endif;


if ( !function_exists( 'awesplash_template_background' ) ):

	/**
	 * Template background
	 * @since 1.0.0
	 */
	function awesplash_template_background() {

		$type = awesplash_sanitize_background_type( get_theme_mod( 'awesplash_background_type', 'color' ) );

		if ( $type == 'slider' ) {
			$slider = get_theme_mod( 'awesplash_background_slider' );

			$slider = ctoolkit\get_gallery_image_ids( $slider );

			$effect = awesplash_sanitize_background_slider_effect( get_theme_mod( 'awesplash_background_slider_effect', 'slide' ) );

			$fade = 'false';

			if ( $effect == 'fade' ) {
				$fade = 'true';
			}

			echo '<div class="hero-section__option" data-init="slick" data-arrows="false" data-dots="true" data-fade="' . esc_attr( $fade ) . '" data-autoplay="5000" data-speed="1200">';
			if ( !empty( $slider ) ) {

				foreach ( $slider as $attachment_id ) {
					echo wp_get_attachment_image( $attachment_id, 'full' );
				}
			}
			echo '</div>';
		} else if ( $type == 'image' ) {
			echo '<div class="hero-section__option" style="background-image:url(' . esc_url( get_theme_mod( 'awesplash_background_image' ) ) . ')"></div>';
		} else if ( $type == 'video' ) {
			$video = trim( get_theme_mod( 'awesplash_background_video' ) );

			if ( !empty( $video ) ) {
				$domain = parse_url( $video, PHP_URL_HOST );
				preg_match( '/[^.]+\.[^.]+$/', $domain, $matches );
				$domain = $matches[0];

				if ( $domain == 'vimeo.com' || $domain == 'youtu.be' || $domain == 'youtube.com' ) {

					$mute = get_theme_mod( 'awesplash_background_video_sound', 1 ) ? 'true' : 'false';
					$autoplay = get_theme_mod( 'awesplash_background_video_autoplay', 1 ) ? 'true' : 'false';
					$domain = $domain == 'vimeo.com' ? 'vimeo' : 'youtube';

					echo '<div data-video="' . esc_attr( $domain ) . '" class="player" data-property="{videoURL:\'' . esc_url( $video ) . '\',containment:\'body\',autoPlay:' . esc_attr( $autoplay ) . ', mute:' . esc_attr( $mute ) . ', startAt:0, opacity:1}">' . esc_html__( 'Video ', 'awesplash' ) . '</div>';
				}
			}
		} else {
			echo '<div class="hero-section__option"></div>';
		}
	}

endif;

if ( !function_exists( 'awesplash_template_content' ) ):

	/**
	 * Display content discription template
	 * @since 1.0.0
	 */
	function awesplash_template_content() {
		echo '<div class="title__description">';
		echo wp_kses_post( get_theme_mod( 'awesplash_content', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ultricies augue vitae lobortis.' ) );
		echo '</div>';
	}

endif;

if ( !function_exists( 'awesplash_template_button' ) ):

	/**
	 * Display button template
	 * @since 1.0.0
	 */
	function awesplash_template_button() {
		//Options btn color: .btn-success | .btn-info | .btn-warning | .btn-danger | .btn-primary
		echo '<div class="title__action"><button type="submit" class="btn btn-warning">' . esc_html( get_theme_mod( 'awesplash_button_text', __( 'Enter website', 'awesplash' ) ) ) . '</button></div>';
	}

endif;


if ( !function_exists( 'awesplash_template_opt' ) ):

	/**
	 * Display opt-in validation template
	 * @since 1.0.0
	 */
	function awesplash_template_opt() {

		echo '<div class="title__opt-in">';
		if ( esc_attr( get_theme_mod( 'awesplash_opt_enable', 1 ) ) ) {
			printf( '<input type="checkbox" id="opt-in" name="opt-in"><label for="opt-in">%s</label>', esc_html( get_theme_mod( 'awesplash_opt_text', __( 'I agree with the terms and conditions.', 'awesplash' ) ) ) );
		}
		echo '</div>';
	}

endif;


if ( !function_exists( 'awesplash_template_age' ) ):

	/**
	 * Display template Age validation template
	 * @since 1.0.0
	 */
	function awesplash_template_age() {
		$now = new DateTime();
		$month = isset( $_POST['age_month'] ) ? absint( $_POST['age_month'] ) : $now->format( 'm' );
		$day = isset( $_POST['age_day'] ) ? absint( $_POST['age_day'] ) : $now->format( 'd' );
		$year = isset( $_POST['age_year'] ) ? absint( $_POST['age_year'] ) : $now->format( 'Y' );

		echo '<div class="title__age">';
		if ( esc_attr( get_theme_mod( 'awesplash_age_enable' ), 0 ) ) {
			?>
			<h3><?php echo esc_html( get_theme_mod( 'awesplash_age_text', __( 'Enter your Age', 'awesplash' ) ) ) ?></h3>
			<label for="title__month"><?php echo esc_html__( 'Month', 'awesplash' ) ?></label><input type="number" id="title__month" name="age_month" min="1" max="12" size="2" maxlength="2" value="<?php echo esc_attr( $month ) ?>" placeholder="mm" required="required" />
			<label for="title__day"><?php echo esc_html__( 'Day', 'awesplash' ) ?></label><input type="number" id="title__day" name="age_day" min="1" max="31" size="2" maxlength="2" value="<?php echo esc_attr( $day ) ?>" placeholder="dd" required="required" />
			<label for="title__year"><?php echo esc_html__( 'Year', 'awesplash' ) ?></label><input type="number" id="title__year" name="age_year" min="1" max="9999" size="4" maxlength="4" value="<?php echo esc_attr( $year ) ?>" placeholder="yyyy" required="required" />
			<?php
		}
		echo '</div>';
	}

endif;


if ( !function_exists( 'awesplash_template_errors' ) ) {

	/**
	 * Display error on template
	 * @param bool $single Display single message or list
	 * @since 1.0.0
	 */
	function awesplash_template_errors( $single = false ) {

		if ( !empty( $_SESSION['awesplash_form_errors'] ) && is_array( $_SESSION['awesplash_form_errors'] ) ) {
			$errors = $_SESSION['awesplash_form_errors'];

			$index = 0;
			foreach ( $errors as $key => $value ) {
				$index++;

				printf( '<div class="title__%s">%s</div>', $key, $value );

				if ( $single && $index == 1 ) {
					break;
				}
			}

			unset( $_SESSION['awesplash_form_errors'] );
		}
	}

}

if ( !function_exists( 'awesplash_custom_css' ) ):

	/**
	 * Generate the CSS for the current custom color scheme.
	 * @since 1.0.0
	 * @return string Css
	 */
	function awesplash_custom_css() {


		$css = WP_DEBUG ? '' : get_transient( 'awesplash_custom_css' );
		$css = '';
		if ( empty( $css ) || is_customize_preview() ) {

			$background_color = sanitize_hex_color( get_theme_mod( 'awesplash_background_color', '' ) );
			if ( !empty( $background_color ) ) {
				/**
				 * Background Color
				 */
				$css .= '.hero-section--color .hero-section__option {background-color: ' . $background_color . ';}';
			}

			/**
			 * Heading
			 */
			if ( get_theme_mod( 'awesplash_heading_typo_enable', 0 ) ) {
				$text_color = sanitize_hex_color( get_theme_mod( 'awesplash_heading_color' ) );

				$css_typo = ctoolkit\typography_to_css( get_theme_mod( 'awesplash_heading_typo', array() ) );

				if ( !empty( $text_color ) ) {
					$css_typo[] = 'color:' . $text_color;
				}
				if ( !empty( $css_typo ) ) {
					$css .= '.title__heading{' . implode( ';', $css_typo ) . ';}';
				}
			}

			/**
			 * Content
			 */
			if ( get_theme_mod( 'awesplash_content_typo_enable', 0 ) ) {
				$text_color = sanitize_hex_color( get_theme_mod( 'awesplash_content_color', '' ) );
				$css_typo = ctoolkit\typography_to_css( get_theme_mod( 'awesplash_content_typo' ) );

				if ( !empty( $text_color ) ) {
					$css_typo[] = 'color:' . $text_color;
				}
				if ( !empty( $css_typo ) ) {
					$css .= '.title__description{' . implode( ';', $css_typo ) . ';}';
				}
			}

			/**
			 * Button
			 */
			if ( get_theme_mod( 'awesplash_button_typo_enable', 0 ) ) {

				$css_typo = ctoolkit\typography_to_css( get_theme_mod( 'awesplash_button_typo' ) );
				$text_color = sanitize_hex_color( get_theme_mod( 'awesplash_button_color' ) );
				$text_color_hover = sanitize_hex_color( get_theme_mod( 'awesplash_button_color_hover' ) );
				$bg_color = sanitize_hex_color( get_theme_mod( 'awesplash_button_bgcolor' ) );
				$bg_hovercolor = sanitize_hex_color( get_theme_mod( 'awesplash_button_bgcolor_hover' ) );

				if ( !empty( $text_color ) ) {
					$css_typo[] = 'color:' . $text_color;
				}

				if ( !empty( $bg_color ) ) {
					$css_typo[] = 'background-color:' . $bg_color;
					$css_typo[] = 'border-color:' . awesplash_color_luminance( $bg_color, -0.2 );
				}

				if ( !empty( $text_color_hover ) ) {
					$css .= '.title__action .btn:hover{color:' . $text_color_hover . '}';
				}

				if ( !empty( $bg_hovercolor ) ) {
					$css .= '.title__action .btn:hover,.title__action .btn:active,.title__action .btn:focus{background-color:' . $bg_hovercolor . ';border-color:' . awesplash_color_luminance( $bg_hovercolor, -0.2 ) . '}';
				}

				if ( !empty( $css_typo ) ) {
					$css .= '.title__action .btn{' . implode( ';', $css_typo ) . ';}';
				}
			}

			if ( !WP_DEBUG ) {
				set_transient( 'awesplash_custom_css', $css );
			}
		}

		return $css;
	}

endif;

if ( !function_exists( 'awesplash_custom_js' ) ) {

	/**
	 * Display custom Javascript
	 * @since 1.0.0
	 */
	function awesplash_custom_js() {
		if ( $custom_js = get_theme_mod( 'awesplash_custom_js', '' ) ) {
			printf( '<script type="text/javascript" id="custom-code-js">%s</script>', $custom_js );
		}
	}

}


if ( !function_exists( 'awesplash_get_section_class' ) ) {

	/**
	 * Generate section class
	 * @since 1.0.2
	 * 
	 * @return string
	 */
	function awesplash_get_section_classes() {
		
		$cssClass = array(
			'hero-section--' . esc_attr( get_theme_mod( 'awesplash_background_type', 'color' ) ),
			'clearfix'
		);
		
		if ( get_theme_mod( 'awesplash_background_overlay_disable', 0 ) == 0 ) {
			$cssClass[] = 'hero-section--overlay';
		}

		$cssClass = implode( ' ', $cssClass );

		return apply_filters( 'awesplash_get_section_classes', $cssClass );
	}

}