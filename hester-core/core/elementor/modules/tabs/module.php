<?php
namespace Hester_Core\Elementor\Modules\Tabs;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tabs Module
 */
class Module extends Module_Base {
	/**
	 * Get module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-tabs';
	}

	/**
	 * Get module widgets
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'Tabs' );
	}
}
