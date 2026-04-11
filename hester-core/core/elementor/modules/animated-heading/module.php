<?php
/**
 * Animated Heading Module
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\AnimatedHeading;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Animated Heading Module
 *
 * @since 1.0.0
 */
class Module extends Module_Base {

	/**
	 * Get module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-animated-heading';
	}

	/**
	 * Get module widgets
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'AnimatedHeading' );
	}
}
