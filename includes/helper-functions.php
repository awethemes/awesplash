<?php

/**
 * Helper functions file
 *
 * @package AweSplash
 * @since 1.0.0
 */

/**
 * Template file
 * 
 * @since 1.0.0
 */
function awesplash_template( $slug, $data = array() ) {

	if ( is_array( $data ) ) {
		extract( $data );
	}

	include AWESPLASH_DIR . 'templates/' . $slug . '.php';
}

/**
 * Get content of template file
 * 
 * @since 1.0.0
 */
function awesplash_get_template( $slug, $data = array() ) {
	ob_start();
	awesplash_template( $slug, $data );
	return ob_get_clean();
}

/**
 * Format font
 * @param array $typo Typo formated
 * @return string
 */
function awesplash_get_format_font( $typo = array() ) {
	$str = '';

	if ( !empty( $typo['font-family'] ) ) {

		$str .= $typo['font-family'];

		if ( !empty( $typo['variants'] ) ) {
			$str .= ':' . $typo['variants'];
		}
	}

	return $str;
}

/**
 * Merge font
 * @param array $typos Key of thememod
 * @return string Font url
 */
function awesplash_get_font_url( $typos = array() ) {

	$fonts_url = '';
	$fonts = array();
	$subsets = array();

	foreach ( $typos as $theme_mod ) {

		$typo = ctoolkit\build_typography( get_theme_mod( $theme_mod ) );

		if ( $format = awesplash_get_format_font( $typo ) ) {
			$fonts[] = $format;
			if ( !empty( $typo['subsets'] ) ) {
				$subsets[] = $typo['subsets'];
			}
		}
	}
	if ( $fonts ) {

		$subsets = array_unique( $subsets );

		$query_args = array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( implode( ',', $subsets ) ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Check age validate
 * 
 * @param Datetime $date Age of user
 * @return int Min Age
 * @return bool
 */
function awesplash_age_is_validate( $date, $min_age ) {
	$now = new DateTime();
	$diff = $date->diff( $now );

	if ( $diff->y < $min_age ) {
		return false;
	} else if ( ($diff->y == $min_age && $diff->m > 0) || ($diff->y == $min_age && $diff->d > 0) ) {
		return false;
	}

	return true;
}

/**
 * 
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @link https://gist.github.com/stephenharris/5532899
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
function awesplash_color_luminance( $hex, $percent ) {
	// validate hex string

	$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
	$new_hex = '#';

	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}

	// convert to decimal and change luminosity
	for ( $i = 0; $i < 3; $i++ ) {
		$dec = hexdec( substr( $hex, $i * 2, 2 ) );
		$dec = min( max( 0, $dec + $dec * $percent ), 255 );
		$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
	}

	return $new_hex;
}

/**
 * Sanitize heading effect
 * @param string $value
 * @return string Real value
 */
function awesplash_sanitize_heading_effect( $value ) {
	$arr = array( 'clip', 'zoom', 'rotate' );
	if ( !in_array( $value, $arr ) ) {
		return 'clip';
	}

	return $value;
}

/**
 * Sanitize background type
 * 
 * @param string $value
 * @return string Background type value
 */
function awesplash_sanitize_background_type( $value ) {
	$arr = array( 'color', 'image', 'slider', 'video' );
	if ( !in_array( $value, $arr ) ) {
		return 'color';
	}

	return $value;
}

/**
 * Sanitize slider effect
 * 
 * @param string $value
 * @return string
 */
function awesplash_sanitize_background_slider_effect( $value ) {
	$arr = array( 'slide', 'fade' );
	if ( !in_array( $value, $arr ) ) {
		return 'slide';
	}

	return $value;
}

/**
 * Get current url
 * 
 * @since 1.0.2
 * 
 * @return string URL
 */
function awesplash_get_current_url() {
	global $wp;
	return home_url( $wp->request );
}
