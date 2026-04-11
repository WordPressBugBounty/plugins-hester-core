<?php
/**
 * Modules Manager Class
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Modules Manager
 *
 * @since 1.0.0
 */
class Modules_Manager {

	/**
	 * Registered modules cache
	 *
	 * @var Module_Base[]
	 */
	private $modules = array();

	/**
	 * Constructor - loads and registers all available modules
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}

	/**
	 * Require base classes
	 *
	 * Base classes must be explicitly required before module registration
	 * to ensure they're available for inheritance.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function require_files() {
		require HESTER_CORE_ELEMENTOR_PATH . 'base/module.php';
		require HESTER_CORE_ELEMENTOR_PATH . 'base/widget.php';
	}

	/**
	 * Register and instantiate all available modules
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_modules() {
		$modules = array(
			'query-post',
			'animated-heading',
			'posts',
			'pricing',
			'slides',
			'tabs',
			'flip-box',
			'search',
			'off-canvas',
			'contact-form',
		);

		// Add WooCommerce modules if WooCommerce is active
		if ( class_exists( 'WooCommerce' ) || $this->is_woocommerce_active() ) {
			$modules[] = 'woocommerce';
		}

		foreach ( $modules as $module_name ) {
			// Convert kebab-case to PascalCase
			$class_name      = str_replace( '-', ' ', $module_name );
			$class_name      = str_replace( ' ', '', ucwords( $class_name ) );
			$full_class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\\Module';

			// Autoloader will handle loading the class when instantiated
			if ( class_exists( $full_class_name ) || $this->load_module_class( $full_class_name ) ) {
				$this->modules[ $module_name ] = $full_class_name::instance();
			}
		}
	}

	/**
	 * Manually load module class (for cases where autoloader may not trigger)
	 *
	 * @param string $class_name Full class name.
	 * @return bool
	 */
	private function load_module_class( $class_name ) {
		// Build file path from class name
		$file_path = strtolower(
			preg_replace(
				array(
					'/^' . preg_quote( __NAMESPACE__ ) . '\\\/',
					'/([a-z])([A-Z])/',
					'/_/',
					'/\\\\/',
				),
				array(
					'',
					'$1-$2',
					'-',
					DIRECTORY_SEPARATOR,
				),
				$class_name
			)
		);

		$file = HESTER_CORE_ELEMENTOR_PATH . $file_path . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
			return class_exists( $class_name );
		}

		return false;
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	private function is_woocommerce_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Get module
	 *
	 * @param string $module_name Module name.
	 *
	 * @return Module_Base|false
	 */
	public function get_module( $module_name ) {
		return isset( $this->modules[ $module_name ] ) ? $this->modules[ $module_name ] : false;
	}
}
