<?php
namespace Hester_Core\Elementor\Modules\OffCanvas;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Off Canvas Module
 */
class Module extends Module_Base {
	/**
	 * Get module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-off-canvas';
	}

	/**
	 * Get module widgets
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'Off_Canvas' );
	}
}
