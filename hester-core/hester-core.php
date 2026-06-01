<?php
/**
 * Plugin Name: Hester Core
 * Description: The official companion plugin for Peregrine Themes. Adds widgets, customization options, Elementor widgets, and demo import features.
 * Author:      Peregrine Themes
 * Author URI:  https://peregrine-themes.com
 * Version:     1.1.6
 * Text Domain: hester-core
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Tested up to: 7.0
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
	public $version = '1.1.6';

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
	private function __construct() {
		add_action( 'after_switch_theme', array( $this, 'after_theme_switch' ) );
	}

	/**
	 * After theme switch.
	 *
	 * @since 1.1.6
	 */
	public function after_theme_switch() {
		// Delete theme demos transient.
		delete_transient( 'hester_core_demo_templates' );
	}

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
		if ( preg_match( '/^([\w]+)/', wp_get_theme()->template, $match ) ) {
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
		if ( in_array( $theme_template, array( 'hester' ), true ) ) {
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
	$theme        = wp_get_theme();
	$parent_theme = $theme->parent() ? $theme->parent() : $theme;

	// Check explicit list (for backwards compatibility).
	if ( in_array( $parent_theme->template, HESTER_CORE_SUPPORTED_THEMES, true ) ) {
		return true;
	}

	// Automatically support any theme authored by Peregrine Themes.
	if ( strpos( $parent_theme->get( 'Author' ), 'Peregrine Themes' ) !== false ) {
		return true;
	}

	// Allow filtering for external themes or special cases.
	return apply_filters( 'hester_core_is_supported_theme', false, $theme );
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

	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

	$allowed_pages = array(
		hester_core()->theme_name . '-dashboard',
		hester_core()->theme_name . '-theme-library',
		hester_core()->theme_name . '-demo-library',
		hester_core()->theme_name . '-changelog',
		hester_core()->theme_name . '-plugins',
		hester_core()->theme_name . '-about',
	);

	if ( in_array( $page, $allowed_pages, true ) ) {
		return;
	}

	$dashboard_url     = admin_url( 'admin.php?page=' . hester_core()->theme_name . '-dashboard' );
	$theme_library_url = admin_url( 'admin.php?page=' . hester_core()->theme_name . '-theme-library' );

	// Enqueue AJAX script.
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script( 'hester-core-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), HESTER_CORE_VERSION, true );
	wp_localize_script(
		'hester-core-admin',
		'hester_core_admin',
		array(
			'ajax_url'            => admin_url( 'admin-ajax.php' ),
			'nonce'               => wp_create_nonce( 'hester_core_dismiss_welcome_notice' ),
			'dismiss_text'        => __( 'Dismissing...', 'hester-core' ),
			'dismiss_button_text' => __( 'Dismiss', 'hester-core' ),
			'error_message'       => __( 'Failed to dismiss notice. Please try again.', 'hester-core' ),
		)
	);
	?>
	<div class="notice notice-success hester-core-welcome-notice" id="hester-core-welcome-notice">
		<style>
			.hester-core-welcome-notice{position:relative;padding:20px;display:flex;align-items:center;justify-content:space-between}
			.hester-core-welcome-notice .hester-core-welcome-text{flex:1;min-width:0}
			.hester-core-welcome-notice .hester-core-welcome-text h2{margin:0 0 8px;font-size:18px}
			.hester-core-welcome-notice .hester-core-welcome-text p{margin:0 0 12px;line-height:1.6}
			.hester-core-welcome-notice .hester-core-welcome-actions{margin-top:12px}
			.hester-core-welcome-notice .hester-core-welcome-actions .button{margin-right:8px}
		</style>
		<button type="button" class="hester-core-close notice-dismiss" aria-label="<?php esc_attr_e( 'Dismiss notice', 'hester-core' ); ?>" title="<?php esc_attr_e( 'Dismiss notice', 'hester-core' ); ?>">
			<span class="screen-reader-text"><?php echo esc_html__( 'Dismiss notice.', 'hester-core' ); ?></span>
		</button>
		<div class="hester-core-welcome-text">
			<h2><?php esc_html_e( 'Welcome to Hester Core!', 'hester-core' ); ?></h2>
			<p>
			<?php
			echo wp_kses_post(
				sprintf(
				/* translators: %s is a link to the Peregrine Themes library page */
					__( 'This plugin provides multiple Elementor widgets and other features for themes by %s.', 'hester-core' ),
					'<a href="' . esc_url( $theme_library_url ) . '">' . esc_html__( 'Peregrine Themes', 'hester-core' ) . '</a>'
				)
			);
			?>
				</p>
			<div class="hester-core-welcome-actions">
				<a href="<?php echo esc_url( $dashboard_url ); ?>" class="button button-primary">
					<?php esc_html_e( 'Open Hester Dashboard', 'hester-core' ); ?>
				</a>
				<button type="button" class="button button-secondary" id="hester-core-dismiss-notice">
					<?php esc_html_e( 'Dismiss', 'hester-core' ); ?>
				</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', 'hester_core_welcome_notice' );

// -------------------------------------------------------------------------
// Welcome notice dismiss handler (AJAX)
// -------------------------------------------------------------------------

/**
 * Handles the AJAX request that dismisses the welcome notice.
 *
 * @since 1.1.1
 */
function hester_core_ajax_dismiss_welcome_notice() {
	// Check nonce.
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'hester_core_dismiss_welcome_notice' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}

	// Check permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
	}

	// Create option if missing, otherwise update it.
	if ( ! add_option( 'hester_core_show_welcome_notice', false ) ) {
		update_option( 'hester_core_show_welcome_notice', false );
	}

	wp_send_json_success();
}
add_action( 'wp_ajax_hester_core_dismiss_welcome_notice', 'hester_core_ajax_dismiss_welcome_notice' );
