<?php
namespace Hester_Core\Elementor\Modules\FlipBox\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Hester_Core\Elementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class FlipBox extends Base_Widget {

	public function get_name() {
		return 'hester-flip-box';
	}

	public function get_title() {
		return __( 'Flip Box', 'hester-core' );
	}

	public function get_icon() {

		return 'hester-icon eicon-flip-box';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_style_depends() {
		return array( 'hester-flip-box' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_front',
			array(
				'label' => __( 'Front', 'hester-core' ),
			)
		);

		$this->add_control(
			'graphic_element',
			array(
				'label'       => __( 'Graphic Element', 'hester-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'hester-core' ),
						'icon'  => 'eicon-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'hester-core' ),
						'icon'  => 'eicon-image-bold',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'hester-core' ),
						'icon'  => 'eicon-star',
					),
				),
				'default'     => 'icon',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'hester-core' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
				'dynamic'   => array( 'active' => true ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Actually its `image_size`
				'label'     => __( 'Image Size', 'hester-core' ),
				'default'   => 'thumbnail',
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'Icon', 'hester-core' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-heart-o',
					'library' => 'solid',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_view',
			array(
				'label'     => __( 'View', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'hester-core' ),
					'stacked' => __( 'Stacked', 'hester-core' ),
					'framed'  => __( 'Framed', 'hester-core' ),
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_shape',
			array(
				'label'     => __( 'Shape', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'circle',
				'options'   => array(
					'circle' => __( 'Circle', 'hester-core' ),
					'square' => __( 'Square', 'hester-core' ),
				),
				'condition' => array(
					'icon_view!'      => 'default',
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'front_title_text',
			array(
				'label'       => __( 'Title & Description', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'This is the heading', 'hester-core' ),
				'placeholder' => __( 'Your Title', 'hester-core' ),
				'separator'   => 'before',
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'front_description_text',
			array(
				'label'       => __( 'Description', 'hester-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'hester-core' ),
				'placeholder' => __( 'Your Description', 'hester-core' ),
				'title'       => __( 'Input image text here', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back',
			array(
				'label' => __( 'Back', 'hester-core' ),
			)
		);

		$this->add_control(
			'back_title_text',
			array(
				'label'       => __( 'Title & Description', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'This is the heading', 'hester-core' ),
				'placeholder' => __( 'Your Title', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'back_description_text',
			array(
				'label'       => __( 'Description', 'hester-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'hester-core' ),
				'placeholder' => __( 'Your Description', 'hester-core' ),
				'title'       => __( 'Input image text here', 'hester-core' ),
				'separator'   => 'none',
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'hester-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Click Here', 'hester-core' ),
				'separator' => 'before',
				'dynamic'   => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'hester-core' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
			)
		);

		$this->add_control(
			'link_click',
			array(
				'label'     => __( 'Apply Link On', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'box'    => __( 'Whole Box', 'hester-core' ),
					'button' => __( 'Button Only', 'hester-core' ),
				),
				'default'   => 'button',
				'condition' => array(
					'link[url]!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => __( 'Settings', 'hester-core' ),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-layer, {{WRAPPER}} .hester-flip-box-layer-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'flip_effect',
			array(
				'label'        => __( 'Flip Effect', 'hester-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'flip',
				'options'      => array(
					'flip'     => __( 'Flip', 'hester-core' ),
					'slide'    => __( 'Slide', 'hester-core' ),
					'push'     => __( 'Push', 'hester-core' ),
					'zoom-in'  => __( 'Zoom In', 'hester-core' ),
					'zoom-out' => __( 'Zoom Out', 'hester-core' ),
					'fade'     => __( 'Fade', 'hester-core' ),
				),
				'prefix_class' => 'hester-flip-box-effect-',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'flip_direction',
			array(
				'label'        => __( 'Flip Direction', 'hester-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'left',
				'options'      => array(
					'left'  => __( 'Left', 'hester-core' ),
					'right' => __( 'Right', 'hester-core' ),
					'up'    => __( 'Up', 'hester-core' ),
					'down'  => __( 'Down', 'hester-core' ),
				),
				'condition'    => array(
					'flip_effect!' => array(
						'fade',
						'zoom-in',
						'zoom-out',
					),
				),
				'prefix_class' => 'hester-flip-box-direction-',
			)
		);

		$this->add_control(
			'flip_3d',
			array(
				'label'        => __( '3D Depth', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'condition'    => array(
					'flip_effect' => 'flip',
				),
				'prefix_class' => 'hester-flip-box-3d-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_front',
			array(
				'label' => __( 'Front', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'front_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .hester-flip-box-front',
			)
		);

		$this->add_control(
			'front_background_overlay',
			array(
				'label'     => __( 'Background Overlay', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'front_background_image[id]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'front_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'front_alignment',
			array(
				'label'       => __( 'Alignment', 'hester-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'front_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'hester-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'hester-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'hester-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'front_border',
				'selector'  => '{{WRAPPER}} .hester-flip-box-front',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_image_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Image', 'hester-core' ),
				'condition' => array(
					'graphic_element' => 'image',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'     => __( 'Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'image_width',
			array(
				'label'      => __( 'Size (%)', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'image_opacity',
			array(
				'label'     => __( 'Opacity (%)', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-image' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'label'     => __( 'Image Border', 'hester-core' ),
				'selector'  => '{{WRAPPER}} .hester-flip-box-image img',
				'condition' => array(
					'graphic_element' => 'image',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'     => __( 'Border Radius', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'heading_icon_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Icon', 'hester-core' ),
				'condition' => array(
					'graphic_element' => 'icon',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_spacing',
			array(
				'label'     => __( 'Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_primary_color',
			array(
				'label'     => __( 'Icon Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_padding',
			array(
				'label'     => __( 'Icon Padding', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->add_control(
			'icon_rotate',
			array(
				'label'     => __( 'Icon Rotate', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_border_width',
			array(
				'label'     => __( 'Border Width', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view'       => 'framed',
				),
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'hester-core' ),
				'condition' => array(
					'front_title_text!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'front_title_spacing',
			array(
				'label'     => __( 'Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'front_description_text!' => '',
				),
			)
		);

		$this->add_control(
			'front_title_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-title' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_title_typography',
				'label'    => __( 'Typography', 'hester-core' ),
				'selector' => '{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-title',
			)
		);

		$this->add_control(
			'heading_description_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Description', 'hester-core' ),
				'condition' => array(
					'front_description_text!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'front_description_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-desc' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_description_typography',
				'label'    => __( 'Typography', 'hester-core' ),

				'selector' => '{{WRAPPER}} .hester-flip-box-front .hester-flip-box-layer-desc',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_back',
			array(
				'label' => __( 'Back', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'back_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .hester-flip-box-back',
			)
		);

		$this->add_control(
			'back_background_overlay',
			array(
				'label'     => __( 'Background Overlay', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'back_background_image[id]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'back_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'back_alignment',
			array(
				'label'       => __( 'Alignment', 'hester-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .hester-flip-box-button' => 'margin-{{VALUE}}: 0',
				),
			)
		);

		$this->add_control(
			'back_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'hester-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'hester-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'hester-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'back_border',
				'selector'  => '{{WRAPPER}} .hester-flip-box-back',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_back_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'hester-core' ),
				'condition' => array(
					'back_title_text!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'back_title_spacing',
			array(
				'label'     => __( 'Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'back_title_text!' => '',
				),
			)
		);

		$this->add_control(
			'back_title_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-title' => 'color: {{VALUE}}',

				),
				'condition' => array(
					'back_title_text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'back_title_typography',
				'label'     => __( 'Typography', 'hester-core' ),

				'selector'  => '{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-title',
				'condition' => array(
					'back_title_text!' => '',
				),
			)
		);

		$this->add_control(
			'heading_back_description_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Description', 'hester-core' ),
				'condition' => array(
					'back_description_text!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'back_description_spacing',
			array(
				'label'     => __( 'Spacing', 'hester-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'back_description_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-desc' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography_b',
				'label'    => __( 'Typography', 'hester-core' ),

				'selector' => '{{WRAPPER}} .hester-flip-box-back .hester-flip-box-layer-desc',
			)
		);

		$this->add_control(
			'heading_back_button_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Button', 'hester-core' ),
				'condition' => array(
					'button_text!' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'hester-core' ),

				'selector' => '{{WRAPPER}} .hester-flip-box-button',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'hester-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .hester-flip-box-button',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .hester-flip-box-button',
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-flip-box-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .hester-flip-box-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$wrap_tag   = 'div';
		$button_tag = 'a';
		$link_url   = empty( $settings['link']['url'] ) ? '#' : esc_url( $settings['link']['url'] );

		$this->add_render_attribute( 'wrap', 'class', 'hester-flip-box-layer hester-flip-box-back' );

		$this->add_render_attribute(
			'button',
			'class',
			array(
				'hester-flip-box-button',
				'button',
				'elementor-button',
			)
		);

		if ( 'box' === $settings['link_click'] ) {
			$wrap_tag   = 'a';
			$button_tag = 'button';
			$this->add_render_attribute( 'wrap', 'href', $link_url );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'wrap', 'target', '_blank' );
			}
		} else {
			$this->add_render_attribute( 'button', 'href', $link_url );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}
		}

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'icon-wrap', 'class', 'elementor-icon-wrap' );
			$this->add_render_attribute( 'icon-wrap', 'class', 'elementor-view-' . esc_html( $settings['icon_view'] ) );

			if ( 'default' != $settings['icon_view'] ) {
				$this->add_render_attribute( 'icon-wrap', 'class', 'elementor-shape-' . esc_html( $settings['icon_shape'] ) );
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		} ?>

		<div class="hester-flip-box">

			<div class="hester-flip-box-layer hester-flip-box-front">
				<div class="hester-flip-box-layer-overlay">
					<div class="hester-flip-box-layer-inner">
						<?php
						if ( 'image' === $settings['graphic_element']
							&& ! empty( $settings['image']['url'] ) ) {
							?>
							<div class="hester-flip-box-image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
							</div>
							<?php
						} elseif ( 'icon' === $settings['graphic_element']
							&& ! empty( $settings['icon'] ) ) {
							?>
							<div <?php echo $this->get_render_attribute_string( 'icon-wrap' ); ?>>
								<div class="elementor-icon">
									<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
								</div>
							</div>
							<?php
						}

						if ( ! empty( $settings['front_title_text'] ) ) {
							?>
							<h3 class="hester-flip-box-layer-title">
								<?php echo $this->parse_text_editor( $settings['front_title_text'] ); ?>
							</h3>
							<?php
						}

						if ( ! empty( $settings['front_description_text'] ) ) {
							?>
							<div class="hester-flip-box-layer-desc">
								<?php echo $this->parse_text_editor( $settings['front_description_text'] ); ?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<<?php echo esc_attr( $wrap_tag ); ?> <?php echo $this->get_render_attribute_string( 'wrap' ); ?>>
				<div class="hester-flip-box-layer-overlay">
					<div class="hester-flip-box-layer-inner">
						<?php
						if ( ! empty( $settings['back_title_text'] ) ) {
							?>
							<h3 class="hester-flip-box-layer-title">
								<?php echo $this->parse_text_editor( $settings['back_title_text'] ); ?>
							</h3>
							<?php
						}

						if ( ! empty( $settings['back_description_text'] ) ) {
							?>
							<div class="hester-flip-box-layer-desc">
								<?php echo $this->parse_text_editor( $settings['back_description_text'] ); ?>
							</div>
							<?php
						}

						if ( ! empty( $settings['button_text'] ) ) {
							?>
							<<?php echo esc_attr( $button_tag ); ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
								<?php echo $this->parse_text_editor( $settings['button_text'] ); ?>
							</<?php echo esc_attr( $button_tag ); ?>>
							<?php
						}
						?>
					</div>
				</div>
			</<?php echo esc_attr( $wrap_tag ); ?>>

		</div>

		<?php
	}

	protected function content_template() {

		?>
		<#
			if('image' === settings.graphic_element && '' !== settings.image.url) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.image_size,
					dimension: settings.image_custom_dimension,
					model: editModel
				};

				var imageUrl = elementor.imagesManager.getImageUrl(image);
			}

			var wrapperTag = 'div',
				buttonTag = 'a';

			if('box' === settings.link_click) {
				wrapperTag = 'a';
				buttonTag = 'button';
			}

			if('icon' === settings.graphic_element) {
				var iconWrapperClasses = 'elementor-icon-wrap';
					iconWrapperClasses += ' elementor-view-' + settings.icon_view;
				if('default' !== settings.icon_view) {
					iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
				}
			}
		#>

		<div class="hester-flip-box">
			<div class="hester-flip-box-layer hester-flip-box-front">
				<div class="hester-flip-box-layer-overlay">
					<div class="hester-flip-box-layer-inner">
						<# if('image' === settings.graphic_element
							&& '' !== settings.image.url) { #>
							<div class="hester-flip-box-image">
								<img src="{{ imageUrl }}">
							</div>
						<# } else if('icon' === settings.graphic_element
							&& settings.icon) { #>
							<div class="{{ iconWrapperClasses }}" >
								<div class="elementor-icon">
									<i class="{{ settings.icon }}"></i>
								</div>
							</div>
						<# } #>

						<# if(settings.front_title_text) { #>
							<h3 class="hester-flip-box-layer-title">{{{ settings.front_title_text }}}</h3>
						<# } #>

						<# if(settings.front_description_text) { #>
							<div class="hester-flip-box-layer-desc">{{{ settings.front_description_text }}}</div>
						<# } #>
					</div>
				</div>
			</div>
			<{{ wrapperTag }} class="hester-flip-box-layer hester-flip-box-back">
				<div class="hester-flip-box-layer-overlay">
					<div class="hester-flip-box-layer-inner">
						<# if(settings.back_title_text) { #>
							<h3 class="hester-flip-box-layer-title">{{{ settings.back_title_text }}}</h3>
						<# } #>

						<# if(settings.back_description_text) { #>
							<div class="hester-flip-box-layer-desc">{{{ settings.back_description_text }}}</div>
						<# } #>

						<# if(settings.button_text) { #>
							<{{ buttonTag }} href="#" class="hester-flip-box-button button elementor-button">{{{ settings.button_text }}}</{{ buttonTag }}>
						<# } #>
					</div>
				</div>
			</{{ wrapperTag }}>
		</div>

		<?php
	}
}



