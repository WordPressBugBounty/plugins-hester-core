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

class Woo_Slider extends Base_Widget {

	private $query            = null;
	private $rendering_slider = false;

	public function get_name() {
		return 'hester-woo-slider';
	}

	public function get_title() {
		return __( 'Woo - Carousel', 'hester-core' );
	}

	public function get_icon() {
		return 'hester-icon eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_script_depends() {
		return array( 'hester-woo-carousel', 'swiper' );
	}

	public function get_style_depends() {
		return array( 'hester-woo-carousel', 'swiper', 'hester-swiper-custom', 'font-awesome-5-all' );
	}

	public function get_query() {
		return $this->query;
	}

	public function add_swiper_product_classes( $classes ) {
		if ( $this->rendering_slider ) {
			$classes[] = 'swiper-slide';
		}

		return $classes;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_woo_slider',
			array(
				'label' => __( 'Slider', 'hester-core' ),
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'   => __( 'Arrows', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'   => __( 'Dots', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'          => __( 'Products To Display', 'hester-core' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'          => __( 'Products To Scroll', 'hester-core' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);
		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);
		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => __( 'Pause on Hover', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'        => __( 'Infinite Loop', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'transition_speed',
			array(
				'label'   => __( 'Transition Speed (ms)', 'hester-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->add_control(
			'spacing',
			array(
				'label'       => __( 'Spacing Between Slides (px)', 'hester-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'min'         => 0,
				'max'         => 100,
				'description' => __( 'Space between product items.', 'hester-core' ),
			)
		);

		$this->add_control(
			'auto_height',
			array(
				'label'        => __( 'Auto Height', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Adjust slider height automatically based on active product card.', 'hester-core' ),
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'   => __( 'Transition Effect', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide'     => __( 'Slide', 'hester-core' ),
					'fade'      => __( 'Fade', 'hester-core' ),
					'cube'      => __( 'Cube', 'hester-core' ),
					'coverflow' => __( 'Cover Flow', 'hester-core' ),
					'flip'      => __( 'Flip', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'free_mode',
			array(
				'label'        => __( 'Free Mode (Scroll)', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Enable free mode to allow continuous scrolling instead of sliding between fixed slides', 'hester-core' ),
			)
		);

		$this->add_control(
			'mouse_wheel',
			array(
				'label'        => __( 'Mouse Wheel Control', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'touch_swipe',
			array(
				'label'        => __( 'Touch Swipe', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'query_type',
			array(
				'label'   => __( 'Source', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all'    => __( 'All Products', 'hester-core' ),
					'custom' => __( 'Custom Query', 'hester-core' ),
					'manual' => __( 'Manual Selection', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'category_filter_rule',
			array(
				'label'     => __( 'Cat Filter Rule', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'IN',
				'options'   => array(
					'IN'     => __( 'Match Categories', 'hester-core' ),
					'NOT IN' => __( 'Exclude Categories', 'hester-core' ),
				),
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'category_filter',
			array(
				'label'     => __( 'Select Categories', 'hester-core' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => '',
				'options'   => $this->get_product_categories(),
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'tag_filter_rule',
			array(
				'label'     => __( 'Tag Filter Rule', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'IN',
				'options'   => array(
					'IN'     => __( 'Match Tags', 'hester-core' ),
					'NOT IN' => __( 'Exclude Tags', 'hester-core' ),
				),
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'tag_filter',
			array(
				'label'     => __( 'Select Tags', 'hester-core' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => '',
				'options'   => $this->get_product_tags(),
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'       => __( 'Offset', 'hester-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'description' => __( 'Number of post to displace or pass over.', 'hester-core' ),
				'condition'   => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'query_manual_ids',
			array(
				'label'     => __( 'Select Products', 'hester-core' ),
				'type'      => 'hester-query-posts',
				'post_type' => 'product',
				'multiple'  => true,
				'condition' => array(
					'query_type' => 'manual',
				),
			)
		);

		$this->add_control(
			'query_exclude',
			array(
				'label'     => __( 'Exclude', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'query_type!' => 'manual',
				),
			)
		);

		$this->add_control(
			'query_exclude_ids',
			array(
				'label'       => __( 'Select Products', 'hester-core' ),
				'type'        => 'hester-query-posts',
				'post_type'   => 'product',
				'multiple'    => true,
				'description' => __( 'Select products to exclude from the query.', 'hester-core' ),
				'condition'   => array(
					'query_type!' => 'manual',
				),
			)
		);

		$this->add_control(
			'query_exclude_current',
			array(
				'label'        => __( 'Exclude Current Product', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'hester-core' ),
				'label_off'    => __( 'No', 'hester-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Enable this option to remove current product from the query.', 'hester-core' ),
				'condition'    => array(
					'query_type!' => 'manual',
				),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Products Count', 'hester-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '8',
			)
		);

		$this->add_control(
			'advanced',
			array(
				'label' => __( 'Advanced', 'hester-core' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'filter_by',
			array(
				'label'   => __( 'Filter By', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''         => __( 'None', 'hester-core' ),
					'featured' => __( 'Featured', 'hester-core' ),
					'sale'     => __( 'Sale', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order by', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Date', 'hester-core' ),
					'title'      => __( 'Title', 'hester-core' ),
					'price'      => __( 'Price', 'hester-core' ),
					'popularity' => __( 'Popularity', 'hester-core' ),
					'rating'     => __( 'Rating', 'hester-core' ),
					'rand'       => __( 'Random', 'hester-core' ),
					'menu_order' => __( 'Menu Order', 'hester-core' ),
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
			'section_arrows_style',
			array(
				'label' => __( 'Arrows', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-woo-carousel .swiper-button-prev, {{WRAPPER}} .hester-woo-carousel .swiper-button-next' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'arrows_bg',
			array(
				'label'     => __( 'Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-woo-carousel .swiper-button-prev, {{WRAPPER}} .hester-woo-carousel .swiper-button-next' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_hover_color',
			array(
				'label'     => __( 'Color: Hover', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-woo-carousel .swiper-button-prev:hover, {{WRAPPER}} .hester-woo-carousel .swiper-button-next:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'arrows_hover_bg',
			array(
				'label'     => __( 'Background: Hover', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-woo-carousel .swiper-button-prev:hover, {{WRAPPER}} .hester-woo-carousel .swiper-button-next:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .hester-woo-carousel .swiper-slide' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'    => '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) > a img:not(.secondary-image)',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) > a img:not(.secondary-image), {{WRAPPER}} .woocommerce ul.products li.product .woo-entry-inner li.image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; position: relative; overflow: hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) > a img:not(.secondary-image)',
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) > a img:not(.secondary-image)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'category_heading',
			array(
				'label' => __( 'Category', 'hester-core' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product li.category a, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a',
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product li.category a, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product li.category a:hover, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product li.category, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_heading',
			array(
				'label'     => __( 'Price', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .price, {{WRAPPER}} .woocommerce ul.products li.product .price .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .price, {{WRAPPER}} .woocommerce ul.products li.product .price .amount',
			)
		);

		$this->add_control(
			'del_price_color',
			array(
				'label'     => esc_html__( 'Del Price Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .price del .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'del_price_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .price del .amount',
			)
		);

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rating_heading',
			array(
				'label'     => __( 'Rating', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rating_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .star-rating span::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rating_fill_color',
			array(
				'label'     => esc_html__( 'Fill Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .star-rating::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',

				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .button',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button' => 'color: {{VALUE}};',
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
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce ul.products li.product .button',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_badge_style',
			array(
				'label' => __( 'Badge', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',

				'selector' => '{{WRAPPER}} .woocommerce span.onsale',
			)
		);

		$this->add_control(
			'badge_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce span.onsale' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce span.onsale' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'badge_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .woocommerce span.onsale',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce span.onsale',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'badge_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce span.onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_product_categories() {

		$product_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$product_categories = get_terms( 'product_cat', $cat_args );

		if ( ! empty( $product_categories ) ) {
			foreach ( $product_categories as $key => $category ) {
				$product_cat[ $category->slug ] = $category->name;
			}
		}

		return $product_cat;
	}

	protected function get_product_tags() {

		$product_tag = array();

		$tag_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$product_tag = get_terms( 'product_tag', $tag_args );

		if ( ! empty( $product_tag ) ) {
			foreach ( $product_tag as $key => $tag ) {
				$product_tag[ $tag->slug ] = $tag->name;
			}
		}

		return $product_tag;
	}

	public function query_posts() {
		$settings = $this->get_settings();

		global $post;

		$query_args = array(
			'post_type'      => 'product',
			'posts_per_page' => $settings['posts_per_page'],
			'post__not_in'   => array(),
		);

		// Default ordering args.
		$ordering_args = WC()->query->get_catalog_ordering_args( $settings['orderby'], $settings['order'] );

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order']   = $ordering_args['order'];

		if ( 'sale' === $settings['filter_by'] ) {
			$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		} elseif ( 'featured' === $settings['filter_by'] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['featured'],
			);
		}

		if ( 'custom' === $settings['query_type'] ) {
			if ( ! empty( $settings['category_filter'] ) ) {
				$cat_operator = $settings['category_filter_rule'];

				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['category_filter'],
					'operator' => $cat_operator,
				);
			}

			if ( ! empty( $settings['tag_filter'] ) ) {
				$tag_operator = $settings['tag_filter_rule'];

				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'field'    => 'slug',
					'terms'    => $settings['tag_filter'],
					'operator' => $tag_operator,
				);
			}

			if ( 0 < $settings['offset'] ) {
				$query_args['offset_to_fix'] = $settings['offset'];
			}
		}

		if ( 'manual' === $settings['query_type'] ) {
			$manual_ids             = $settings['query_manual_ids'] ?? array();
			$query_args['post__in'] = $manual_ids;
		}

		if ( 'manual' !== $settings['query_type'] ) {
			if ( ! empty( $settings['query_exclude_ids'] ) ) {
				$exclude_ids                = $settings['query_exclude_ids'];
				$query_args['post__not_in'] = $exclude_ids;
			}

			if ( 'yes' === $settings['query_exclude_current'] ) {
				$query_args['post__not_in'][] = $post->ID;
			}
		}

		$this->query = new \WP_Query( $query_args );
	}

	public function render() {
		$settings = $this->get_settings();

		$this->query_posts();

		$query = $this->get_query();

		if ( ! $query->have_posts() ) {
			return;
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = (int) $settings['slides_to_show'];

		// Arrows
		$arrows = $settings['arrows'];
		if ( 'yes' == $arrows ) {
			$show_arrows = true;
		} else {
			$show_arrows = false;
		}

		// Dots
		$dots = $settings['dots'];
		if ( 'yes' == $dots ) {
			$show_dots = true;
		} else {
			$show_dots = false;
		}

		// RTL
		if ( is_rtl() ) {
			$is_rtl = true;
		} else {
			$is_rtl = false;
		}

		$slides_tablet = isset( $settings['slides_to_show_tablet'] ) ? $settings['slides_to_show_tablet'] : '2';
		$slides_mobile = isset( $settings['slides_to_show_mobile'] ) ? $settings['slides_to_show_mobile'] : '1';

		$slides_scroll_tablet = isset( $settings['slides_to_scroll_tablet'] ) ? $settings['slides_to_scroll_tablet'] : '2';
		$slides_scroll_mobile = isset( $settings['slides_to_scroll_mobile'] ) ? $settings['slides_to_scroll_mobile'] : '1';

		// Get spacing value (default 20px)
		$spacing = isset( $settings['spacing'] ) ? absint( $settings['spacing'] ) : 20;

		// Data settings for Swiper
		$swiper_options = array(
			'slidesToShow'   => ( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 4,
			'slidesToScroll' => ( $settings['slides_to_scroll'] ) ? absint( $settings['slides_to_scroll'] ) : 4,
			'autoplay'       => ( 'yes' === $settings['autoplay'] ),
			'autoplaySpeed'  => isset( $settings['autoplay_speed'] ) ? absint( $settings['autoplay_speed'] ) : 5000,
			'infinite'       => ( 'yes' === $settings['infinite'] ),
			'pauseOnHover'   => ( 'yes' === $settings['pause_on_hover'] ),
			'speed'          => ( $settings['transition_speed'] ) ? absint( $settings['transition_speed'] ) : 500,
			'arrows'         => $show_arrows,
			'dots'           => $show_dots,
			'rtl'            => $is_rtl,
			'spacing'        => $spacing,
			'autoHeight'     => ( 'yes' === ( $settings['auto_height'] ?? '' ) ) ? true : false,
			'effect'         => isset( $settings['effect'] ) ? sanitize_text_field( $settings['effect'] ) : 'slide',
			'freeMode'       => ( 'yes' === ( $settings['free_mode'] ?? '' ) ) ? true : false,
			'mouseWheel'     => ( 'yes' === ( $settings['mouse_wheel'] ?? '' ) ) ? true : false,
			'touchSwipe'     => ( 'yes' === ( $settings['touch_swipe'] ?? 'yes' ) ) ? true : false,
		);

		$swiper_options['slidesToShowTablet']   = $slides_tablet ? absint( $slides_tablet ) : 2;
		$swiper_options['slidesToShowMobile']   = $slides_mobile ? absint( $slides_mobile ) : 1;
		$swiper_options['slidesToScrollTablet'] = $slides_scroll_tablet ? absint( $slides_scroll_tablet ) : $swiper_options['slidesToShowTablet'];
		$swiper_options['slidesToScrollMobile'] = $slides_scroll_mobile ? absint( $slides_scroll_mobile ) : $swiper_options['slidesToShowMobile'];

		$this->add_render_attribute( 'carousel', 'class', 'hester-woo-carousel' );
		$this->add_render_attribute( 'carousel', 'class', 'woocommerce' );
		$this->add_render_attribute( 'carousel', 'class', 'columns-' . esc_attr( $woocommerce_loop['columns'] ) );
		$this->add_render_attribute( 'carousel', 'data-settings', wp_json_encode( $swiper_options ) );

		echo '<div ' . $this->get_render_attribute_string( 'carousel' ) . '>';

			// Swiper wrapper
			echo '<div class="swiper">';
				$this->rendering_slider = true;
				add_filter( 'woocommerce_post_class', array( $this, 'add_swiper_product_classes' ), 20, 1 );

				ob_start();
				woocommerce_product_loop_start();
				$loop_start = ob_get_clean();
				$loop_start = preg_replace(
					'/class=("|\')([^"\']*\bproducts\b[^"\']*)("|\')/i',
					'class=$1$2 swiper-wrapper$3',
					$loop_start,
					1
				);

		if ( false === strpos( $loop_start, 'swiper-wrapper' ) ) {
			$loop_start = preg_replace( '/<ul\b/i', '<ul class="products swiper-wrapper"', $loop_start, 1 );
		}

				echo $loop_start;

		while ( $query->have_posts() ) :
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
				endwhile;

				woocommerce_product_loop_end();

				remove_filter( 'woocommerce_post_class', array( $this, 'add_swiper_product_classes' ), 20 );
				$this->rendering_slider = false;

				// Navigation arrows
		if ( $show_arrows ) {
			echo '<div class="swiper-button-prev"></div>';
			echo '<div class="swiper-button-next"></div>';
		}

				// Pagination dots
		if ( $show_dots ) {
			echo '<div class="swiper-pagination"></div>';
		}
			echo '</div>';

		echo '</div>';

		woocommerce_reset_loop();

		wp_reset_postdata();
	}
}
