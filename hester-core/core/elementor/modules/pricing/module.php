<?php
/**
 * Pricing Module
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Pricing;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pricing module.
 */
class Module extends Module_Base {

	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-pricing';
	}

	/**
	 * Get widgets in module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'Pricing' );
	}
}
