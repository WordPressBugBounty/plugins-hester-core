<?php
/**
 * Pricing Widget
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Pricing\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Hester_Core\Elementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pricing table widget.
 */
class Pricing extends Base_Widget {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-pricing';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Pricing', 'hester-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'hester-icon eicon-price-table';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'hester-core' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'hester-pricing' );
	}

	/**
	 * Dynamic content flag.
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content tab controls.
	 *
	 * @return void
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'section_header',
			array(
				'label' => esc_html__( 'Header', 'hester-core' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => esc_html__( 'Title', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Starter Plan', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'   => esc_html__( 'Subtitle', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Best for individuals', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'default'   => 'h3',
				'condition' => array(
					'heading!' => '',
				),
			)
		);

		$this->add_control(
			'is_featured',
			array(
				'label'        => esc_html__( 'Featured / Highlight', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hester-core' ),
				'label_off'    => esc_html__( 'No', 'hester-core' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing',
			array(
				'label' => esc_html__( 'Pricing', 'hester-core' ),
			)
		);

		$this->add_control(
			'currency_symbol',
			array(
				'label'   => esc_html__( 'Currency Symbol', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''             => esc_html__( 'None', 'hester-core' ),
					'dollar'       => '$ ' . esc_html__( 'Dollar', 'hester-core' ),
					'euro'         => '€ ' . esc_html__( 'Euro', 'hester-core' ),
					'baht'         => '฿ ' . esc_html__( 'Baht', 'hester-core' ),
					'franc'        => '₣ ' . esc_html__( 'Franc', 'hester-core' ),
					'guilder'      => 'ƒ ' . esc_html__( 'Guilder', 'hester-core' ),
					'krona'        => 'kr ' . esc_html__( 'Krona', 'hester-core' ),
					'lira'         => '₤ ' . esc_html__( 'Lira', 'hester-core' ),
					'peseta'       => '₧ ' . esc_html__( 'Peseta', 'hester-core' ),
					'peso'         => '₱ ' . esc_html__( 'Peso', 'hester-core' ),
					'pound'        => '£ ' . esc_html__( 'Pound Sterling', 'hester-core' ),
					'real'         => 'R$ ' . esc_html__( 'Real', 'hester-core' ),
					'ruble'        => '₽ ' . esc_html__( 'Ruble', 'hester-core' ),
					'rupee'        => '₨ ' . esc_html__( 'Rupee', 'hester-core' ),
					'indian_rupee' => '₹ ' . esc_html__( 'Rupee (Indian)', 'hester-core' ),
					'shekel'       => '₪ ' . esc_html__( 'Shekel', 'hester-core' ),
					'yen'          => '¥ ' . esc_html__( 'Yen / Yuan', 'hester-core' ),
					'won'          => '₩ ' . esc_html__( 'Won', 'hester-core' ),
					'custom'       => esc_html__( 'Custom', 'hester-core' ),
				),
				'default' => 'dollar',
			)
		);

		$this->add_control(
			'currency_symbol_custom',
			array(
				'label'     => esc_html__( 'Custom Symbol', 'hester-core' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'currency_symbol' => 'custom',
				),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '39.99',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'currency_format',
			array(
				'label'   => esc_html__( 'Currency Format', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => '1,234.56 (Default)',
					',' => '1.234,56',
				),
			)
		);

		$this->add_control(
			'sale',
			array(
				'label'     => esc_html__( 'Sale', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'hester-core' ),
				'label_off' => esc_html__( 'Off', 'hester-core' ),
				'default'   => '',
			)
		);

		$this->add_control(
			'original_price',
			array(
				'label'     => esc_html__( 'Original Price', 'hester-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '59',
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'sale' => 'yes',
				),
			)
		);

		$this->add_control(
			'period',
			array(
				'label'   => esc_html__( 'Duration / Period', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monthly', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			array(
				'label' => esc_html__( 'Features', 'hester-core' ),
			)
		);

		$default_icon = array(
			'value'   => 'far fa-check-square',
			'library' => 'fa-regular',
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Text', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'List Item', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'selected_icon',
			array(
				'label'   => esc_html__( 'Icon', 'hester-core' ),
				'type'    => Controls_Manager::ICONS,
				'default' => $default_icon,
			)
		);

		$repeater->add_control(
			'item_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'features_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_text'     => esc_html__( 'Feature #1', 'hester-core' ),
						'selected_icon' => $default_icon,
					),
					array(
						'item_text'     => esc_html__( 'Feature #2', 'hester-core' ),
						'selected_icon' => $default_icon,
					),
					array(
						'item_text'     => esc_html__( 'Feature #3', 'hester-core' ),
						'selected_icon' => $default_icon,
					),
				),
				'title_field' => '{{{ item_text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer',
			array(
				'label' => esc_html__( 'Footer', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'hester-core' ),
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '#' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'     => esc_html__( 'Button Icon', 'hester-core' ),
				'type'      => Controls_Manager::ICONS,
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'hester-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'before' => array(
						'title' => esc_html__( 'Before', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => esc_html__( 'After', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'after',
				'condition' => array(
					'button_text!'        => '',
					'button_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'footer_additional_info',
			array(
				'label'     => esc_html__( 'Additional Info', 'hester-core' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'No setup fee. Cancel anytime.', 'hester-core' ),
				'rows'      => 3,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			array(
				'label' => esc_html__( 'Ribbon', 'hester-core' ),
			)
		);

		$this->add_control(
			'show_ribbon',
			array(
				'label'        => esc_html__( 'Show', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ribbon_title',
			array(
				'label'     => esc_html__( 'Title', 'hester-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Popular', 'hester-core' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_horizontal_position',
			array(
				'label'     => esc_html__( 'Position', 'hester-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab controls.
	 *
	 * @return void
	 */
	private function register_style_controls() {
		$this->start_controls_section(
			'section_featured_style',
			array(
				'label'     => esc_html__( 'Featured / Highlight', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'is_featured' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table--featured' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'featured_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table--featured' => 'border-color: {{VALUE}}; border-style: solid;',
				),
			)
		);

		$this->add_responsive_control(
			'featured_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 2,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table--featured' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'featured_box_shadow',
				'selector' => '{{WRAPPER}} .hester-price-table--featured',
			)
		);

		$this->add_responsive_control(
			'featured_translate_y',
			array(
				'label'      => esc_html__( 'Vertical Offset', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table--featured' => 'transform: translateY({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			array(
				'label'      => esc_html__( 'Header', 'hester-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'heading',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'sub_heading',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__header' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_heading_style',
			array(
				'label'     => esc_html__( 'Title', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'heading!' => '',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'heading!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'heading_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__heading',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'condition' => array(
					'heading!' => '',
				),
			)
		);

		$this->add_control(
			'heading_sub_heading_style',
			array(
				'label'     => esc_html__( 'Sub Title', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->add_control(
			'sub_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'sub_heading!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__subheading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sub_heading_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__subheading',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'condition' => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing_element_style',
			array(
				'label' => esc_html__( 'Pricing', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'pricing_element_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__price' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'pricing_element_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__currency, {{WRAPPER}} .hester-price-table__integer-part, {{WRAPPER}} .hester-price-table__fractional-part' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .hester-price-table__price',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_control(
			'heading_currency_style',
			array(
				'label'     => esc_html__( 'Currency Symbol', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_size',
			array(
				'label'     => esc_html__( 'Size', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'condition' => array(
					'currency_symbol!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__price > .hester-price-table__currency' => 'font-size: calc({{SIZE}}em/100)',
				),
			)
		);

		$this->add_control(
			'currency_position',
			array(
				'label'   => esc_html__( 'Position', 'hester-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => array(
					'before' => array(
						'title' => esc_html__( 'Before', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => esc_html__( 'After', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->add_control(
			'currency_vertical_position',
			array(
				'label'                => esc_html__( 'Vertical Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'hester-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'hester-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'hester-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-price-table__currency' => 'align-self: {{VALUE}}',
				),
				'condition'            => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'fractional_part_style',
			array(
				'label'     => esc_html__( 'Fractional Part', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'fractional_part_size',
			array(
				'label'     => esc_html__( 'Size', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__fractional-part' => 'font-size: calc({{SIZE}}em/100)',
				),
			)
		);

		$this->add_control(
			'fractional_part_vertical_position',
			array(
				'label'                => esc_html__( 'Vertical Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'hester-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'hester-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'hester-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-price-table__after-price' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_original_price_style',
			array(
				'label'     => esc_html__( 'Original Price', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'original_price_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__original-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'original_price_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__original-price',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'original_price_vertical_position',
			array(
				'label'                => esc_html__( 'Vertical Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'hester-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'hester-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'hester-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'bottom',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-price-table__original-price' => 'align-self: {{VALUE}}',
				),
				'condition'            => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'heading_period_style',
			array(
				'label'     => esc_html__( 'Period', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->add_control(
			'period_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'condition' => array(
					'period!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__period' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'period_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__period',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->add_control(
			'period_position',
			array(
				'label'     => esc_html__( 'Position', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'below'  => esc_html__( 'Below', 'hester-core' ),
					'beside' => esc_html__( 'Beside', 'hester-core' ),
				),
				'default'   => 'below',
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features_list_style',
			array(
				'label' => esc_html__( 'Features', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'features_list_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__features-list' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'features_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'features_list_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__features-list' => '--hester-price-table-features-list-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'features_list_typography',
				'selector' => '{{WRAPPER}} .hester-price-table__features-list li',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'features_list_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'hester-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'hester-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'End', 'hester-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'classes'              => 'elementor-control-start-end',
				'selectors_dictionary' => array(
					'left'  => is_rtl() ? 'end' : 'start',
					'right' => is_rtl() ? 'start' : 'end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-price-table__features-list' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'item_width',
			array(
				'label'     => esc_html__( 'Width', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'min' => 25,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__feature-inner' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				),
			)
		);

		$this->add_control(
			'list_divider',
			array(
				'label'   => esc_html__( 'Divider', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => esc_html__( 'Style', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'hester-core' ),
					'double' => esc_html__( 'Double', 'hester-core' ),
					'dotted' => esc_html__( 'Dotted', 'hester-core' ),
					'dashed' => esc_html__( 'Dashed', 'hester-core' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__features-list li:before' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dddddd',
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__features-list li:before' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 2,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'condition'  => array(
					'list_divider' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__features-list li:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'divider_width',
			array(
				'label'     => esc_html__( 'Width', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__features-list li:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				),
			)
		);

		$this->add_control(
			'divider_gap',
			array(
				'label'      => esc_html__( 'Gap', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 15,
				),
				'condition'  => array(
					'list_divider' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__features-list li:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			array(
				'label'     => esc_html__( 'Footer', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'footer_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__footer' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'footer_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'condition'  => array(
					'button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_footer_button',
			array(
				'label'     => esc_html__( 'Button', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => esc_html__( 'Size', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => esc_html__( 'Extra Small', 'hester-core' ),
					'sm' => esc_html__( 'Small', 'hester-core' ),
					'md' => esc_html__( 'Medium', 'hester-core' ),
					'lg' => esc_html__( 'Large', 'hester-core' ),
					'xl' => esc_html__( 'Extra Large', 'hester-core' ),
				),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'condition'  => array(
					'button_text!'        => '',
					'button_icon[value]!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__button .hester-price-table__button-icon--before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hester-price-table__button .hester-price-table__button-icon--after' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'hester-core' ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__button',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .hester-price-table__button',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
				'condition'      => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .hester-price-table__button',
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'condition'  => array(
					'button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => esc_html__( 'Text Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'condition'  => array(
					'button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'hester-core' ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'button_background_hover',
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .hester-price-table__button:hover',
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label'     => esc_html__( 'Animation', 'hester-core' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'heading_additional_info',
			array(
				'label'      => esc_html__( 'Additional Info', 'hester-core' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_text',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'footer_additional_info',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'additional_info_color',
			array(
				'label'      => esc_html__( 'Color', 'hester-core' ),
				'type'       => Controls_Manager::COLOR,
				'global'     => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__additional_info' => 'color: {{VALUE}}',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_text',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'footer_additional_info',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'additional_info_typography',
				'selector'   => '{{WRAPPER}} .hester-price-table__additional_info',
				'global'     => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_text',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'footer_additional_info',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'additional_info_margin',
			array(
				'label'      => esc_html__( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 30,
					'bottom' => 0,
					'left'   => 30,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__additional_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'button_text',
							'operator' => '!==',
							'value'    => '',
						),
						array(
							'name'     => 'footer_additional_info',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			array(
				'label'     => esc_html__( 'Ribbon', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__ribbon-inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			array(
				'label'      => esc_html__( 'Distance', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array(
						'max' => 50,
					),
					'em'  => array(
						'max' => 5,
					),
					'rem' => array(
						'max' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-price-table__ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
				'condition'  => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .hester-price-table__ribbon-inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'ribbon_typography',
				'selector'  => '{{WRAPPER}} .hester-price-table__ribbon-inner',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'ribbon_box_shadow',
				'selector'  => '{{WRAPPER}} .hester-price-table__ribbon-inner',
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get currency symbol.
	 *
	 * @param string $symbol_name Symbol key.
	 * @return string
	 */
	private function get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'       => '$',
			'euro'         => '€',
			'baht'         => '฿',
			'franc'        => '₣',
			'guilder'      => 'ƒ',
			'krona'        => 'kr',
			'lira'         => '₤',
			'peseta'       => '₧',
			'peso'         => '₱',
			'pound'        => '£',
			'real'         => 'R$',
			'ruble'        => '₽',
			'rupee'        => '₨',
			'indian_rupee' => '₹',
			'shekel'       => '₪',
			'yen'          => '¥',
			'won'          => '₩',
		);

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	/**
	 * Sanitize plain text.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	private function sanitize_plain_text( $value ) {
		return sanitize_text_field( (string) $value );
	}

	/**
	 * Sanitize rich text.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	private function sanitize_rich_text( $value ) {
		return wp_kses_post( (string) $value );
	}

	/**
	 * Sanitize price-like text.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	private function sanitize_price_value( $value ) {
		$value = sanitize_text_field( (string) $value );

		return preg_replace( '/[^0-9,\.\-\s]/', '', $value );
	}

	/**
	 * Render currency symbol.
	 *
	 * @param string $symbol Symbol.
	 * @param string $location Location.
	 * @return void
	 */
	private function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->get_settings( 'currency_position' );
		$location_setting  = ! empty( $currency_position ) ? $currency_position : 'before';

		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo '<span class="hester-price-table__currency">' . esc_html( $symbol ) . '</span>';
		}
	}

	/**
	 * Get rendered icon markup with static cache.
	 *
	 * @param array<string, mixed> $icon Icon settings.
	 * @return string
	 */
	private function get_icon_markup( array $icon ) {
		static $cache = array();

		$cache_key = md5( wp_json_encode( $icon ) );

		if ( isset( $cache[ $cache_key ] ) ) {
			return $cache[ $cache_key ];
		}

		if ( empty( $icon['value'] ) ) {
			$cache[ $cache_key ] = '';
			return '';
		}

		ob_start();
		Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		$cache[ $cache_key ] = (string) ob_get_clean();

		return $cache[ $cache_key ];
	}

	/**
	 * Prepare repeater features for output.
	 *
	 * @param mixed $features Raw features list.
	 * @return array<int, array<string, string>>
	 */
	private function prepare_feature_items( $features ) {
		if ( ! is_array( $features ) ) {
			return array();
		}

		$items = array();

		foreach ( $features as $feature ) {
			if ( ! is_array( $feature ) ) {
				continue;
			}

			$icon_html = '';

			$icon_settings = array();

			if ( ! empty( $feature['selected_icon'] ) && is_array( $feature['selected_icon'] ) ) {
				$icon_settings = $feature['selected_icon'];
			} elseif ( ! empty( $feature['selected_item_icon'] ) && is_array( $feature['selected_item_icon'] ) ) {
				$icon_settings = $feature['selected_item_icon'];
			}

			if ( ! empty( $icon_settings ) ) {
				$icon_html = $this->get_icon_markup( $icon_settings );
			}

			$items[] = array(
				'id'        => ! empty( $feature['_id'] ) ? sanitize_html_class( (string) $feature['_id'] ) : '',
				'text'      => isset( $feature['item_text'] ) ? $this->sanitize_rich_text( $feature['item_text'] ) : '',
				'icon_html' => $icon_html,
			);
		}

		return $items;
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$heading         = isset( $settings['heading'] ) ? $this->sanitize_rich_text( $settings['heading'] ) : '';
		$sub_heading     = isset( $settings['sub_heading'] ) ? $this->sanitize_rich_text( $settings['sub_heading'] ) : '';
		$period_text     = isset( $settings['period'] ) ? $this->sanitize_rich_text( $settings['period'] ) : '';
		$button_text     = isset( $settings['button_text'] ) ? $this->sanitize_plain_text( $settings['button_text'] ) : '';
		$additional_info = isset( $settings['footer_additional_info'] ) ? $this->sanitize_rich_text( $settings['footer_additional_info'] ) : '';
		$ribbon_title    = isset( $settings['ribbon_title'] ) ? $this->sanitize_rich_text( $settings['ribbon_title'] ) : '';
		$original_price  = isset( $settings['original_price'] ) ? $this->sanitize_price_value( $settings['original_price'] ) : '';
		$features        = $this->prepare_feature_items( $settings['features_list'] ?? array() );

		$symbol = '';
		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = isset( $settings['currency_symbol_custom'] ) ? $this->sanitize_plain_text( $settings['currency_symbol_custom'] ) : '';
			}
		}

		$currency_format   = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price_parts       = explode( $currency_format, $this->sanitize_price_value( $settings['price'] ?? '' ) );
		$intpart           = isset( $price_parts[0] ) ? $price_parts[0] : '';
		$fraction          = isset( $price_parts[1] ) ? $price_parts[1] : '';
		$period_position   = ! empty( $settings['period_position'] ) ? sanitize_key( $settings['period_position'] ) : 'below';
		$button_icon_align = ! empty( $settings['button_icon_align'] ) ? sanitize_key( $settings['button_icon_align'] ) : 'after';
		$heading_tag       = Utils::validate_html_tag( $settings['heading_tag'] ?? 'h3' );
		$button_tag        = ! empty( $settings['link']['url'] ) ? 'a' : 'span';

		$this->add_render_attribute(
			'wrapper',
			'class',
			array(
				'hester-price-table',
			)
		);

		if ( 'yes' === ( $settings['is_featured'] ?? '' ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'hester-price-table--featured' );
		}

		if ( 'yes' === ( $settings['show_ribbon'] ?? '' ) && '' !== $ribbon_title ) {
			$this->add_render_attribute( 'wrapper', 'class', 'hester-price-table--has-ribbon' );
		}

		$this->add_render_attribute(
			'button',
			'class',
			array(
				'hester-price-table__button',
				'elementor-button',
				'elementor-size-' . sanitize_html_class( $settings['button_size'] ?? 'md' ),
			)
		);

		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . sanitize_html_class( $settings['button_hover_animation'] ) );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link'] );
		}

		$this->add_render_attribute( 'heading', 'class', 'hester-price-table__heading' );
		$this->add_render_attribute( 'sub_heading', 'class', 'hester-price-table__subheading' );
		$this->add_render_attribute( 'period', 'class', array( 'hester-price-table__period', 'elementor-typo-excluded' ) );
		$this->add_render_attribute( 'footer_additional_info', 'class', 'hester-price-table__additional_info' );
		$this->add_render_attribute( 'ribbon_title', 'class', 'hester-price-table__ribbon-inner' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );
		$this->add_inline_editing_attributes( 'footer_additional_info' );

		$button_icon_html = '';
		if ( ! empty( $settings['button_icon'] ) && is_array( $settings['button_icon'] ) ) {
			$button_icon_html = $this->get_icon_markup( $settings['button_icon'] );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( '' !== $heading || '' !== $sub_heading ) : ?>
				<div class="hester-price-table__header">
					<?php if ( '' !== $heading ) : ?>
						<<?php Utils::print_validated_html_tag( $heading_tag ); ?> <?php $this->print_render_attribute_string( 'heading' ); ?>>
							<?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</<?php Utils::print_validated_html_tag( $heading_tag ); ?>>
					<?php endif; ?>
					<?php if ( '' !== $sub_heading ) : ?>
						<span <?php $this->print_render_attribute_string( 'sub_heading' ); ?>>
							<?php echo $sub_heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="hester-price-table__price">
				<?php if ( 'yes' === ( $settings['sale'] ?? '' ) && '' !== $original_price ) : ?>
					<div class="hester-price-table__original-price elementor-typo-excluded">
						<?php
						$this->render_currency_symbol( $symbol, 'before' );
						echo esc_html( $original_price );
						$this->render_currency_symbol( $symbol, 'after' );
						?>
					</div>
				<?php endif; ?>

				<?php $this->render_currency_symbol( $symbol, 'before' ); ?>

				<?php if ( '' !== $intpart ) : ?>
					<span class="hester-price-table__integer-part"><?php echo esc_html( $intpart ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== $fraction || ( '' !== $period_text && 'beside' === $period_position ) ) : ?>
					<div class="hester-price-table__after-price">
						<?php if ( '' !== $fraction ) : ?>
							<span class="hester-price-table__fractional-part"><?php echo esc_html( $fraction ); ?></span>
						<?php endif; ?>
						<?php if ( '' !== $period_text && 'beside' === $period_position ) : ?>
							<span <?php $this->print_render_attribute_string( 'period' ); ?>><?php echo $period_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php $this->render_currency_symbol( $symbol, 'after' ); ?>

				<?php if ( '' !== $period_text && 'below' === $period_position ) : ?>
					<span <?php $this->print_render_attribute_string( 'period' ); ?>><?php echo $period_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $features ) ) : ?>
				<ul class="hester-price-table__features-list">
					<?php foreach ( $features as $feature ) : ?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $feature['id'] ); ?> hester-price-table__feature-item">
							<div class="hester-price-table__feature-inner">
								<?php if ( '' !== $feature['icon_html'] ) : ?>
									<span class="hester-price-table__feature-icon"><?php echo $feature['icon_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<?php endif; ?>
								<?php if ( '' !== $feature['text'] ) : ?>
									<span class="hester-price-table__feature-text"><?php echo $feature['text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<?php else : ?>
									&nbsp;
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( '' !== $button_text || '' !== $additional_info ) : ?>
				<div class="hester-price-table__footer">
					<?php if ( '' !== $button_text ) : ?>
						<<?php echo esc_html( $button_tag ); ?> <?php $this->print_render_attribute_string( 'button' ); ?>>
							<?php if ( '' !== $button_icon_html && 'before' === $button_icon_align ) : ?>
								<span class="hester-price-table__button-icon hester-price-table__button-icon--before"><?php echo $button_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
							<span class="hester-price-table__button-text"><?php echo esc_html( $button_text ); ?></span>
							<?php if ( '' !== $button_icon_html && 'after' === $button_icon_align ) : ?>
								<span class="hester-price-table__button-icon hester-price-table__button-icon--after"><?php echo $button_icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
						</<?php echo esc_html( $button_tag ); ?>>
					<?php endif; ?>

					<?php if ( '' !== $additional_info ) : ?>
						<div <?php $this->print_render_attribute_string( 'footer_additional_info' ); ?>>
							<?php echo $additional_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( 'yes' === ( $settings['show_ribbon'] ?? '' ) && '' !== $ribbon_title ) : ?>
			<?php
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'hester-price-table__ribbon' );
			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) {
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'hester-price-table__ribbon--' . sanitize_html_class( $settings['ribbon_horizontal_position'] ) );
			}
			$this->add_inline_editing_attributes( 'ribbon_title' );
			?>
			<div <?php $this->print_render_attribute_string( 'ribbon-wrapper' ); ?>>
				<div <?php $this->print_render_attribute_string( 'ribbon_title' ); ?>><?php echo $ribbon_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
		<?php endif; ?>
		<?php
	}
}

