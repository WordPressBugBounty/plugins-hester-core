<?php

namespace Hester_Core\Elementor\Modules\Woocommerce\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Hester_Core\Elementor\Base\Base_Widget;
use Hester_Core\Elementor\Modules\QueryPost\Module;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Add_To_Cart extends Base_Widget {

	public function get_name() {
		return 'hester-woo-add-to-cart';
	}

	public function get_title() {
		return __( 'Woo - Add To Cart', 'hester-core' );
	}

	public function get_icon() {

		return 'hester-icon eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_style_depends() {
		return array( 'hester-woo-addtocart' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_woo_product',
			array(
				'label' => __( 'Product', 'hester-core' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'     => __( 'Select Product', 'hester-core' ),
				'type'      => 'hester-query-posts',
				'post_type' => 'product',
			)
		);

		$this->add_control(
			'quantity',
			array(
				'label'   => __( 'Quantity', 'hester-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'hester-core' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Add To Cart', 'hester-core' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'hester-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'hester-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'hester-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'hester-core' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'hester-core' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'      => '',
				'prefix_class' => 'wew%s-align-',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'hester-core' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-shopping-basket',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => __( 'Icon Position', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Before', 'hester-core' ),
					'right' => __( 'After', 'hester-core' ),
				),
				'condition' => array(
					'icon!' => '',
				),
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'     => __( 'Icon Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 4,
				),
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'condition' => array(
					'icon!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hester-addtocart .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
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

				'selector' => '{{WRAPPER}} .hester-addtocart',
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
					'{{WRAPPER}} .hester-addtocart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .hester-addtocart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart:hover' => 'border-color: {{VALUE}};',
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
				'selector'    => '{{WRAPPER}} .hester-addtocart',
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
					'{{WRAPPER}} .hester-addtocart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .hester-addtocart',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-addtocart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .hester-addtocart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_view_cart_style',
			array(
				'label' => __( 'View Cart Text', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_cart_typography',
				'selector' => '{{WRAPPER}} .hester-addtocart-wrap .added_to_cart',
			)
		);

		$this->start_controls_tabs( 'tabs_view_cart_style' );

		$this->start_controls_tab(
			'tab_view_cart_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'view_cart_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_view_cart_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'view_cart_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_hover_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'view_cart_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .hester-addtocart-wrap .added_to_cart',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'view_cart_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'view_cart_box_shadow',
				'selector' => '{{WRAPPER}} .hester-addtocart-wrap .added_to_cart',
			)
		);

		$this->add_responsive_control(
			'view_cart_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'view_cart_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-addtocart-wrap .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$html     = '';
		$product  = false;

		if ( ! empty( $settings['product_id'] ) ) {
			$product_data = get_post( $settings['product_id'] );
		}

		$product = ! empty( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ) ) ? wc_setup_product_data( $product_data ) : false;

		$this->add_render_attribute( 'button-wrap', 'class', 'hester-addtocart-wrap' );
		$this->add_render_attribute( 'button-text', 'class', 'hester-addtocart-text' );

		if ( $product ) {

			$product_id   = $product->get_id();
			$product_type = $product->get_type();

			$class = array(
				'hester-addtocart',
				'button',
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			);

			$this->add_render_attribute(
				'button',
				array(
					'href'            => esc_url( $product->add_to_cart_url() ),
					'class'           => $class,
					'data-quantity'   => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product_id,
					'rel'             => 'nofollow',
				)
			);

			$this->add_render_attribute(
				'icon-align',
				'class',
				array(
					'hester-button-icon',
					'elementor-align-icon-' . $settings['icon_align'],
				)
			);
			?>

			<div <?php echo $this->get_render_attribute_string( 'button-wrap' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<?php if ( ! empty( $settings['icon'] ) && 'left' == $settings['icon_align'] ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
							<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
						<?php
					}
					?>

					<span <?php echo $this->get_render_attribute_string( 'button-text' ); ?>><?php echo esc_attr( $settings['text'] ); ?></span>

					<?php if ( ! empty( $settings['icon'] ) && 'right' == $settings['icon_align'] ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
							<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
						<?php
					}
					?>
				</a>
			</div>

			<?php
		} elseif ( current_user_can( 'manage_options' ) ) {

			$this->add_render_attribute( 'button', 'href', '#' );
			$this->add_render_attribute(
				'button',
				'class',
				array(
					'hester-addtocart',
					'button',
					'elementor-button',
				)
			);
			?>

			<div <?php echo $this->get_render_attribute_string( 'button-wrap' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
					<span <?php echo $this->get_render_attribute_string( 'button-text' ); ?>><?php echo __( 'Please select a product', 'hester-core' ); ?></span>
				</a>
			</div>

			<?php
		}
	}
}




