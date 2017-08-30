<?php

/**
 * Plugin Name: AweSplash - Just Splash Page    
 * Plugin URI: https://wordpress.org/plugins/awesplash/    
 * Description: A splash page for your WordPress site.    
 * Version: 1.0.1    
 * Author: Awethemes    
 * Author URI: http://awethemes.com/    
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Requires at least: 4.0
 * Tested up to: 4.8
 * Text Domain: awesplash
 * Domain Path: /languages/
 *
 * @package awesplash
 */
if ( !class_exists( 'AweSplash' ) ) {

	final class AweSplash {

		private $is_acitve;

		function __construct() {

			$this->is_acitve = absint( get_theme_mod( 'awesplash_enable', 0 ) );

			$this->defined();
			$this->hook();
			$this->includes();

			do_action( 'awesplash_loaded' );
		}

		/**
		 * Allow to display splash page
		 * @return bool
		 */
		public function is_allow() {
			// && is_admin() 
			if ( !$this->is_acitve ) {
				return false;
			}

			if ( isset( $_COOKIE['awesplash'] ) && $_COOKIE['awesplash'] == 'yes' ) {
				return false;
			}

			if ( esc_attr( get_theme_mod( 'awesplash_display_type', '' ) ) == '' && !is_front_page() ) {
				return false;
			}

			return true;
		}

		/**
		 * The single instance of the class.
		 *
		 * @var awesplash
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main Awe Splash Instance.
		 *
		 * Ensures only one instance of AweSplash is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see awesplash()
		 * @return AweSplash - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Call functions to WordPress hooks
		 * @since 1.0.0
		 * @return void
		 */
		public function hook() {
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'customize_save', array( $this, 'customize_save' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
			add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_js' ) );
			add_action( 'wp_ajax_awesplash_enable', array( $this, 'enable' ) );

			if ( $this->is_acitve ) {
				add_action( 'template_include', array( $this, 'splashpage' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'wp_print_styles', array( $this, 'remove_styles' ), 100 );
				add_action( 'wp_print_scripts', array( $this, 'remove_scripts' ), 100 );
				add_action( 'wp_footer', 'awesplash_custom_js', 999 );
			}
		}

		/**
		 * Clear cache for custom resource
		 */
		public function customize_save() {
			delete_transient( 'awesplash_custom_css' );
			delete_transient( 'awesplash_custom_font_url' );
		}

		/**
		 * Ajax turn on/off splash page
		 */
		public function enable() {

			if ( !isset( $_POST['enable'] ) ) {
				wp_send_json_error( 'invalid_value' );
			}

			if ( !check_ajax_referer( 'awesplash_enable', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			$enable = absint( $_POST['enable'] );
			set_theme_mod( 'awesplash_enable', $enable );
			setcookie( 'awesplash', '', time() - 3600, '/' );
			wp_send_json_success( $enable );
		}

		/**
		 * Render page
		 * @since 1.0.0
		 */
		public function splashpage( $template ) {


			if ( !$this->is_allow() ) {
				return $template;
			}

			$no_validate = !get_theme_mod( 'awesplash_age_enable', 0 ) && !get_theme_mod( 'awesplash_opt_enable', 1 );

			if ( !isset( $_COOKIE['awesplash'] ) && $no_validate && !is_customize_preview() ) {

				$expiration = time() + (DAY_IN_SECONDS * absint( get_theme_mod( 'awesplash_expire_days', 30 ) ));
				setCookie( 'awesplash', 'viewed', $expiration, '/' );
			} else if ( isset( $_COOKIE['awesplash'] ) && $_COOKIE['awesplash'] == 'viewed' && $no_validate ) {
				$expiration = time() + (DAY_IN_SECONDS * absint( get_theme_mod( 'awesplash_expire_days', 30 ) ));
				setCookie( 'awesplash', 'yes', $expiration, '/' );
				$actual_link = (isset( $_SERVER['HTTPS'] ) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				wp_redirect( esc_url( $actual_link ) );
				exit;
			}


			$template = get_template_directory() . '/splash-page.php';

			if ( file_exists( $template ) ) {
				return $template;
			}

			return apply_filters( 'awesplash_template', AWESPLASH_DIR . 'templates/' ) . 'splash-page.php';
		}

		public function local_var() {
			return array(
				'background_color' => sanitize_hex_color( get_theme_mod( 'awesplash_background_color' ) ),
				'heading_color' => sanitize_hex_color( get_theme_mod( 'awesplash_heading_color' ) ),
				'content_color' => sanitize_hex_color( get_theme_mod( 'awesplash_content_color' ) ),
				'button_color' => sanitize_hex_color( get_theme_mod( 'awesplash_button_color' ) ),
				'button_color_hover' => sanitize_hex_color( get_theme_mod( 'awesplash_button_color_hover' ) ),
				'button_bgcolor' => sanitize_hex_color( get_theme_mod( 'awesplash_button_bgcolor' ) ),
				'button_bgcolor_hover' => sanitize_hex_color( get_theme_mod( 'awesplash_button_bgcolor_hover' ) ),
				'confirm_on' => __( 'You need to reload the page to show splash page. Press OK to reload.', 'awesplash' ),
				'confirm_off' => __( 'You need to reload the page to turn off splash page. Press OK to reload.', 'awesplash' ),
				'nonce' => wp_create_nonce( 'awesplash_enable' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			);
		}

		/**
		 * Bind JS handlers to instantly live-preview changes.
		 * @since 1.0.0
		 */
		public function customize_controls_js() {
			wp_enqueue_script( 'awesplash-customize-controls', AWESPLASH_URL . 'assets/js/customize-controls.js', array( 'customize-preview' ), AWESPLASH_VER, true );
			wp_localize_script( 'awesplash-customize-controls', 'awesplash_var', $this->local_var() );
		}

		/**
		 * Bind JS handlers to instantly live-preview changes.
		 * @since 1.0.0
		 */
		public function customize_preview_js() {
			wp_enqueue_script( 'awesplash-customize-preview', AWESPLASH_URL . 'assets/js/customize-preview.js', array( 'customize-preview' ), AWESPLASH_VER, true );
			wp_localize_script( 'awesplash-customize-preview', 'awesplash_var', $this->local_var() );
		}

		/**
		 * Register Splash page font url
		 * @since 1.0.0
		 * @return string Font url
		 */
		public function font_url() {

			$fonts_url = '';
			$font_families = array();

			$open_san = _x( 'on', 'Montserrat font', 'awesplash' );

			if ( 'off' !== $open_san ) {
				$font_families[] = 'Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i';
			}

			if ( !empty( $font_families ) ) {
				$query_args = array(
					'family' => urlencode( implode( '|', $font_families ) ),
					'subset' => urlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

				$fonts_url = apply_filters( 'awesplash_fonts_url', $fonts_url );
			}

			return esc_url_raw( $fonts_url );
		}

		/**
		 * Register custom font url
		 * @since 1.0.0
		 * @return string|bool Font url or False
		 */
		public function custom_font_url() {

			$fonts_url = WP_DEBUG ? '' : get_transient( 'awesplash_custom_font_url' );

			if ( empty( $fonts_url ) || is_customize_preview() ) {

				$fonts_url = awesplash_get_font_url( array(
					'awesplash_heading_typo',
					'awesplash_content_typo',
					'awesplash_button_typo',
					'awesplash_opt_typo',
					'awesplash_age_typo',
						) );

				if ( !WP_DEBUG ) {
					set_transient( 'awesplash_custom_font_url', $fonts_url );
				}
			}

			return esc_url_raw( $fonts_url );
		}

		/**
		 * Register scripts and style for splash page
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_style( 'awesplash-fonts', $this->font_url(), array(), null );

			wp_enqueue_style( 'awesplash-custom-fonts', $this->custom_font_url(), array(), null );

			wp_enqueue_style( 'awesplash-style', AWESPLASH_URL . 'assets/css/main.css', array(), AWESPLASH_VER );
			wp_enqueue_script( 'modernizr', AWESPLASH_URL . 'assets/js/vendor/modernizr-2.8.3.min.js', array(), AWESPLASH_VER, false );
			wp_enqueue_script( 'slick', AWESPLASH_URL . 'assets/js/plugins/slick.min.js', array( 'jquery' ), AWESPLASH_VER, true );
			wp_enqueue_script( 'ytplayer', AWESPLASH_URL . 'assets/js/plugins/jquery.mb.YTPlayer.min.js', array(), AWESPLASH_VER, true );
			wp_enqueue_script( 'vimeoplayer', AWESPLASH_URL . 'assets/js/plugins/jquery.mb.vimeo_player.min.js', array(), AWESPLASH_VER, true );
			wp_enqueue_script( 'animate-headline', AWESPLASH_URL . 'assets/js/plugins/animate-headline.js', array( 'jquery' ), AWESPLASH_VER, true );
			wp_enqueue_script( 'awesplash-main', AWESPLASH_URL . 'assets/js/main.js', array( 'jquery' ), AWESPLASH_VER, true );

			if ( $custom_css = awesplash_custom_css() ) {
				wp_add_inline_style( 'awesplash-style', $custom_css );
			}
			if ( $custom_css = get_theme_mod( 'awesplash_custom_css', '' ) ) {
				wp_add_inline_style( 'awesplash-fonts', $custom_css );
			}
		}

		/**
		 * Include functions
		 * @since 1.0.0
		 */
		public function includes() {
			require AWESPLASH_DIR . 'includes/ctoolkit/ctoolkit.php';
			require AWESPLASH_DIR . 'includes/helper-functions.php';
			require AWESPLASH_DIR . 'includes/customizer-functions.php';
			require AWESPLASH_DIR . 'includes/template-tags.php';

			if ( $this->is_acitve ) {
				require AWESPLASH_DIR . 'includes/class-awesplash-handle.php';
			}
		}

		/**
		 * Defined 
		 */
		public function defined() {
			define( 'AWESPLASH_URL', plugin_dir_url( __FILE__ ) );
			define( 'AWESPLASH_DIR', plugin_dir_path( __FILE__ ) );
			define( 'AWESPLASH_VER', '1.0.1' );
		}

		/**
		 * Load Local files.
		 * @since 1.0.0
		 * @return void
		 */
		public function load_plugin_textdomain() {

// Set filter for plugin's languages directory
			$dir = AWESPLASH_DIR . 'languages/';
			$dir = apply_filters( 'awesplash_languages_directory', $dir );

// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'awesplash' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'awesplash', $locale );

// Setup paths to current locale file
			$mofile_local = $dir . $mofile;

			$mofile_global = WP_LANG_DIR . '/awesplash/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/epl folder
				load_textdomain( 'awesplash', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/awesplash/languages/ folder
				load_textdomain( 'awesplash', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'awesplash', false, $dir );
			}
		}

		/**
		 * Just use awesplash page style
		 * @since 1.0.0
		 */
		public function remove_styles() {
			if ( $this->is_allow() && !is_admin() ) {
				global $wp_styles;

				$wp_styles->queue = array(
					'admin-bar',
					'awesplash-fonts',
					'awesplash-custom-fonts',
					'awesplash-style',
					'animate-headline',
					'customize-preview',
					'awesplash-customize-controls',
					'wp-mediaelement'
				);
			}
		}

		/**
		 * Just use awesplash page scripts
		 * @since 1.0.0
		 */
		public function remove_scripts() {
			if ( $this->is_allow() && !is_admin() ) {
				global $wp_scripts;

				$wp_scripts->queue = array(
					'admin-bar',
					'modernizr',
					'slick',
					'ytplayer',
					'vimeoplayer',
					'animate-headline',
					'awesplash-main',
					'html5',
					'customize-preview',
					'awesplash-customize-controls',
					'awesplash-customize-preview',
					'wp-mediaelement',
					'froogaloop',
					'customize-selective-refresh',
					'customize-preview-widgets',
					'customize-preview-nav-menus'
				);
				/**
				 * Remove all action in wp_footer except the default
				 */
				global $wp_filter;
				global $wp_version;

				$callbacks = $wp_version > 4.3 ? $wp_filter['wp_footer']->callbacks : $wp_filter['wp_footer'];

				$customizer_preview = array();

				if ( is_customize_preview() ) {
					$customizer_preview = $callbacks[1];
				}

				$save20 = array();
				$save1000 = array();

				foreach ( $callbacks[20] as $key => $value ) {
					if ( $key == 'wp_print_footer_scripts' || strpos( $key, 'customize_preview_settings' ) >= 0 ) {
						$save20[$key] = $value;
					}
				}

				foreach ( $callbacks[1000] as $key => $value ) {
					if ( $key == 'wp_admin_bar_render' || strpos( $key, 'export_preview_data' ) >= 0 ) {
						$save20[$key] = $value;
					}
				}

				if ( $wp_version > 4.3 ) {
					if ( isset( $callbacks[1] ) ) {
						$wp_filter['wp_footer']->callbacks[1] = $callbacks[1];
					}
					$wp_filter['wp_footer']->callbacks[20] = $save20;
					$wp_filter['wp_footer']->callbacks[1000] = $save1000;
				} else {

					if ( isset( $callbacks[1] ) ) {
						$wp_filter['wp_footer'][1] = $callbacks[1];
					}
					$wp_filter['wp_footer'][20] = $save20;
					$wp_filter['wp_footer'][1000] = $save1000;
				}
			}
		}

	}

	/**
	 * Main instance of AweSplash.
	 *
	 * Returns the main instance of awesplash to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return awesplash
	 */
	function awesplash() {
		return AweSplash::instance();
	}

	/**
	 * Global for backwards compatibility.
	 */
	$GLOBALS['awesplash'] = awesplash();
}
