<?php
/**
 * Hester Core Elementor Widgets Bootstrap
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Hester Core Elementor Plugin Class
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Plugin singleton instance
	 *
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * Elementor modules manager
	 *
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		add_action( 'elementor/init', array( $this, 'init' ), 0 );
		add_action( 'elementor/elements/categories_registered', array( $this, 'init_panel_section' ) );
	}

	/**
	 * Autoload classes
	 *
	 * @since 1.0.0
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					array(
						'/^' . __NAMESPACE__ . '\\\/',
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
					$class_to_load
				)
			);
			$filename = HESTER_CORE_ELEMENTOR_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}
	}

	/**
	 * Init Elementor
	 *
	 * @since 1.0.0
	 */
	public function init() {
		do_action( 'hester_core_elementor_init' );

		// Explicitly require these files to ensure classes are available
		require_once HESTER_CORE_ELEMENTOR_PATH . 'helpers.php';
		require_once HESTER_CORE_ELEMENTOR_PATH . 'modules-manager.php';
		require_once HESTER_CORE_ELEMENTOR_PATH . 'assets-manager.php';

		$this->modules_manager = new Modules_Manager();
		new Assets_Manager();
	}

	/**
	 * Init panel section
	 *
	 * @since 1.0.0
	 */
	public function init_panel_section() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'hester-core',
			array(
				'title' => __( 'Hester Core', 'hester-core' ),
				'icon'  => 'eicon-h-packs',
			)
		);
	}

	/**
	 * Cloning is forbidden
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hester-core' ), '1.0.0' );
	}

	/**
	 * Unserializing is forbidden
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hester-core' ), '1.0.0' );
	}
}

/**
 * Get Elementor plugin instance
 *
 * @return Plugin
 */
function hester_core_elementor() {
	return Plugin::instance();
}

hester_core_elementor();
