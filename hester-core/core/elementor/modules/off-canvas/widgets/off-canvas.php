<?php
namespace Hester_Core\Elementor\Modules\OffCanvas\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Hester_Core\Elementor\Base\Base_Widget;
use Elementor\Plugin;

class Off_Canvas extends Base_Widget {

	public function get_name() {
		return 'hester-off-canvas';
	}

	public function get_title() {
		return __( 'Off Canvas', 'hester-core' );
	}

	public function get_icon() {

		return 'hester-icon eicon-menu-bar';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_script_depends() {
		return array( 'hester-off-canvas' );
	}

	public function get_style_depends() {
		return array( 'hester-off-canvas' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_off_canvas',
			array(
				'label' => __( 'Off Canvas', 'hester-core' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Source', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sidebar',
				'options' => array(
					'sidebar'  => __( 'Sidebar', 'hester-core' ),
					'template' => __( 'Template', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'sidebars',
			array(
				'label'     => __( 'Choose Sidebar', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => hester_get_available_sidebars(),
				'condition' => array(
					'source' => 'sidebar',
				),
			)
		);

		$this->add_control(
			'templates',
			array(
				'label'     => __( 'Choose Template', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => hester_get_available_templates(),
				'condition' => array(
					'source' => 'template',
				),
			)
		);

		$this->add_responsive_control(
			'off_canvas_width',
			array(
				'label'      => __( 'Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 1200,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_overlay',
			array(
				'label' => __( 'Overlay', 'hester-core' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'off_canvas_close_button',
			array(
				'label'   => __( 'Close Button', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
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
				'default' => __( 'Off Canvas', 'hester-core' ),
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
				'label'            => __( 'Icon', 'hester-core' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'fa fa-bars',
				'label_block'      => true,
				'default'          => array(
					'value'   => 'fas fa-bars',
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
					'{{WRAPPER}} .hester-off-canvas-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hester-off-canvas-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Off Canvas', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'off_canvas_bg',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_overlay_bg',
			array(
				'label'     => __( 'Overlay Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.5)',
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-overlay' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'off_canvas_overlay' => 'yes',
				),
			)
		);

		$this->add_control(
			'off_canvas_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_link_color',
			array(
				'label'     => __( 'Link Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar a' => 'color: {{VALUE}};',
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar a *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'off_canvas_box_shadow',
				'selector' => '#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar',
			)
		);

		$this->add_responsive_control(
			'off_canvas_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_close_btn_heading',
			array(
				'label'     => __( 'Close Button', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'off_canvas_close_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'off_canvas_close_btn_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'off_canvas_close_button' => 'yes',
				),
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-close svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_close_btn_hover_color',
			array(
				'label'     => __( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'off_canvas_close_button' => 'yes',
				),
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-close:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_widget_style',
			array(
				'label'     => __( 'Widgets', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'source' => 'sidebar',
				),
			)
		);

		$this->add_control(
			'off_canvas_widgets_bg',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'source' => 'sidebar',
				),
				'selectors' => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar .sidebar-box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'off_canvas_widgets_border',
				'selector'  => '#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar .sidebar-box',
				'condition' => array(
					'source' => 'sidebar',
				),
			)
		);

		$this->add_responsive_control(
			'off_canvas_widgets_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'source' => 'sidebar',
				),
				'selectors'  => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar .sidebar-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'off_canvas_widgets_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'source' => 'sidebar',
				),
				'selectors'  => array(
					'#hester-off-canvas-{{ID}}.hester-off-canvas-wrap .hester-off-canvas-sidebar .sidebar-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name'     => 'off_canvas_button_typography',
				'selector' => '{{WRAPPER}} .hester-off-canvas-button a',
			)
		);

		$this->start_controls_tabs( 'tabs_off_canvas_button_style' );

		$this->start_controls_tab(
			'tab_off_canvas_button_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'off_canvas_button_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-off-canvas-button a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_button_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-off-canvas-button a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_off_canvas_button_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'off_canvas_button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-off-canvas-button a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_button_hover_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-off-canvas-button a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'off_canvas_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-off-canvas-button a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'off_canvas_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .hester-off-canvas-button a',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'off_canvas_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-off-canvas-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'off_canvas_button_box_shadow',
				'selector' => '{{WRAPPER}} .hester-off-canvas-button a',
			)
		);

		$this->add_responsive_control(
			'off_canvas_button_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-off-canvas-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'off_canvas_button_margin',
			array(
				'label'      => __( 'Margin', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-off-canvas-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();
		$source   = $settings['source'];

		$this->add_render_attribute( 'button-wrap', 'class', 'hester-off-canvas-button' );
		$this->add_render_attribute( 'button', 'href', '#hester-off-canvas-' . esc_attr( $id ) );
		$this->add_render_attribute( 'button', 'class', 'button elementor-button' );

		$this->add_render_attribute(
			'icon-align',
			'class',
			array(
				'hester-button-icon',
				'elementor-align-icon-' . $settings['icon_align'],
			)
		);

		$this->add_render_attribute( 'off-canvas', 'id', 'hester-off-canvas-' . esc_attr( $id ) );
		$this->add_render_attribute( 'off-canvas', 'class', 'hester-off-canvas-wrap' );

		$this->add_render_attribute( 'off-canvas-close', 'type', 'button' );
		$this->add_render_attribute( 'off-canvas-close', 'class', 'hester-off-canvas-close' );

		$this->add_render_attribute( 'off-canvas-sidebar', 'class', 'hester-off-canvas-sidebar' );

		$this->add_render_attribute( 'off-canvas-overlay', 'class', 'hester-off-canvas-overlay' ); ?>

		<div <?php echo $this->get_render_attribute_string( 'button-wrap' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<?php
				if ( ! empty( $settings['icon'] ) && 'left' == $settings['icon_align'] ) {
					?>
					<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
					</span>
					<?php
				}
				?>

				<span><?php echo esc_attr( $settings['text'] ); ?></span>

				<?php
				if ( ! empty( $settings['icon'] ) && 'right' == $settings['icon_align'] ) {
					?>
					<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
					</span>
					<?php
				}
				?>
			</a>
		</div>
		
		<div <?php echo $this->get_render_attribute_string( 'off-canvas' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'off-canvas-sidebar' ); ?>>
				<?php
				if ( $settings['off_canvas_close_button'] ) {
					?>
					<button <?php echo $this->get_render_attribute_string( 'off-canvas-close' ); ?>>
						<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
							<path d="M505.943,6.058c-8.077-8.077-21.172-8.077-29.249,0L6.058,476.693c-8.077,8.077-8.077,21.172,0,29.249
								C10.096,509.982,15.39,512,20.683,512c5.293,0,10.586-2.019,14.625-6.059L505.943,35.306
								C514.019,27.23,514.019,14.135,505.943,6.058z"/>
							<path d="M505.942,476.694L35.306,6.059c-8.076-8.077-21.172-8.077-29.248,0c-8.077,8.076-8.077,21.171,0,29.248l470.636,470.636
								c4.038,4.039,9.332,6.058,14.625,6.058c5.293,0,10.587-2.019,14.624-6.057C514.018,497.866,514.018,484.771,505.942,476.694z"/>
						</svg>
					</button>
				<?php } ?>
				<?php
				if ( ! empty( $source ) ) {
					if ( 'sidebar' == $source
						&& ( '0' != $settings['sidebars'] && ! empty( $settings['sidebars'] ) ) ) {
						dynamic_sidebar( $settings['sidebars'] );
					} elseif ( 'template' == $source
						&& ( '0' != $settings['templates'] && ! empty( $settings['templates'] ) ) ) {
						echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['templates'] );
					}
				}
				?>
			</div>
			<?php
			if ( $settings['off_canvas_overlay'] ) {
				?>
				<div <?php echo $this->get_render_attribute_string( 'off-canvas-overlay' ); ?>></div>
				<?php
			}
			?>
		</div>

		<?php
	}
}



