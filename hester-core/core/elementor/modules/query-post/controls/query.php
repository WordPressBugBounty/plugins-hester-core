<?php
namespace Hester_Core\Elementor\Modules\QueryPost\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Query extends Base_Data_Control {
	const CONTROL_ID = 'hester-query-posts';

	public function get_type() {
		return self::CONTROL_ID;
	}

	protected function get_default_settings() {
		return array(
			'label_block' => true,
			'multiple'    => false,
			'options'     => array(),
			'post_type'   => 'all',
		);
	}

	public function enqueue() {
		wp_register_script(
			'hester-query-post',
			HESTER_CORE_ELEMENTOR_URL . 'assets/js/query-post.js',
			array( 'jquery' ),
			HESTER_CORE_VERSION,
			true
		);

		wp_localize_script(
			'hester-query-post',
			'hesterData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'hester_nonce' ),
			)
		);

		wp_enqueue_script( 'hester-query-post' );
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each(data.options, function(option_title, option_value) {
						var value = data.controlValue;
						if ( typeof value === 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var list = _.values(value);
							var selected = ( -1 !== list.indexOf(option_value) ) ? 'selected' : '';
						}
					#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# }); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
