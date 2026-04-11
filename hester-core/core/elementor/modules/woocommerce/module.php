<?php
namespace Hester_Core\Elementor\Modules\Woocommerce;

use Hester_Core\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module extends Module_Base {

	public function get_name() {
		return 'hester-woocommerce';
	}

	public function get_widgets() {
		return array(
			'Woo_Products',
			'Woo_Slider',
			'Woo_Categories',
			'Woo_Add_To_Cart',
		);
	}

	public function __construct() {
		parent::__construct();

		if ( class_exists( 'WooCommerce' ) || hester_core_is_woocommerce_active() ) {
			wc()->frontend_includes();
		}
	}
}

function hester_core_is_woocommerce_active() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( 'woocommerce/woocommerce.php' );
}
