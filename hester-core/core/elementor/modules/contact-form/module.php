<?php
namespace Hester_Core\Elementor\Modules\ContactForm;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact Form Module
 */
class Module extends Module_Base {
	/**
	 * Get module name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-contact-form';
	}

	/**
	 * Get module widgets
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array( 'Contact_Form' );
	}
}
