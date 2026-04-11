<?php
/**
 * Base Module Class
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Module Base class for creating Elementor modules
 *
 * @since 1.1.0
 */
abstract class Module_Base {

	/**
	 * Reflection class for dynamic widget file loading
	 *
	 * @var \ReflectionClass
	 */
	private $reflection;

	/**
	 * Module instances cache (singleton)
	 *
	 * @var Module_Base[]
	 */
	protected static $_instances = array();

	/**
	 * Get module name
	 *
	 * @since 1.1.0
	 */
	abstract public function get_name();

	/**
	 * Get module widgets
	 *
	 * @since 1.1.0
	 */
	abstract public function get_widgets();

	/**
	 * Return the current module class name
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public static function class_name() {
		return get_called_class();
	}

	/**
	 * Get instance
	 *
	 * @return static
	 */
	public static function instance() {
		if ( empty( static::$_instances[ static::class_name() ] ) ) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	/**
	 * Constructor
	 *
	 * Hook into Elementor to register the widgets
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Initializes all widgets for the current module
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function init_widgets() {
		foreach ( $this->get_widgets() as $widget ) {
			$widget_class_name = $this->reflection->getNamespaceName() . '\\Widgets\\' . $widget;

			$this->register_widget( $widget_class_name );
		}
	}

	/**
	 * Register widget
	 *
	 * @param string $widget_class_name Widget class name.
	 *
	 * @return void
	 */
	public function register_widget( $widget_class_name ) {
		// Autoloader will load the class if it doesn't exist yet
		if ( ! class_exists( $widget_class_name ) ) {
			// Try to load manually as fallback
			$file = $this->get_widget_file( $widget_class_name );
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}

		if ( class_exists( $widget_class_name ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new $widget_class_name() );
		}
	}

	/**
	 * Convert widget class name to file path
	 *
	 * @param string $widget_class_name Widget class name.
	 * @return string
	 */
	private function get_widget_file( $widget_class_name ) {
		// Convert full class name to file path
		$file_path = strtolower(
			preg_replace(
				array(
					'/^Hester_Core\\\Elementor\\\/',
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
				$widget_class_name
			)
		);

		return HESTER_CORE_ELEMENTOR_PATH . $file_path . '.php';
	}
}
