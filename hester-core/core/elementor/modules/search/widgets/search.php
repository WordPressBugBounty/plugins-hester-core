<?php
namespace Hester_Core\Elementor\Modules\Search\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Hester_Core\Elementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Search extends Base_Widget {

	public function get_name() {
		return 'hester-search';
	}

	public function get_title() {
		return __( 'Ajax Search', 'hester-core' );
	}

	public function get_icon() {

		return 'hester-icon eicon-search';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_script_depends() {
		return array( 'hester-search' );
	}

	public function get_style_depends() {
		return array( 'hester-search', 'font-awesome-5-all' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_search',
			array(
				'label' => __( 'Search', 'hester-core' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'              => __( 'Source', 'hester-core' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'product',
				'options'            => array(
					'any'     => __( 'All Post Types', 'hester-core' ),
					'post'    => __( 'Posts', 'hester-core' ),
					'page'    => __( 'Pages', 'hester-core' ),
					'product' => __( 'Products', 'hester-core' ),
				),
				'prefix_class'       => 'hester-search--source-',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-search-wrap' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-searchform, {{WRAPPER}} .hester-searchform input.field' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'       => __( 'Placeholder', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Search', 'hester-core' ),
				'placeholder' => __( 'Search', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'enable_ajax',
			array(
				'label'        => __( 'Enable Ajax', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'hester-core' ),
				'label_off'    => __( 'Hide', 'hester-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'ajax_results_limit',
			array(
				'label'     => __( 'Results Limit', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'min'       => 1,
				'max'       => 50,
				'step'      => 1,
				'condition' => array(
					'enable_ajax' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'hester-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'hester-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'hester-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'hester-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'      => '',
				'prefix_class' => 'wew%s-align-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input',
			array(
				'label' => __( 'Input', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_input_style' );

		$this->start_controls_tab(
			'tab_input_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'input_bg',
			array(
				'label'     => __( 'Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'input_bg_hover',
			array(
				'label'     => __( 'Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_color_hover',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			array(
				'label' => __( 'Focus', 'hester-core' ),
			)
		);

		$this->add_control(
			'input_bg_focus',
			array(
				'label'     => __( 'Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_color_focus',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_border_color_focus',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform input.field:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .hester-searchform input.field:focus',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'input_border',
				'label'       => __( 'Border', 'hester-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .hester-searchform input.field',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-searchform input.field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-searchform input.field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'newsletter_input',
				'selector' => '{{WRAPPER}} .hester-searchform input.field',

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			array(
				'label' => __( 'Icon Button', 'hester-core' ),
				'type'  => Controls_Manager::SECTION,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Font Size', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 12,
				),
				'range'     => array(
					'min' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_position',
			array(
				'label'     => __( 'Position', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
				),
				'range'     => array(
					'min' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button' => is_rtl() ? 'left: {{SIZE}}px;' : 'right: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => __( 'Button Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-search-wrap' => '--hester-button-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Button Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-searchform button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_bg_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'btn_color_hover',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btn_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-searchform button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_results',
			array(
				'label' => __( 'Search Results', 'hester-core' ),
				'type'  => Controls_Manager::SECTION,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'results_bg',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->start_controls_tabs( 'tabs_results_links_style' );

		$this->start_controls_tab(
			'tab_results_links_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'results_links_bg',
			array(
				'label'     => __( 'Links Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.search-result-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_links_color',
			array(
				'label'     => __( 'Links Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.search-result-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_links_border_color',
			array(
				'label'     => __( 'Links Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_icons_color',
			array(
				'label'     => __( 'Icons Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a i.icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_all_links_color',
			array(
				'label'     => __( 'All Results Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.all-results' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'results_links_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'results_links_hover_bg',
			array(
				'label'     => __( 'Links Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.search-result-link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_links_hover_color',
			array(
				'label'     => __( 'Links Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.search-result-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_links_hover_border_color',
			array(
				'label'     => __( 'Links Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_icons_hover_color',
			array(
				'label'     => __( 'Icons Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a:hover i.icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'results_all_links_hover_color',
			array(
				'label'     => __( 'All Results Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a.all-results:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'results_links_padding',
			array(
				'label'      => __( 'Links Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'results_search_typo',
				'selector' => '{{WRAPPER}} .hester-search-wrap .hester-search-results ul li a',

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_no_search_results',
			array(
				'label' => __( 'No Results Found', 'hester-core' ),
				'type'  => Controls_Manager::SECTION,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'no_results_heading_color',
			array(
				'label'     => __( 'Heading Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-no-search-results h6' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'no_results_heading_typo',
				'selector' => '{{WRAPPER}} .hester-search-wrap .hester-no-search-results h6',

			)
		);

		$this->add_control(
			'no_results_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-search-wrap .hester-no-search-results p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'no_results_text_typo',
				'selector' => '{{WRAPPER}} .hester-search-wrap .hester-no-search-results p',

			)
		);
	}

	private static function get_post_types( $args = array() ) {
		$post_type_args = array(
			'show_in_nav_menus' => true,
		);

		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
		}

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types        = array();
		$post_types['any'] = esc_html__( 'Any', 'hester-core' );

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Admin ajax, put it in data so that it works in the edit mode of Elementor
		$ajax = admin_url( 'admin-ajax.php' );

		// If ajax
		$classes = 'hester-searchform';
		if ( 'yes' == $settings['enable_ajax'] ) {
			$classes .= ' hester-ajax-search';
		}

		// Placeholder
		$placeholder = '';
		if ( ! empty( $settings['placeholder'] ) ) {
			$placeholder = $settings['placeholder'];
		}

		$results_limit = isset( $settings['ajax_results_limit'] ) ? absint( $settings['ajax_results_limit'] ) : 5;
		if ( $results_limit < 1 ) {
			$results_limit = 5;
		}

		$ajax_nonce = wp_create_nonce( 'hester_nonce' );

		$source = ! empty( $settings['source'] ) ? $settings['source'] : 'any'; ?>

		<div class="hester-search-wrap" data-ajaxurl="<?php echo esc_url( $ajax ); ?>" data-results-limit="<?php echo esc_attr( $results_limit ); ?>" data-source="<?php echo esc_attr( $source ); ?>" data-nonce="<?php echo esc_attr( $ajax_nonce ); ?>">
			<form method="get" class="<?php echo esc_attr( $classes ); ?>" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" class="field" name="s" id="s" placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<button type="submit" class="search-submit" value=""><i class="fas fa-search"></i></button>
				<input type="hidden" class="post-type" name="post_type" value="<?php echo esc_attr( $source ); ?>">
			</form>
			<?php
			if ( 'yes' == $settings['enable_ajax'] ) {
				?>
				<div class="hester-ajax-loading"></div>
				<div class="hester-search-results"></div>
				<?php
			}
			?>
		</div>

		<?php
	}

	// No template because it cause a js error in the edit mode
}



