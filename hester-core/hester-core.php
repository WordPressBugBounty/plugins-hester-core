<?php
/**
 * Plugin Name: Hester Core
 * Description: The official companion plugin for Peregrine Themes. Adds widgets, customization options, Elementor widgets, and demo import features.
 * Author:      Peregrine Themes
 * Author URI:  https://peregrine-themes.com
 * Version:     1.1.2
 * Text Domain: hester-core
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Tested up to: 6.9
 *
 * Hester Core is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Hester Core is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Hester Core. If not, see <https://www.gnu.org/licenses/>.
 *
 * @category  Plugin
 * @package   Hester_Core
 * @link      https://peregrine-themes.com
 * @copyright 2022 Peregrine Themes
 * @author    Peregrine Themes <peregrinethemes@gmail.com>
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @since     1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme templates that receive full Hester Core support (widgets, admin, Elementor, etc.).
 *
 * @since 1.1.1
 * @var string[]
 */
define(
	'HESTER_CORE_SUPPORTED_THEMES',
	array(
		'hester',
		'hester-pro',
		'blogun',
		'blogun-pro',
		'bloglo',
		'bloglo-pro',
		'bloghash',
		'bloghash-pro',
		'shopwell',
		'blogsy',
	)
);

/**
 * Subset of supported themes that load the widgets component.
 *
 * 'shopwell' and 'blogsy' are intentionally excluded — they do not
 * use the shared widgets provided by this plugin.
 *
 * @since 1.1.1
 * @var string[]
 */
define(
	'HESTER_CORE_WIDGET_THEMES',
	array(
		'hester',
		'hester-pro',
		'blogun',
		'blogun-pro',
		'bloglo',
		'bloglo-pro',
		'bloghash',
		'bloghash-pro',
	)
);

/**
 * Main Hester Core class.
 *
 * @package Hester_Core
 * @since   1.0.0
 */
final class Hester_Core {

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '1.1.2';

	/**
	 * Active theme template slug (e.g. "hester", "bloglo").
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $theme_name = 'hester';

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @var Hester_Core|null
	 */
	private static $instance = null;

	/**
	 * Returns the single instance of the class, creating it on first call.
	 *
	 * @since  1.0.0
	 * @return Hester_Core
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->constants();
			self::$instance->load_textdomain();
			self::$instance->set_theme_name();
			self::$instance->includes();

			add_action( 'plugins_loaded', array( self::$instance, 'on_plugins_loaded' ), 10 );
		}

		return self::$instance;
	}

	/**
	 * Private constructor — use ::instance().
	 *
	 * @since 1.0.0
	 */
	private function __construct() {}

	/**
	 * Defines plugin constants.
	 *
	 * @since 1.0.0
	 */
	private function constants() {
		$constants = array(
			'HESTER_CORE_VERSION'        => $this->version,
			'HESTER_CORE_PLUGIN_DIR'     => plugin_dir_path( __FILE__ ),
			'HESTER_CORE_PLUGIN_URL'     => plugin_dir_url( __FILE__ ),
			'HESTER_CORE_PLUGIN_FILE'    => __FILE__,
			'HESTER_CORE_ELEMENTOR_PATH' => plugin_dir_path( __FILE__ ) . 'core/elementor/',
			'HESTER_CORE_ELEMENTOR_URL'  => plugin_dir_url( __FILE__ ) . 'core/elementor/',
		);

		foreach ( $constants as $name => $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
	}

	/**
	 * Loads plugin text domain for translations.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'hester-core',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}

	/**
	 * Resolves and stores the active theme's base template slug.
	 *
	 * @since 1.0.0
	 */
	private function set_theme_name() {
		$theme = wp_get_theme();

		if ( preg_match( '/^([\w]+)/', $theme->template, $match ) ) {
			$this->theme_name = strtolower( $match[0] );
		}
	}

	/**
	 * Includes required files based on the active theme and environment.
	 *
	 * @since 1.0.0
	 */
	private function includes() {
		$theme_template = wp_get_theme()->template;

		// Widgets — only for themes that use the shared widget component.
		// 'shopwell' and 'blogsy' are excluded intentionally.
		if ( in_array( $theme_template, HESTER_CORE_WIDGET_THEMES, true ) ) {
			require_once HESTER_CORE_PLUGIN_DIR . 'core/widgets/widgets.php';
		}

		// Admin class — always required.
		require_once HESTER_CORE_PLUGIN_DIR . 'core/admin/class-hester-core-admin.php';

		// Elementor integration — only when Elementor is active.
		if ( did_action( 'elementor/loaded' ) ) {
			require_once HESTER_CORE_ELEMENTOR_PATH . 'plugin.php';
			\Hester_Core\Elementor\Plugin::instance();
		}

		// WP-CLI commands.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once HESTER_CORE_PLUGIN_DIR . 'core/cli/class-hester-core-cli.php';
		}

		// Theme-specific extras.
		if ( in_array( $theme_template, array( 'hester', 'hester-pro' ), true ) ) {
			require_once HESTER_CORE_PLUGIN_DIR . 'themes/hester/hester.php';
		}
	}

	/**
	 * Fires the hester_core_loaded action once all dependencies are available.
	 *
	 * @since 1.0.0
	 */
	public function on_plugins_loaded() {
		/**
		 * Fires after Hester Core is fully loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hester_core_loaded' );
	}
}

/**
 * Returns the single Hester_Core instance.
 *
 * Preferred usage:
 *   $hester_core = hester_core();
 *
 * @since  1.0.0
 * @return Hester_Core
 */
function hester_core() {
	return Hester_Core::instance();
}

// Bootstrap the plugin only for supported themes; show an admin notice otherwise.
if ( hester_core_is_supported_theme() ) {
	hester_core();
} else {
	add_action( 'admin_notices', 'hester_core_unsupported_theme_notice' );
}

// -------------------------------------------------------------------------
// Helper functions
// -------------------------------------------------------------------------

/**
 * Checks whether the currently active theme is supported by Hester Core.
 *
 * @since  1.1.1
 * @return bool
 */
function hester_core_is_supported_theme() {
	return in_array( wp_get_theme()->template, HESTER_CORE_SUPPORTED_THEMES, true );
}

// -------------------------------------------------------------------------
// Admin notices
// -------------------------------------------------------------------------

/**
 * Displays an admin notice when an unsupported theme is active.
 *
 * @since 1.0.0
 */
function hester_core_unsupported_theme_notice() {
	?>
	<div class="notice notice-warning">
		<p><?php esc_html_e( 'Please activate one of Peregrine Themes before activating Hester Core.', 'hester-core' ); ?></p>
	</div>
	<?php
}

/**
 * Displays a dismissible welcome notice after the plugin is activated.
 *
 * @since 1.1.1
 */
function hester_core_welcome_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! get_option( 'hester_core_show_welcome_notice', true ) ) {
		return;
	}

	if ( ! hester_core_is_supported_theme() ) {
		return;
	}

	$dismiss_url   = hester_core_dismiss_welcome_notice_url();
	$banner_url    = plugin_dir_url( HESTER_CORE_PLUGIN_FILE ) . 'assets/images/hester-core-elementor-widgets.png';
	$dashboard_url = admin_url( 'admin.php?page=' . hester_core()->theme_name . '-dashboard' );
	?>
	<div class="notice notice-success hester-core-welcome-notice">
		<style>
			.hester-core-welcome-notice{position:relative;padding:20px;display:flex;align-items:center;justify-content:space-between}
			.hester-core-welcome-notice .hester-core-welcome-text{flex:1;min-width:0}
			.hester-core-welcome-notice .hester-core-welcome-text h2{margin:0 0 8px;font-size:18px}
			.hester-core-welcome-notice .hester-core-welcome-text p{margin:0 0 12px;line-height:1.6}
			.hester-core-welcome-notice .hester-core-welcome-banner{max-width:350px;margin-left:20px;flex-shrink:0}
			.hester-core-welcome-notice .hester-core-welcome-banner img{width:100%;height:auto;border-radius:6px}
			.hester-core-welcome-notice .hester-core-welcome-actions{margin-top:12px}
			.hester-core-welcome-notice .hester-core-welcome-actions .button{margin-right:8px}
		</style>
		<div class="hester-core-welcome-text">
			<h2><?php esc_html_e( 'Welcome to Hester Core!', 'hester-core' ); ?></h2>
			<p><?php echo wp_kses_post( sprintf( __( 'This plugin provides multiple Elementor widgets and other features for themes by %s.', 'hester-core' ), '<a href="' . esc_url( 'https://wordpress.org/themes/author/peregrinethemes/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Peregrine Themes', 'hester-core' ) . '</a>' ) ); ?></p>
			<div class="hester-core-welcome-actions">
				<a href="<?php echo esc_url( $dashboard_url ); ?>" class="button button-primary">
					<?php esc_html_e( 'Open Hester Dashboard', 'hester-core' ); ?>
				</a>
				<a href="<?php echo esc_url( $dismiss_url ); ?>" class="button button-secondary">
					<?php esc_html_e( 'Dismiss', 'hester-core' ); ?>
				</a>
			</div>
		</div>
		<div class="hester-core-welcome-banner">
			<img
				src="<?php echo esc_url( $banner_url ); ?>"
				alt="<?php esc_attr_e( 'Hester Core', 'hester-core' ); ?>"
			/>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', 'hester_core_welcome_notice' );

// -------------------------------------------------------------------------
// Welcome notice dismiss handler
// -------------------------------------------------------------------------

/**
 * Builds the nonce-protected URL used to dismiss the welcome notice.
 *
 * @since  1.1.1
 * @return string
 */
function hester_core_dismiss_welcome_notice_url() {
	return wp_nonce_url(
		add_query_arg( 'hester_core_dismiss_welcome_notice', '1', admin_url( 'index.php' ) ),
		'hester_core_dismiss_welcome_notice',
		'hester_core_dismiss_welcome_notice_nonce'
	);
}

/**
 * Handles the GET request that dismisses the welcome notice.
 *
 * Verifies capability, nonce, and the expected query arg before acting.
 *
 * @since 1.1.1
 */
function hester_core_handle_welcome_notice_dismiss() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce verified below.
	if ( '1' !== filter_input( INPUT_GET, 'hester_core_dismiss_welcome_notice', FILTER_SANITIZE_NUMBER_INT ) ) {
		return;
	}

	check_admin_referer( 'hester_core_dismiss_welcome_notice', 'hester_core_dismiss_welcome_notice_nonce' );

	update_option( 'hester_core_show_welcome_notice', false );

	wp_safe_redirect(
		esc_url_raw(
			remove_query_arg( array( 'hester_core_dismiss_welcome_notice', 'hester_core_dismiss_welcome_notice_nonce' ) )
		)
	);
	exit;
}
add_action( 'admin_init', 'hester_core_handle_welcome_notice_dismiss' );

