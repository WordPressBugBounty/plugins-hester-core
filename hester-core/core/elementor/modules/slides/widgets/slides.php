<?php
/**
 * Slides Widget
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Slides\Widgets;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin as Elementor_Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Hester_Core\Elementor\Base\Base_Widget;
use Hester_Core\Elementor\Modules\Slides\Controls\Control_Slides_Animation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slides widget.
 */
class Slides extends Base_Widget {

	/**
	 * Get widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-slides';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Slides', 'hester-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'hester-icon eicon-slides';
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'slides', 'carousel', 'image', 'title', 'slider' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'swiper', 'hester-slides' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'hester-slides', 'swiper', 'hester-swiper-custom' );
	}

	/**
	 * Whether this widget has dynamic content.
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Keep wrapper behavior aligned with Elementor optimized markup feature.
	 *
	 * @return bool
	 */
	public function has_widget_inner_wrapper(): bool {
		return ! Elementor_Plugin::instance()->experiments->is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Get button size options.
	 *
	 * @return array
	 */
	public static function get_button_sizes() {
		return array(
			'xs' => esc_html__( 'Extra Small', 'hester-core' ),
			'sm' => esc_html__( 'Small', 'hester-core' ),
			'md' => esc_html__( 'Medium', 'hester-core' ),
			'lg' => esc_html__( 'Large', 'hester-core' ),
			'xl' => esc_html__( 'Extra Large', 'hester-core' ),
		);
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			array(
				'label' => esc_html__( 'Slides', 'hester-core' ),
			)
		);

		$this->add_control(
			'slides_name',
			array(
				'label'   => esc_html__( 'Slides Name', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Slides', 'hester-core' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab(
			'background',
			array(
				'label' => esc_html__( 'Background', 'hester-core' ),
			)
		);

		$repeater->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#bbbbbb',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'background_image',
			array(
				'label'     => esc_html__( 'Image', 'hester-core' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-image: url({{URL}})',
				),
				'default'   => array(
					'url' => '',
				),
			)
		);

		$repeater->add_control(
			'background_size',
			array(
				'label'     => esc_html__( 'Size', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => esc_html__( 'Cover', 'hester-core' ),
					'contain' => esc_html__( 'Contain', 'hester-core' ),
					'auto'    => esc_html__( 'Auto', 'hester-core' ),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-size: {{VALUE}}',
				),
				'condition' => array(
					'background_image[url]!' => '',
				),
			)
		);

		$repeater->add_control(
			'background_ken_burns',
			array(
				'label'     => esc_html__( 'Ken Burns Effect', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'background_image[url]!' => '',
				),
			)
		);

		$repeater->add_control(
			'zoom_direction',
			array(
				'label'      => esc_html__( 'Zoom Direction', 'hester-core' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'in',
				'options'    => array(
					'in'  => esc_html__( 'In', 'hester-core' ),
					'out' => esc_html__( 'Out', 'hester-core' ),
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'background_ken_burns',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'background_overlay',
			array(
				'label'   => esc_html__( 'Background Overlay', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater->add_control(
			'background_video',
			array(
				'label'        => esc_html__( 'Background Video', 'hester-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'hester-core' ),
				'label_off'    => esc_html__( 'Off', 'hester-core' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater->add_control(
			'background_video_link',
			array(
				'label'         => esc_html__( 'Video URL', 'hester-core' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => array(
					'active' => true,
				),
				'show_external' => false,
				'condition'     => array(
					'background_video' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'background_overlay_color',
			array(
				'label'      => esc_html__( 'Color', 'hester-core' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba(0,0,0,0.5)',
				'condition'  => array(
					'background_overlay' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
			array(
				'label'      => esc_html__( 'Blend Mode', 'hester-core' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					''            => esc_html__( 'Normal', 'hester-core' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'exclusion'   => 'Exclusion',
					'luminosity'  => 'Luminosity',
				),
				'condition'  => array(
					'background_overlay' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'content',
			array(
				'label' => esc_html__( 'Content', 'hester-core' ),
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'       => esc_html__( 'Subtitle', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide Subtitle', 'hester-core' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'heading',
			array(
				'label'       => esc_html__( 'Title', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Slide Heading', 'hester-core' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hester-core' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'hester-core' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'hester-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Click Here', 'hester-core' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => esc_html__( 'Link', 'hester-core' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link_click',
			array(
				'label'      => esc_html__( 'Apply Link On', 'hester-core' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					'slide'  => esc_html__( 'Whole Slide', 'hester-core' ),
					'button' => esc_html__( 'Button Only', 'hester-core' ),
				),
				'default'    => 'slide',
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'link[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'style',
			array(
				'label' => esc_html__( 'Style', 'hester-core' ),
			)
		);

		$repeater->add_control(
			'custom_style',
			array(
				'label'       => esc_html__( 'Custom', 'hester-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Set custom style that will only affect this specific slide.', 'hester-core' ),
			)
		);

		$repeater->add_control(
			'horizontal_position',
			array(
				'label'                => esc_html__( 'Horizontal Position', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'hester-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				),
				'conditions'           => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'vertical_position',
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
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'align-items: {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'conditions'           => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'text_align',
			array(
				'label'                => esc_html__( 'Text Align', 'hester-core' ),
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				),
				'conditions'           => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'content_color',
			array(
				'label'      => esc_html__( 'Content Color', 'hester-core' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .elementor-slide-subtitle' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .elementor-slide-heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .elementor-slide-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .elementor-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'       => 'repeater_text_shadow',
				'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents',
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'custom_style',
							'value' => 'yes',
						),
					),
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			array(
				'label'       => esc_html__( 'Slides', 'hester-core' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'subtitle'         => esc_html__( 'Slide 1 Subtitle', 'hester-core' ),
						'heading'          => esc_html__( 'Slide 1 Heading', 'hester-core' ),
						'description'      => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'hester-core' ),
						'button_text'      => esc_html__( 'Click Here', 'hester-core' ),
						'background_color' => '#2563eb',
						'background_image' => array(
							'url' => '',
						),
					),
					array(
						'subtitle'         => esc_html__( 'Slide 2 Subtitle', 'hester-core' ),
						'heading'          => esc_html__( 'Slide 2 Heading', 'hester-core' ),
						'description'      => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'hester-core' ),
						'button_text'      => esc_html__( 'Click Here', 'hester-core' ),
						'background_color' => '#7c3aed',
						'background_image' => array(
							'url' => '',
						),
					),
					array(
						'subtitle'         => esc_html__( 'Slide 3 Subtitle', 'hester-core' ),
						'heading'          => esc_html__( 'Slide 3 Heading', 'hester-core' ),
						'description'      => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'hester-core' ),
						'button_text'      => esc_html__( 'Click Here', 'hester-core' ),
						'background_color' => '#16a34a',
						'background_image' => array(
							'url' => '',
						),
					),
				),
				'title_field' => '{{{ heading }}}',
			)
		);

		$this->add_responsive_control(
			'slides_height',
			array(
				'label'      => esc_html__( 'Height', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vh', 'custom' ),
				'range'      => array(
					'px'  => array(
						'min' => 100,
						'max' => 1000,
					),
					'em'  => array(
						'min' => 10,
						'max' => 100,
					),
					'rem' => array(
						'min' => 10,
						'max' => 100,
					),
					'vh'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'slides_subtitle_tag',
			array(
				'label'   => esc_html__( 'Subtitle HTML Tag', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => 'div',
			)
		);

		$this->add_control(
			'slides_title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => 'div',
			)
		);

		$this->add_control(
			'slides_description_tag',
			array(
				'label'   => esc_html__( 'Description HTML Tag', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => 'div',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label' => esc_html__( 'Slider Options', 'hester-core' ),
				'type'  => Controls_Manager::SECTION,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => esc_html__( 'Navigation', 'hester-core' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => esc_html__( 'Arrows and Dots', 'hester-core' ),
					'arrows' => esc_html__( 'Arrows', 'hester-core' ),
					'dots'   => esc_html__( 'Dots', 'hester-core' ),
					'none'   => esc_html__( 'None', 'hester-core' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'label'              => esc_html__( 'Slides Per View', 'hester-core' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1,
				'tablet_default'     => 1,
				'mobile_default'     => 1,
				'min'                => 1,
				'max'                => 4,
				'step'               => 1,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_gap',
			array(
				'label'              => esc_html__( 'Space Between Slides', 'hester-core' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'tablet_default'     => 0,
				'mobile_default'     => 0,
				'min'                => 0,
				'max'                => 100,
				'step'               => 1,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'hester-core' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'hester-core' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => array(
					'autoplay!' => '',
				),
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => esc_html__( 'Pause on Interaction', 'hester-core' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => array(
					'autoplay!' => '',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'hester-core' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-slide' => 'transition-duration: calc({{VALUE}}ms*1.2)',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'hester-core' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'              => esc_html__( 'Transition', 'hester-core' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => esc_html__( 'Slide', 'hester-core' ),
					'fade'  => esc_html__( 'Fade', 'hester-core' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'transition_speed',
			array(
				'label'              => esc_html__( 'Transition Speed', 'hester-core' ) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'   => esc_html__( 'Content Animation', 'hester-core' ),
				'type'    => Control_Slides_Animation::TYPE,
				'default' => 'fadeInUp',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_slides',
			array(
				'label' => esc_html__( 'Slides', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_max_width',
			array(
				'label'          => esc_html__( 'Content Width', 'hester-core' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'px'  => array( 'max' => 1000 ),
					'em'  => array( 'max' => 100 ),
					'rem' => array( 'max' => 100 ),
				),
				'default'        => array(
					'size' => 66,
					'unit' => '%',
				),
				'tablet_default' => array( 'unit' => '%' ),
				'mobile_default' => array( 'unit' => '%' ),
				'selectors'      => array(
					'{{WRAPPER}} .swiper-slide-contents' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slides_horizontal_position',
			array(
				'label'        => esc_html__( 'Horizontal Position', 'hester-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'center',
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'hester-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'elementor--h-position-',
			)
		);

		$this->add_control(
			'slides_vertical_position',
			array(
				'label'        => esc_html__( 'Vertical Position', 'hester-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'middle',
				'options'      => array(
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
				'prefix_class' => 'elementor--v-position-',
			)
		);

		$this->add_control(
			'slides_text_align',
			array(
				'label'                => esc_html__( 'Text Align', 'hester-core' ),
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
				'default'              => 'center',
				'classes'              => 'elementor-control-start-end',
				'selectors_dictionary' => array(
					'left'  => is_rtl() ? 'end' : 'start',
					'right' => is_rtl() ? 'start' : 'end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .swiper-slide-contents',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_subtitle',
			array(
				'label' => esc_html__( 'Subtitle', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'subtitle_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide-inner .elementor-slide-subtitle:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'subtitle_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-subtitle' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'subtitle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'subtitle_radius',
			array(
				'label'      => esc_html__( 'Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .elementor-slide-subtitle',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide-inner .elementor-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-heading' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'heading_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_radius',
			array(
				'label'      => esc_html__( 'Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .elementor-slide-heading',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			array(
				'label' => esc_html__( 'Description', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide-inner .elementor-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .elementor-slide-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Button', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => esc_html__( 'Size', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .elementor-slide-button',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
			)
		);

		$this->add_control(
			'button_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 20 ),
					'em'  => array( 'max' => 2 ),
					'rem' => array( 'max' => 2 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => esc_html__( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-slide-button',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => esc_html__( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_hover_background',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-slide-button:hover',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-slide-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_transition_duration',
			array(
				'label'      => esc_html__( 'Transition Duration', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 's', 'ms', 'custom' ),
				'default'    => array(
					'unit' => 'ms',
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-slide-button' => 'transition-duration: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			array(
				'label'     => esc_html__( 'Navigation', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'arrows', 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'heading_style_arrows',
			array(
				'label'     => esc_html__( 'Arrows', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'arrows_position',
			array(
				'label'        => esc_html__( 'Position', 'hester-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inside',
				'options'      => array(
					'inside'  => esc_html__( 'Inside', 'hester-core' ),
					'outside' => esc_html__( 'Outside', 'hester-core' ),
				),
				'prefix_class' => 'elementor-arrows-position-',
				'condition'    => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => esc_html__( 'Size', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-swiper-button svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'heading_style_dots',
			array(
				'label'     => esc_html__( 'Pagination', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'        => esc_html__( 'Position', 'hester-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inside',
				'options'      => array(
					'outside' => esc_html__( 'Outside', 'hester-core' ),
					'inside'  => esc_html__( 'Inside', 'hester-core' ),
				),
				'prefix_class' => 'elementor-pagination-position-',
				'condition'    => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'dots_gap',
			array(
				'label'      => esc_html__( 'Space Between Dots', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array( 'max' => 50 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}{{UNIT}}; --swiper-pagination-bullet-vertical-gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => esc_html__( 'Size', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array( 'max' => 100 ),
					'em'  => array( 'max' => 10 ),
					'rem' => array( 'max' => 10 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination-bullet'                => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction'              => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_color_inactive',
			array(
				'label'     => esc_html__( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background-color: {{VALUE}}; opacity: 1;',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_color',
			array(
				'label'     => esc_html__( 'Active Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) || ! is_array( $settings['slides'] ) ) {
			return;
		}

		$optimized_markup = Elementor_Plugin::instance()->experiments->is_feature_active( 'e_optimized_markup' );
		$direction        = is_rtl() ? 'rtl' : 'ltr';
		$slides_name      = isset( $settings['slides_name'] ) ? sanitize_text_field( (string) $settings['slides_name'] ) : esc_html__( 'Slides', 'hester-core' );
		$navigation       = sanitize_key( $settings['navigation'] ?? 'both' );
		if ( ! in_array( $navigation, array( 'both', 'arrows', 'dots', 'none' ), true ) ) {
			$navigation = 'both';
		}

		$transition = sanitize_key( $settings['transition'] ?? 'slide' );
		if ( ! in_array( $transition, array( 'slide', 'fade' ), true ) ) {
			$transition = 'slide';
		}

		$desktop_slides = max( 1, min( 4, absint( $settings['slides_per_view'] ?? 1 ) ) );
		$tablet_slides  = max( 1, min( 2, absint( $settings['slides_per_view_tablet'] ?? $desktop_slides ) ) );
		$mobile_slides  = 1;
		$desktop_gap    = max( 0, absint( $settings['slides_gap'] ?? 0 ) );
		$tablet_gap     = max( 0, absint( $settings['slides_gap_tablet'] ?? $desktop_gap ) );
		$mobile_gap     = max( 0, absint( $settings['slides_gap_mobile'] ?? $tablet_gap ) );

		$swiper_settings = array(
			'navigation'             => $navigation,
			'slides_per_view'        => $desktop_slides,
			'slides_per_view_tablet' => $tablet_slides,
			'slides_per_view_mobile' => $mobile_slides,
			'slides_gap'             => $desktop_gap,
			'slides_gap_tablet'      => $tablet_gap,
			'slides_gap_mobile'      => $mobile_gap,
			'autoplay'               => 'yes' === ( $settings['autoplay'] ?? '' ),
			'pause_on_hover'         => 'yes' === ( $settings['pause_on_hover'] ?? '' ),
			'pause_on_interaction'   => 'yes' === ( $settings['pause_on_interaction'] ?? '' ),
			'autoplay_speed'         => absint( $settings['autoplay_speed'] ?? 5000 ),
			'infinite'               => 'yes' === ( $settings['infinite'] ?? '' ),
			'transition'             => $transition,
			'transition_speed'       => absint( $settings['transition_speed'] ?? 500 ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'                => array( 'hester-slides-wrapper', 'elementor-slides-wrapper', 'elementor-main-swiper', 'swiper' ),
				'role'                 => 'region',
				'aria-roledescription' => 'carousel',
				'aria-label'           => $slides_name,
				'dir'                  => $direction,
				'data-animation'       => ! empty( $settings['content_animation'] ) ? sanitize_key( (string) $settings['content_animation'] ) : 'fadeInUp',
				'data-settings'        => wp_json_encode( $swiper_settings ),
			)
		);

		$subtitle_tag    = Utils::validate_html_tag( $settings['slides_subtitle_tag'] ?? 'div' );
		$title_tag       = Utils::validate_html_tag( $settings['slides_title_tag'] ?? 'div' );
		$description_tag = Utils::validate_html_tag( $settings['slides_description_tag'] ?? 'div' );

		$this->add_render_attribute( 'button', 'class', array( 'elementor-button', 'elementor-slide-button' ) );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . sanitize_html_class( $settings['button_size'] ) );
		}

		$slides      = array();
		$slide_count = 0;
		$allowed_title_subtitle_html = hester_get_inline_content_allowed_html();

		foreach ( $settings['slides'] as $slide_index => $slide ) {
			$slide_html       = '';
			$btn_attributes   = '';
			$slide_attributes = '';
			$slide_element    = 'div';
			$btn_element      = 'span';
			$link             = isset( $slide['link'] ) && is_array( $slide['link'] ) ? $slide['link'] : array();
			$link_click       = isset( $slide['link_click'] ) ? sanitize_key( (string) $slide['link_click'] ) : 'slide';
			if ( ! in_array( $link_click, array( 'slide', 'button' ), true ) ) {
				$link_click = 'slide';
			}
			$subtitle           = isset( $slide['subtitle'] ) ? wp_kses( (string) $slide['subtitle'], $allowed_title_subtitle_html ) : '';
			$heading            = isset( $slide['heading'] ) ? wp_kses( (string) $slide['heading'], $allowed_title_subtitle_html ) : '';
			$description        = isset( $slide['description'] ) ? $this->parse_text_editor( (string) $slide['description'] ) : '';
			$description        = '' !== $description ? wp_kses_post( $description ) : '';
			$button_text        = isset( $slide['button_text'] ) ? sanitize_text_field( (string) $slide['button_text'] ) : '';
			$background_ken     = ! empty( $slide['background_ken_burns'] );
			$zoom_direction     = isset( $slide['zoom_direction'] ) ? sanitize_key( (string) $slide['zoom_direction'] ) : 'in';
			$background_overlay = ( isset( $slide['background_overlay'] ) && 'yes' === $slide['background_overlay'] ) || ( isset( $slide['background_overlay_image'] ) && 'yes' === $slide['background_overlay_image'] ) || ( isset( $slide['background_overlay_video'] ) && 'yes' === $slide['background_overlay_video'] );
			$video_enabled      = isset( $slide['background_video'] ) && 'yes' === $slide['background_video'];
			$video_url          = '';

			if ( $video_enabled && ! empty( $slide['background_video_link']['url'] ) ) {
				$video_url = esc_url( $slide['background_video_link']['url'] );
			}

			if ( ! empty( $link['url'] ) ) {
				$this->add_link_attributes( 'slide_link_' . $slide_count, $link );

				if ( 'button' === $link_click ) {
					$btn_element    = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link_' . $slide_count );
				} else {
					$slide_element    = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link_' . $slide_count );
				}
			}

			$this->add_render_attribute( 'slide_bg_' . $slide_index, 'class', 'swiper-slide-bg' );

			if ( $background_ken ) {
				$this->add_render_attribute( 'slide_bg_' . $slide_index, 'class', array( 'elementor-ken-burns', 'elementor-ken-burns--' . $zoom_direction ) );
			}

			if ( ! empty( $slide['background_image']['id'] ) ) {
				$this->add_render_attribute( 'slide_bg_' . $slide_index, 'role', 'img' );
				$this->add_render_attribute( 'slide_bg_' . $slide_index, 'aria-label', Control_Media::get_image_alt( $slide['background_image'] ) );
			} elseif ( ! empty( $slide['background_image']['url'] ) && '' !== $heading ) {
				$this->add_render_attribute( 'slide_bg_' . $slide_index, 'role', 'img' );
				$this->add_render_attribute( 'slide_bg_' . $slide_index, 'aria-label', wp_strip_all_tags( $heading ) );
			}

			$slide_html .= '<' . $slide_element . ' class="swiper-slide-inner" ' . $slide_attributes . '>';
			$slide_html .= '<div class="swiper-slide-contents">';

			if ( '' !== $subtitle ) {
				$slide_html .= '<' . esc_html( $subtitle_tag ) . ' class="elementor-slide-subtitle">' . wp_kses_post( $subtitle ) . '</' . esc_html( $subtitle_tag ) . '>';
			}

			if ( '' !== $heading ) {
				$slide_html .= '<' . esc_html( $title_tag ) . ' class="elementor-slide-heading">' . wp_kses_post( $heading ) . '</' . esc_html( $title_tag ) . '>';
			}

			if ( '' !== $description ) {
				$slide_html .= '<' . esc_html( $description_tag ) . ' class="elementor-slide-description">' . $description . '</' . esc_html( $description_tag ) . '>';
			}

			if ( '' !== $button_text ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . esc_html( $button_text ) . '</' . $btn_element . '>';
			}

			$slide_html .= '</div></' . $slide_element . '>';

			if ( $background_overlay ) {
				$slide_html = '<div class="elementor-background-overlay"></div>' . $slide_html;
			}

			if ( '' !== $video_url ) {
				$host = strtolower( (string) wp_parse_url( $video_url, PHP_URL_HOST ) );

				if ( false !== strpos( $host, 'youtube.com' ) || false !== strpos( $host, 'youtu.be' ) ) {
					$youtube_id = '';

					if ( false !== strpos( $host, 'youtu.be' ) ) {
						$youtube_id = trim( (string) wp_parse_url( $video_url, PHP_URL_PATH ), '/' );
					} else {
						$query_params = array();
						parse_str( (string) wp_parse_url( $video_url, PHP_URL_QUERY ), $query_params );
						if ( ! empty( $query_params['v'] ) ) {
							$youtube_id = (string) $query_params['v'];
						} else {
							$path_parts = explode( '/', trim( (string) wp_parse_url( $video_url, PHP_URL_PATH ), '/' ) );
							$youtube_id = isset( $path_parts[1] ) && 'embed' === ( $path_parts[0] ?? '' ) ? (string) $path_parts[1] : '';
						}
					}

					$youtube_id = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $youtube_id );

					if ( '' !== $youtube_id ) {
						$youtube_embed = 'https://www.youtube.com/embed/' . rawurlencode( $youtube_id ) . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . rawurlencode( $youtube_id ) . '&modestbranding=1&rel=0&playsinline=1&iv_load_policy=3';
						$slide_html    = '<iframe class="hester-slide-bg-embed" src="' . esc_url( $youtube_embed ) . '" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen loading="lazy" tabindex="-1" aria-hidden="true"></iframe>' . $slide_html;
					}
				} elseif ( false !== strpos( $host, 'vimeo.com' ) ) {
					$vimeo_id = '';
					$path     = trim( (string) wp_parse_url( $video_url, PHP_URL_PATH ), '/' );

					if ( '' !== $path ) {
						$path_parts = explode( '/', $path );
						$vimeo_id   = (string) end( $path_parts );
					}

					$vimeo_id = preg_replace( '/[^0-9]/', '', (string) $vimeo_id );

					if ( '' !== $vimeo_id ) {
						$vimeo_embed = 'https://player.vimeo.com/video/' . rawurlencode( $vimeo_id ) . '?autoplay=1&muted=1&loop=1&background=1&title=0&byline=0&portrait=0';
						$slide_html  = '<iframe class="hester-slide-bg-embed" src="' . esc_url( $vimeo_embed ) . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy" tabindex="-1" aria-hidden="true"></iframe>' . $slide_html;
					}
				} else {
					$slide_html = '<video class="hester-slide-bg-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true"><source src="' . esc_url( $video_url ) . '"></video>' . $slide_html;
				}
			}

			$slide_html = '<div ' . $this->get_render_attribute_string( 'slide_bg_' . $slide_index ) . '></div>' . $slide_html;

			$repeater_id = ! empty( $slide['_id'] ) ? sanitize_html_class( $slide['_id'] ) : (string) $slide_index;
			$slides[]    = '<div class="elementor-repeater-item-' . esc_attr( $repeater_id ) . ' swiper-slide" role="group" aria-roledescription="slide">' . $slide_html . '</div>';
			++$slide_count;
		}

		$show_dots    = in_array( $navigation, array( 'dots', 'both' ), true );
		$show_arrows  = in_array( $navigation, array( 'arrows', 'both' ), true );
		$slides_count = count( $settings['slides'] );
		?>
		<?php if ( ! $optimized_markup ) : ?>
		<div class="elementor-swiper">
		<?php endif; ?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div class="swiper-wrapper elementor-slides">
					<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $show_arrows ) : ?>
						<div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Previous slide', 'hester-core' ); ?>">
							<?php $this->render_swiper_button( 'previous' ); ?>
						</div>
						<div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Next slide', 'hester-core' ); ?>">
							<?php $this->render_swiper_button( 'next' ); ?>
						</div>
					<?php endif; ?>
					<?php if ( $show_dots ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php if ( ! $optimized_markup ) : ?>
		</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render swiper button icon.
	 *
	 * @param string $type Button type.
	 * @return void
	 */
	private function render_swiper_button( $type ) {
		$direction = 'next' === $type ? 'right' : 'left';

		if ( is_rtl() ) {
			$direction = 'right' === $direction ? 'left' : 'right';
		}

		$icon_value = 'eicon-chevron-' . $direction;

		Icons_Manager::render_icon(
			array(
				'library' => 'eicons',
				'value'   => $icon_value,
			),
			array(
				'aria-hidden' => 'true',
			)
		);
	}

}
