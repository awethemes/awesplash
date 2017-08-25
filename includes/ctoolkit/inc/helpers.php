<?php

namespace ctoolkit;

/**
 * Helper functions
 *
 * @package   ctoolkit
 * @category  Functions
 * @author    tuanvu
 * @license   GPLv3
 * @version   1.0
 */

/**
 * Parse string like "title:cToolkit is useful|author:vutuansw" to array('title' => 'cToolkit is useful', 'author' => 'vutuansw')
 *
 * @param $value
 * @param array $default
 *
 * @since 1.0
 * @return array
 */
function parse_multi_attribute( $value, $default = array() ) {
	$result = $default;
	$params_pairs = explode( '|', $value );
	if ( !empty( $params_pairs ) ) {
		foreach ( $params_pairs as $pair ) {
			$param = preg_split( '/\:/', $pair );
			if ( !empty( $param[0] ) && isset( $param[1] ) ) {
				$result[$param[0]] = rawurldecode( $param[1] );
			}
		}
	}

	return $result;
}

/**
 * Sanitize checkbox is multiple
 * @since 1.0
 * @return array
 */
function sanitize_checkbox_multiple( $value ) {

	if ( empty( $value ) ) {
		$value = array();
	}

	if ( is_string( $value ) ) {
		$value = explode( ',', $value );
	}

	return $value;
}

/**
 * Sanitize font-weight
 * @param string $value
 * @return string
 */
function sanitize_font_weight( $value ) {

	$value = str_replace( '0light', '0', $value );
	$value = str_replace( '0italic', '0', $value );
	$value = str_replace( '0bold', '0', $value );
	//$value = str_replace( 'regular', 'normal', $value );
	
	$arr = array(
		'100',
		'200',
		'300',
		'400',
		'500',
		'600',
		'700',
		'800',
		'900',
		'initial',
		'lighter',
		'normal',
		'bolder',
		'inherit',
	);

	if ( !in_array( $value, $arr ) ) {
		return '';
	}

	return $value;
}

/**
 * Convert typography to css properties
 * @since 1.1
 * @param string $value Typography value
 * @return array Css properties
 */
function typography_to_css( $value ) {

	$css = array();

	$typo = build_typography( $value );
	
	foreach ( $typo as $key => $val ) {
		if ( !empty( $val ) ) {
			if ( $key == 'variants' ) {
				$key = 'font-weight';
				if ( is_array( $val ) ) {
					$val = $val[0];
				}

				$val = sanitize_font_weight( $val );
			}
			if ( $key != 'subsets' ) {
				$css[] = $key . ':' . $val;
			}
		}
	}

	return $css;
}

/**
 * Autocomplete ajax post type
 *
 * @since 1.0
 * @return void
 */
function autocomplete_ajax_post_type() {

	$s = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
	$post_types = !empty( $_GET['types'] ) ? explode( ',', $_GET['types'] ) : array( 'post' );

	$posts = get_posts( array(
		'posts_per_page' => 20,
		'post_type' => $post_types,
		'post_status' => 'publish',
		's' => $s
			) );

	$result = array();

	foreach ( $posts as $post ) {
		$result[] = array(
			'value' => $post->ID,
			'label' => $post->post_title,
		);
	}

	wp_send_json( $result );
}

/**
 * Autocomplete ajax taxonomy
 *
 * @since 1.0
 * @return void
 */
function autocomplete_ajax_taxonomy() {

	$s = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

	$types = !empty( $_GET['types'] ) ? explode( ',', $_GET['types'] ) : array( 'category' );

	$args['taxonomy'] = $types;
	$args['hide_empty'] = false;
	$args['name__like'] = $s;


	$terms = get_terms( $args );

	$result = array();

	foreach ( $terms as $term ) {
		$result[] = array(
			'value' => $term->term_id,
			'label' => $term->name,
		);
	}

	wp_send_json( $result );
}

add_action( 'wp_ajax_ctoolkit_autocomplete_post_type', '\ctoolkit\autocomplete_ajax_post_type' );
add_action( 'wp_ajax_ctoolkit_autocomplete_taxonomy', '\ctoolkit\autocomplete_ajax_taxonomy' );

/**
 * Build Link from string
 * 
 * @param string $value
 *
 * @since 1.0
 * @return array
 */
function build_link( $value ) {
	return parse_multi_attribute( $value, array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' ) );
}

/**
 * Print link editor template
 * Link field need a hidden textarea to work
 * 
 * @since 1.0
 * @return void
 */
function link_editor_hidden() {
	echo '<textarea id="content" class="hide hidden"></textarea>';
	require_once ABSPATH . "wp-includes/class-wp-editor.php";
	\_WP_Editors::wp_link_dialog();
}

/**
 * Get gallery from field gallery value
 * @since 1.1
 * @return array
 */
function get_gallery_image_ids( $value ) {
	if ( !empty( $value ) ) {
		$ids = array();
		$value = explode( ',', $value );
		foreach ( $value as $img ) {
			$img = explode( '|', $img );
			$ids[] = $img[0];
		}
		return $ids;
	}
	return array();
}

/**
 * Sanitize text-transform css value
 * @since 1.1
 * 
 * @return string $value
 * @return string Value sanitized
 */
function sanitize_text_transform( $value ) {

	$arr = array( 'none', 'capitalize', 'uppercase', 'lowercase', 'initial' );

	if ( !in_array( $value, $arr ) ) {
		return '';
	}

	return $value;
}

/**
 * Sanitize font variants value
 * @since 1.1
 * 
 * @return string $value
 * @return string Value sanitized
 */
function sanitize_font_variants( $value ) {
	
	$variants = Fonts::get_all_variants();

	if ( is_string( $value ) && !array_key_exists( $value, $variants ) ) {
		return '';
	}
	return $value;
}

/**
 * Sanitize font subsets value
 * @since 1.1
 * 
 * @return string $value
 * @return string Value sanitized
 */
function sanitize_font_subsets( $value ) {
	$subsets = Fonts::get_google_font_subsets();
	if ( is_string( $value ) && !array_key_exists( $value, $subsets ) ) {
		return '';
	}

	return $value;
}

/**
 * Convert typography from string to array
 * 
 * @param string $value
 *
 * @since 1.1
 * @return array Typography value
 */
function build_typography( $value ) {
	
	$subfields = array(
		'font-family' => '',
		'variants' => '',
		'subsets' => '',
		'line-height' => '',
		'font-size' => '',
		'letter-spacing' => '',
		'text-transform' => '',
		'color' => ''
	);

	if ( is_string( $value ) ) {
		$value = json_decode( urldecode( $value ), true );
	}
	
	if ( empty( $value ) ) {
		$value = $subfields;
	}

	if ( is_array( $value ) ) {
		$value = wp_parse_args( $value, $subfields );
	}

	$value['font-family'] = \sanitize_text_field( $value['font-family'] );
	$value['variants'] = sanitize_font_variants( $value['variants'] );
	$value['subsets'] = sanitize_font_subsets( $value['subsets'] );
	$value['line-height'] = \sanitize_text_field( $value['line-height'] );
	$value['font-size'] = \sanitize_text_field( $value['font-size'] );
	$value['letter-spacing'] = \sanitize_text_field( $value['letter-spacing'] );
	$value['text-transform'] = sanitize_text_transform( $value['text-transform'] );
	$value['color'] = \sanitize_hex_color( $value['color'] );

	return $value;
}

/**
 * Sanitize typography array to string field
 * 
 * @param string $value
 *
 * @since 1.1
 * @return string urlencode json_encode
 */
function sanitize_typography_field( $value ) {

	$value = build_typography( $value );
	return urlencode( json_encode( $value ) );
}
