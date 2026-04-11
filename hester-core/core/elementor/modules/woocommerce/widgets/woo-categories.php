<?php
namespace Hester_Core\Elementor\Modules\Woocommerce\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Hester_Core\Elementor\Base\Base_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Categories extends Base_Widget {

	public function get_name() {
		return 'hester-woo-categories';
	}

	public function get_title() {
		return __( 'Woo - Categories', 'hester-core' );
	}

	public function get_icon() {

		return 'hester-icon eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_woo_categories',
			array(
				'label' => __( 'Products', 'hester-core' ),
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'1'  => '1',
					'2'  => '2',
					'3'  => '3',
					'4'  => '4',
					'5'  => '5',
					'6'  => '6',
					'7'  => '7',
					'8'  => '8',
					'9'  => '9',
					'10' => '10',
				),
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => __( 'Categories Count', 'hester-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			array(
				'label' => __( 'Query', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => _x( 'Source', 'Posts Query Control', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''          => __( 'Show All', 'hester-core' ),
					'by_id'     => __( 'Manual Selection', 'hester-core' ),
					'by_parent' => __( 'By Parent', 'hester-core' ),
				),
			)
		);

		$categories = get_terms( 'product_cat' );

		$options = array();
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			array(
				'label'       => __( 'Categories', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $options,
				'default'     => array(),
				'label_block' => true,
				'multiple'    => true,
				'condition'   => array(
					'source' => 'by_id',
				),
			)
		);

		$parent_options = array( '0' => __( 'Only Top Level', 'hester-core' ) ) + $options;
		$this->add_control(
			'parent',
			array(
				'label'     => __( 'Parent', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => $parent_options,
				'condition' => array(
					'source' => 'by_parent',
				),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => __( 'Hide Empty', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order by', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'        => __( 'Name', 'hester-core' ),
					'slug'        => __( 'Slug', 'hester-core' ),
					'description' => __( 'Description', 'hester-core' ),
					'count'       => __( 'Count', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => __( 'ASC', 'hester-core' ),
					'desc' => __( 'DESC', 'hester-core' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label' => __( 'Item', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce ul.products li.product',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product',
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'image_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce ul.products li.product img',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product img',
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Title', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product a:hover .woocommerce-loop-category__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_style',
			array(
				'label' => __( 'Description', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .description',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = array(
			'number'     => $settings['number'],
			'columns'    => $settings['columns'],
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
		);

		if ( 'by_id' === $settings['source'] ) {
			$attributes['ids'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$attributes['parent'] = $settings['parent'];
		}

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = sprintf( '[product_categories %s]', $this->get_render_attribute_string( 'shortcode' ) );

		return $shortcode;
	}

	public function render() {
		echo do_shortcode( $this->get_shortcode() );
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}
}
