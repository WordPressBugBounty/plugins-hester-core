<?php
/**
 * Slides Module
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Slides;

use Elementor\Controls_Manager;
use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slides module.
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/controls/register', array( $this, 'register_controls' ) );
	}

	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-slides';
	}

	/**
	 * Get module widgets.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'Slides' );
	}

	/**
	 * Register module controls.
	 *
	 * @param Controls_Manager $controls_manager Controls manager.
	 * @return void
	 */
	public function register_controls( Controls_Manager $controls_manager ) {
		$class_name = '\\Hester_Core\\Elementor\\Modules\\Slides\\Controls\\Control_Slides_Animation';

		if ( ! class_exists( $class_name, false ) ) {
			require_once HESTER_CORE_ELEMENTOR_PATH . 'modules/slides/controls/control-slides-animation.php';
		}

		if ( class_exists( $class_name ) ) {
			$controls_manager->register( new $class_name() );
		}
	}
}
