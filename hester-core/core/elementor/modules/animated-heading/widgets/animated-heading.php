<?php
namespace Hester_Core\Elementor\Modules\AnimatedHeading\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Hester_Core\Elementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class AnimatedHeading extends Base_Widget {

	public function get_name() {
		return 'hester-animated-heading';
	}

	public function get_title() {
		return __( 'Animated Heading', 'hester-core' );
	}

	public function get_icon() {
		return 'hester-icon eicon-animated-headline';
	}

	public function get_categories() {
		return array( 'hester-core' );
	}

	public function get_script_depends() {
		return array( 'hester-animated-heading', 'morphext', 'typed' );
	}

	public function get_style_depends() {
		return array( 'hester-animated-heading' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_animated_heading',
			array(
				'label' => __( 'Heading', 'hester-core' ),
			)
		);

		$this->add_control(
			'heading_layout',
			array(
				'label'   => __( 'Layout', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'animated',
				'options' => array(
					'animated' => __( 'Animated', 'hester-core' ),
					'typed'    => __( 'Typed', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'pre_heading',
			array(
				'label'       => __( 'Pre Heading', 'hester-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'This is an', 'hester-core' ),
				'placeholder' => __( 'Enter your prefix heading', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'animated_heading',
			array(
				'label'       => __( 'Heading', 'hester-core' ),
				'description' => __( 'Write animated heading here with comma separated. Such as Animated, Morphing, Awesome', 'hester-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Animated, Amazing, Awesome', 'hester-core' ),
				'placeholder' => __( 'Enter your animated heading', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'post_heading',
			array(
				'label'       => __( 'Post Heading', 'hester-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Heading', 'hester-core' ),
				'placeholder' => __( 'Enter your suffix heading', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
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
			'title_html_tag',
			array(
				'label'     => __( 'HTML Tag', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => hester_get_available_tags(),
				'default'   => 'h2',
				'condition' => array(
					'link[url]' => '',
				),
			)
		);

		$this->add_responsive_control(
			'heading_line_layout',
			array(
				'label'        => __( 'Line Layout', 'hester-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'default'      => 'inline',
				'options'      => array(
					'inline'  => array(
						'title' => __( 'Same Line', 'hester-core' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'stacked' => array(
						'title' => __( 'Separate Lines', 'hester-core' ),
						'icon'  => 'eicon-editor-list-ul',
					),
				),
				'prefix_class' => 'hester-heading-layout%s-',
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
				'default'      => 'center',
				'prefix_class' => 'elementor-align%s-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_animation',
			array(
				'label'     => __( 'Animation Options', 'hester-core' ),
				'condition' => array(
					'heading_animation!' => '',
				),
			)
		);

		$this->add_control(
			'heading_animation',
			array(
				'label'     => __( 'Animation', 'hester-core' ),
				'type'      => Controls_Manager::ANIMATION,
				'default'   => 'fadeIn',
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'animated',
				),
			)
		);

		$this->add_control(
			'heading_animation_duration',
			array(
				'label'     => __( 'Duration', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''     => __( 'Normal', 'hester-core' ),
					'slow' => __( 'Slow', 'hester-core' ),
					'fast' => __( 'Fast', 'hester-core' ),
				),
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'animated',
				),
			)
		);

		$this->add_control(
			'heading_animation_delay',
			array(
				'label'     => __( 'Delay (ms)', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2500,
				'min'       => 100,
				'max'       => 7000,
				'step'      => 100,
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'animated',
				),
			)
		);

		$this->add_control(
			'type_speed',
			array(
				'label'     => __( 'Type Speed', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 50,
				'min'       => 10,
				'max'       => 100,
				'step'      => 5,
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'typed',
				),
			)
		);

		$this->add_control(
			'start_delay',
			array(
				'label'     => __( 'Start Delay', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'typed',
				),
			)
		);

		$this->add_control(
			'back_speed',
			array(
				'label'     => __( 'Back Speed', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 30,
				'min'       => 0,
				'max'       => 100,
				'step'      => 2,
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'typed',
				),
			)
		);

		$this->add_control(
			'back_delay',
			array(
				'label'     => __( 'Back Delay', 'hester-core' ) . ' (ms)',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
				'min'       => 0,
				'max'       => 3000,
				'step'      => 50,
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'typed',
				),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'     => __( 'Loop', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'heading_animation!' => '',
					'heading_layout'     => 'typed',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pre_heading',
			array(
				'label'     => __( 'Pre Heading', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pre_heading!' => '',
				),
			)
		);

		$this->add_control(
			'pre_heading_color',
			array(
				'label'     => __( 'Pre Heading Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-heading-wrap .hester-pre-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pre_heading_typography',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-pre-heading',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'pre_heading_shadow',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-pre-heading',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_animated_heading',
			array(
				'label' => __( 'Animated Heading', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'animated_heading_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-heading-wrap .hester-heading-tag' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'animated_heading_typography',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-heading-tag',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'animated_heading_shadow',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-heading-tag',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_post_heading',
			array(
				'label'     => __( 'Post Heading', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_heading!' => '',
				),
			)
		);

		$this->add_control(
			'post_heading_color',
			array(
				'label'     => __( 'Post Heading Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-heading-wrap .hester-post-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'post_heading_typography',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-post-heading',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'post_heading_shadow',
				'selector' => '{{WRAPPER}} .hester-heading-wrap .hester-post-heading',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$id        = $this->get_id();
		$title_tag = $settings['title_html_tag'];

		$this->add_render_attribute( 'heading', 'class', 'hester-heading-tag' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'heading', 'href', esc_url( $settings['link']['url'] ) );

			if ( ! empty( $settings['link']['is_external'] ) ) {
				$this->add_render_attribute( 'heading', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'heading', 'rel', 'nofollow' );
			}

			$title_tag = 'a';
		}

		$type_heading = explode( ',', (string) $settings['animated_heading'] );
		$loop         = ( ! empty( $settings['loop'] ) && 'yes' === $settings['loop'] ) ? 'true' : 'false';

		$words       = array_filter( array_map( 'trim', $type_heading ) );
		$layout      = ( isset( $settings['heading_layout'] ) && 'typed' === $settings['heading_layout'] ) ? 'typed' : 'morphext';
		$animation   = ! empty( $settings['heading_animation'] ) ? $settings['heading_animation'] : 'fadeIn';
		$speed       = absint( $settings['heading_animation_delay'] ?? 2500 );
		$type_speed  = absint( $settings['type_speed'] ?? 50 );
		$start_delay = absint( $settings['start_delay'] ?? 1 );
		$back_speed  = absint( $settings['back_speed'] ?? 30 );
		$back_delay  = absint( $settings['back_delay'] ?? 500 );
		?>

		<div id="hester-animated-heading-<?php echo esc_attr( $id ); ?>" class="hester-heading-wrap">
			<<?php echo esc_html( hester_validate_html_tag( $title_tag ) ); ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>

				<?php if ( ! empty( $settings['pre_heading'] ) ) : ?>
					<span class="hester-pre-heading"><?php echo esc_html( $settings['pre_heading'] ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $settings['animated_heading'] ) && 'animated' === $settings['heading_layout'] ) : ?>
					<?php $animation_duration = ! empty( $settings['heading_animation_duration'] ) ? ' hester-animated-' . $settings['heading_animation_duration'] : ''; ?>
					<span class="hester-animated-heading hester-animated-heading-animated<?php echo esc_attr( $animation_duration ); ?>"
						data-layout="<?php echo esc_attr( $layout ); ?>"
						data-words="<?php echo esc_attr( wp_json_encode( array_values( $words ) ) ); ?>"
						data-animation="<?php echo esc_attr( $animation ); ?>"
						data-speed="<?php echo esc_attr( $speed ); ?>"
						data-backspeed="<?php echo esc_attr( $back_speed ); ?>"
						data-backdelay="<?php echo esc_attr( $back_delay ); ?>"><?php echo esc_html( implode( ', ', $words ) ); ?></span>
				<?php elseif ( ! empty( $settings['animated_heading'] ) && 'typed' === $settings['heading_layout'] ) : ?>
					<span class="hester-animated-heading hester-animated-heading-animated"
						data-layout="typed"
						data-words="<?php echo esc_attr( wp_json_encode( array_values( $words ) ) ); ?>"
						data-speed="<?php echo esc_attr( $type_speed ); ?>"
						data-startdelay="<?php echo esc_attr( $start_delay ); ?>"
						data-backspeed="<?php echo esc_attr( $back_speed ); ?>"
						data-backdelay="<?php echo esc_attr( $back_delay ); ?>"
					data-loop="<?php echo esc_attr( $loop ); ?>"></span>
				<?php endif; ?>

				<?php if ( ! empty( $settings['post_heading'] ) ) : ?>
					<span class="hester-post-heading"><?php echo esc_html( $settings['post_heading'] ); ?></span>
				<?php endif; ?>

			</<?php echo esc_html( hester_validate_html_tag( $title_tag ) ); ?>>
		</div>

		<?php
	}
}
