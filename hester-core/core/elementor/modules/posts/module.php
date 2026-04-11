<?php
/**
 * Posts Module
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Posts;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module extends Module_Base {

	public function get_name() {
		return 'hester-posts';
	}

	public function get_widgets() {
		return array( 'Posts' );
	}
}
