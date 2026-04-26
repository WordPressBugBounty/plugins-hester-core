<?php
/**
 * Hester Theme Library. Browse and install Peregrine Themes from WordPress.org.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.1.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Theme Library Class.
 *
 * @since 1.1.6
 * @package Hester Core
 */
final class Hester_Theme_Library {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	private static $instance;

	/**
	 * Version.
	 *
	 * @since 1.1.6
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Cached themes array.
	 *
	 * @since 1.1.6
	 * @var array|false
	 */
	public $themes = false;

	/**
	 * WordPress.org API endpoint for Peregrine Themes.
	 */
	const API_URL = 'https://api.wordpress.org/themes/info/1.2/?action=query_themes&author=peregrinethemes';

	/**
	 * Transient key for caching theme list.
	 */
	const TRANSIENT_KEY = 'hester_core_peregrine_themes';

	/**
	 * Main Hester Theme Library Instance.
	 *
	 * @since 1.1.6
	 * @return Hester_Theme_Library
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Theme_Library ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.1.6
	 */
	public function __construct() {

		$this->version = HESTER_CORE_VERSION;

		$this->includes();
		$this->hooks();

		do_action( 'hester_theme_library_loaded' );
	}

	/**
	 * Include files.
	 *
	 * @since 1.1.6
	 */
	private function includes() {

		require_once plugin_dir_path( __FILE__ ) . 'class-hester-theme-library-page.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.1.6
	 */
	private function hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'admin_init', array( $this, 'refresh_themes' ) );
		add_action( 'wp_ajax_hester_core_activate_theme', array( $this, 'activate_theme' ) );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since 1.1.6
	 *
	 * @param string $hook Current hook name.
	 */
	public function admin_enqueue( $hook = '' ) {

		$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$theme_name = hester_core()->theme_name;

		if ( 'hester_page_' . $theme_name . '-theme-library' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'hester-theme-library',
			plugin_dir_url( __FILE__ ) . 'assets/js/theme-library' . $suffix . '.js',
			array( 'jquery', 'wp-util', 'updates', 'hester-toast' ),
			$this->version,
			true
		);

		wp_localize_script(
			'hester-theme-library',
			'hesterCoreThemeLibrary',
			array(
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'wpnonce'          => wp_create_nonce( 'hester_core_theme_library' ),
				'themes'           => $this->get_themes_with_status(),
				'canInstallThemes' => current_user_can( 'install_themes' ),
				'canSwitchThemes'  => current_user_can( 'switch_themes' ),
				'customizeUrl'     => admin_url( 'customize.php' ),
				'strings'          => array(
					'install'         => __( 'Install', 'hester-core' ),
					'installing'      => __( 'Installing...', 'hester-core' ),
					'installed'       => __( 'Installed', 'hester-core' ),
					'installSuccess'  => __( 'Theme installation successful!', 'hester-core' ),
					'installFailed'   => __( 'Installation failed', 'hester-core' ),
					'activate'        => __( 'Activate', 'hester-core' ),
					'activating'      => __( 'Activating...', 'hester-core' ),
					'active'          => __( 'Active', 'hester-core' ),
					'activateSuccess' => __( 'Theme activated successfully!', 'hester-core' ),
					'activateFailed'  => __( 'Activation failed', 'hester-core' ),
					'noThemesFound'   => __( 'No themes found.', 'hester-core' ),
					'customize'       => __( 'Customize', 'hester-core' ),
					'preview'         => __( 'Preview', 'hester-core' ),
				),
			)
		);

		wp_enqueue_style(
			'hester-theme-library',
			plugin_dir_url( __FILE__ ) . 'assets/css/theme-library' . $suffix . '.css',
			array(),
			$this->version
		);
	}

	/**
	 * Fetch themes from WordPress.org API, with transient caching.
	 *
	 * @since 1.1.6
	 * @return array
	 */
	public function get_themes() {

		if ( false !== $this->themes ) {
			return $this->themes;
		}

		$cached = get_transient( self::TRANSIENT_KEY );

		if ( false !== $cached ) {
			$this->themes = $cached;
			return $this->themes;
		}

		$response = wp_remote_get(
			self::API_URL,
			array( 'timeout' => 30 )
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data['themes'] ) || ! is_array( $data['themes'] ) ) {
			return array();
		}

		// Exclude 'blogun' theme if present.
		$this->themes = array_values(
			array_filter(
				$data['themes'],
				function ( $theme ) {
					return isset( $theme['slug'] ) && 'blogun' !== $theme['slug'];
				}
			)
		);

		set_transient( self::TRANSIENT_KEY, $this->themes, DAY_IN_SECONDS );

		return $this->themes;
	}

	/**
	 * Get themes array with current install/active status for each theme.
	 *
	 * @since 1.1.6
	 * @return array
	 */
	public function get_themes_with_status() {

		$themes           = $this->get_themes();
		$active_theme     = get_option( 'stylesheet' );
		$installed_themes = wp_get_themes();

		foreach ( $themes as &$theme ) {
			$slug = $theme['slug'];

			if ( $slug === $active_theme ) {
				$theme['status'] = 'active';
			} elseif ( isset( $installed_themes[ $slug ] ) ) {
				$theme['status'] = 'installed';
			} else {
				$theme['status'] = 'not-installed';
			}
		}
		unset( $theme );

		return $themes;
	}

	/**
	 * Clear theme cache on nonce-verified refresh request.
	 *
	 * @since 1.1.6
	 */
	public function refresh_themes() {

		if (
			! isset( $_GET['hester_core_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['hester_core_nonce'] ) ), 'refresh_themes' )
		) {
			return;
		}

		if ( ! current_user_can( apply_filters( 'hester_manage_cap', 'edit_theme_options' ) ) ) {
			return;
		}

		delete_transient( self::TRANSIENT_KEY );
		$this->themes = false;

		$theme_name = hester_core()->theme_name;
		wp_safe_redirect( admin_url( 'admin.php?page=' . $theme_name . '-theme-library' ) );
		exit;
	}

	/**
	 * AJAX handler: activate a theme by slug.
	 *
	 * @since 1.1.6
	 */
	public function activate_theme() {

		check_ajax_referer( 'hester_core_theme_library', 'nonce' );

		if ( ! current_user_can( 'switch_themes' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to activate themes.', 'hester-core' ) ) );
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $slug ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid theme slug.', 'hester-core' ) ) );
		}

		$theme = wp_get_theme( $slug );

		if ( ! $theme->exists() ) {
			wp_send_json_error( array( 'message' => __( 'Theme is not installed.', 'hester-core' ) ) );
		}

		switch_theme( $slug );

		wp_send_json_success(
			array(
				'slug'     => $slug,
				'message'  => __( 'Theme activated successfully.', 'hester-core' ),
				'redirect' => admin_url( 'admin.php?page=' . $theme->template . '-theme-library' ),
			)
		);
	}
}

/**
 * Returns the one Hester_Theme_Library instance.
 *
 * @since 1.1.6
 * @return Hester_Theme_Library
 */
function hester_theme_library() {
	return Hester_Theme_Library::instance();
}

hester_theme_library();
