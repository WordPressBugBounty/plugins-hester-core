<?php
/**
 * Base Widget Class
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Base;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Base Widget for all Hester Core widgets
 *
 * @since 1.0.0
 */
abstract class Base_Widget extends Widget_Base {

	/**
	 * Get widget category
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'hester-core' );
	}

	/**
	 * Get helpful keywords for the widget
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'hester', 'core' );
	}
}
