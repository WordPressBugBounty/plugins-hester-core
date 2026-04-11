<?php

namespace Hester_Core\Elementor\Modules\Slides\Controls;

use Elementor\Control_Hover_Animation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Control_Slides_Animation extends Control_Hover_Animation {
	const TYPE = 'hester_animation_slides_content';

	public function get_type(): string {
		return static::TYPE;
	}

	public static function get_animations(): array {
		return array(
			'fadeInDown'  => esc_html__( 'Down', 'hester-core' ),
			'fadeInUp'    => esc_html__( 'Up', 'hester-core' ),
			'fadeInRight' => esc_html__( 'Right', 'hester-core' ),
			'fadeInLeft'  => esc_html__( 'Left', 'hester-core' ),
			'zoomIn'      => esc_html__( 'Zoom', 'hester-core' ),
		);
	}
}
