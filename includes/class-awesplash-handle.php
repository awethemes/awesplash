<?php

/**
 * Handle frontend forms.
 *
 * @package AweSplash
 * @since 1.0.0
 */

/**
 * Class AwesPlash_Handle
 */
class AwesPlash_Handle {

	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'form_validate' ) );
	}

	public static function form_validate() {

		if ( !empty( $_POST['awesplash_nonce'] ) ) {
			
			$error = array();

			if ( wp_verify_nonce( $_POST['awesplash_nonce'], 'awesplash' ) ) {

				if ( get_theme_mod( 'awesplash_age_enable', 0 ) ) {
					$month = isset( $_POST['age_month'] ) ? absint( $_POST['age_month'] ) : 0;
					$day = isset( $_POST['age_day'] ) ? absint( $_POST['age_day'] ) : 0;
					$year = isset( $_POST['age_year'] ) ? absint( $_POST['age_year'] ) : 0;

					$date = new DateTime();
					$date->setDate( $year, $month, $day );
					$min = absint( get_theme_mod( 'awesplash_age_min', 18 ) );

					if ( !awesplash_age_is_validate( $date, $min ) ) {
						$error['warning'] = esc_html( get_theme_mod( 'awesplash_age_warning', __( 'Sorry, you may not view this site.', 'awesplash' ) ) );
					}
				}

				if ( get_theme_mod( 'awesplash_opt_enable', 1 ) ) {
					if ( !isset( $_POST['opt-in'] ) ) {
						$error['error'] = esc_html( get_theme_mod( 'awesplash_opt_warning', __( 'You aren\'t agree with conditions.', 'awesplash' ) ) );
					}
				}
			} else {
				$error['error'] = __( 'Sorry, security key is invalid.', 'awesplash' );
			}

			$_SESSION['awesplash_form_errors'] = $error;

			if ( empty( $error ) ) {
				
				$expiration = absint( get_theme_mod( 'awesplash_expire_days', 30 ) );
				$expiration = $expiration > 0 ? time() + (DAY_IN_SECONDS * $expiration) : 0;
				setCookie( 'awesplash', 'yes', $expiration, '/' );

				$custom_link = get_theme_mod( 'awesplash_button_url' );

				if ( empty( $custom_link ) ) {
					$custom_link = awesplash_get_current_url();
				}

				wp_redirect( esc_url( $custom_link ) );
				exit;
			}
		}
	}

}

AwesPlash_Handle::init();
