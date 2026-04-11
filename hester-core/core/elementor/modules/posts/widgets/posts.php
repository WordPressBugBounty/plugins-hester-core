<?php
/**
 * Posts Widget
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Hester_Core\Elementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Posts widget.
 */
class Posts extends Base_Widget {

	/**
	 * Get widget slug.
	 *
	 * Kept stable for existing saved Elementor instances.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'hester-posts';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts', 'hester-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'hester-icon eicon-posts-grid';
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'posts', 'blog', 'archive', 'grid', 'cards' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'hester-posts' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_layout_controls();
		$this->register_content_controls();
		$this->register_query_controls();
		$this->register_card_style_controls();
		$this->register_image_style_controls();
		$this->register_badge_style_controls();
		$this->register_meta_style_controls();
		$this->register_title_style_controls();
		$this->register_excerpt_style_controls();
		$this->register_button_style_controls();
		$this->register_pagination_style_controls();
	}

	/**
	 * Register layout controls.
	 *
	 * @return void
	 */
	private function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'hester-core' ),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'       => __( 'Posts Per Page', 'hester-core' ),
				'description' => __( 'Use -1 to show all matching posts.', 'hester-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
				'min'         => -1,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'hester-core' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .hester-posts' => '--hester-posts-columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => __( 'Columns Gap', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts' => '--hester-posts-column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => __( 'Rows Gap', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts' => '--hester-posts-row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'masonry',
			array(
				'label'              => __( 'Masonry Layout', 'hester-core' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'   => __( 'Skin', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cards',
				'options' => array(
					'cards'        => __( 'Cards', 'hester-core' ),
					'classic'      => __( 'Classic', 'hester-core' ),
					'full_content' => __( 'Full Content', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'     => __( 'Image Position', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => array(
					'top'   => __( 'Top', 'hester-core' ),
					'left'  => __( 'Left', 'hester-core' ),
					'right' => __( 'Right', 'hester-core' ),
					'none'  => __( 'None', 'hester-core' ),
				),
				'condition' => array(
					'show_image' => 'yes',
					'skin'       => 'classic',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => __( 'Image Width', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 100,
						'max' => 900,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__media' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_image'      => 'yes',
					'skin!'           => 'cards',
					'image_position!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'content_alignment',
			array(
				'label'                => __( 'Content Alignment', 'hester-core' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'left',
				'options'              => array(
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
				'selectors_dictionary' => array(
					'left'   => 'text-align: left; align-items: flex-start; --hp-meta-justify: flex-start',
					'center' => 'text-align: center; align-items: center; --hp-meta-justify: center',
					'right'  => 'text-align: right; align-items: flex-end; --hp-meta-justify: flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .hester-posts__content' => '{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_ratio',
			array(
				'label'     => __( 'Image Ratio', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '16-9',
				'options'   => array(
					'auto' => __( 'Auto', 'hester-core' ),
					'16-9' => __( 'Landscape', 'hester-core' ),
					'4-3'  => __( 'Classic', 'hester-core' ),
					'1-1'  => __( 'Square', 'hester-core' ),
					'3-4'  => __( 'Portrait', 'hester-core' ),
				),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'   => __( 'Pagination', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'                      => __( 'None', 'hester-core' ),
					'numbers'                   => __( 'Numbers', 'hester-core' ),
					'prev_next'                 => __( 'Previous / Next', 'hester-core' ),
					'numbers_and_prev_next'     => __( 'Numbers, Previous / Next', 'hester-core' ),
					'load_more_on_click'        => __( 'Load on Click', 'hester-core' ),
					'load_more_infinite_scroll' => __( 'Infinite Scroll', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'pagination_heading',
			array(
				'label'     => __( 'Pagination Settings', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'pagination_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'       => __( 'Page Limit', 'hester-core' ),
				'description' => __( 'Leave empty to use all pages.', 'hester-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'condition'   => array(
					'pagination_type!' => array( 'none', 'load_more_on_click', 'load_more_infinite_scroll' ),
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => __( 'Shorten', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'pagination_type' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'       => __( 'Previous Label', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Previous', 'hester-core' ),
				'label_block' => true,
				'condition'   => array(
					'pagination_type' => array( 'prev_next', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'       => __( 'Next Label', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Next', 'hester-core' ),
				'label_block' => true,
				'condition'   => array(
					'pagination_type' => array( 'prev_next', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_position',
			array(
				'label'       => __( 'Alignment', 'hester-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'hester-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'hester-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'hester-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .hester-pagination ul.page-numbers' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .hester-posts__load-more' => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'pagination_type!' => array( 'none', 'load_more_infinite_scroll' ),
				),
			)
		);

		$this->add_control(
			'load_more_button_text',
			array(
				'label'       => __( 'Button Text', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Load More', 'hester-core' ),
				'label_block' => true,
				'condition'   => array(
					'pagination_type' => 'load_more_on_click',
				),
			)
		);

		$this->add_control(
			'load_more_no_posts_message_switcher',
			array(
				'label'     => __( 'Custom Messages', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'pagination_type' => array( 'load_more_on_click', 'load_more_infinite_scroll' ),
				),
			)
		);

		$this->add_control(
			'load_more_no_posts_custom_message',
			array(
				'label'       => __( 'No More Posts Message', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'No more posts to show', 'hester-core' ),
				'label_block' => true,
				'condition'   => array(
					'pagination_type'                     => array( 'load_more_on_click', 'load_more_infinite_scroll' ),
					'load_more_no_posts_message_switcher' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register query controls.
	 *
	 * @return void
	 */
	private function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'hester-core' ),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => __( 'Post Type', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_available_post_types(),
			)
		);

		$this->add_control(
			'query_source',
			array(
				'label'   => __( 'Source', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest_posts',
				'options' => array(
					'latest_posts'     => __( 'Latest Posts', 'hester-core' ),
					'current_query'    => __( 'Current Query', 'hester-core' ),
					'manual_selection' => __( 'Manual Selection', 'hester-core' ),
					'related'          => __( 'Related', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'related_taxonomies',
			array(
				'label'       => __( 'Term', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_supported_taxonomies(),
				'condition'   => array(
					'query_source' => 'related',
				),
			)
		);

		$this->add_control(
			'related_fallback',
			array(
				'label'     => __( 'Fallback', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fallback_none',
				'options'   => array(
					'fallback_none'   => __( 'None', 'hester-core' ),
					'fallback_by_id'  => __( 'Manual Selection', 'hester-core' ),
					'fallback_recent' => __( 'Recent Posts', 'hester-core' ),
				),
				'condition' => array(
					'query_source' => 'related',
				),
			)
		);

		$this->add_control(
			'related_fallback_ids',
			array(
				'label'       => __( 'Manual Selection', 'hester-core' ),
				'description' => __( 'Enter post IDs separated by commas.', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'query_source'     => 'related',
					'related_fallback' => 'fallback_by_id',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => __( 'Order', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => __( 'Descending', 'hester-core' ),
					'ASC'  => __( 'Ascending', 'hester-core' ),
				),
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => __( 'Order By', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'          => __( 'Date', 'hester-core' ),
					'title'         => __( 'Title', 'hester-core' ),
					'modified'      => __( 'Modified', 'hester-core' ),
					'author'        => __( 'Author', 'hester-core' ),
					'rand'          => __( 'Random', 'hester-core' ),
					'comment_count' => __( 'Comment Count', 'hester-core' ),
					'menu_order'    => __( 'Menu Order', 'hester-core' ),
				),
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'ignore_sticky_posts',
			array(
				'label'     => __( 'Ignore Sticky Posts', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'query_include_heading',
			array(
				'label'     => __( 'Include', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'query_source!' => 'current_query',
				),
			)
		);

		$this->add_control(
			'include_categories_ids',
			array(
				'label'       => __( 'Include Categories', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_terms_options( 'category' ),
				'condition'   => array(
					'post_type'    => 'post',
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'include_ids',
			array(
				'label'       => __( 'Manual Selection', 'hester-core' ),
				'description' => __( 'Enter post IDs separated by commas.', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'query_source' => 'manual_selection',
				),
			)
		);

		$this->add_control(
			'include_authors',
			array(
				'label'       => __( 'Authors', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_authors_options(),
				'condition'   => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'exclude_categories_ids',
			array(
				'label'       => __( 'Exclude Categories', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_terms_options( 'category' ),
				'condition'   => array(
					'post_type'    => 'post',
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'include_tags_ids',
			array(
				'label'       => __( 'Include Tags', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_terms_options( 'post_tag' ),
				'condition'   => array(
					'post_type'    => 'post',
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'exclude_tags_ids',
			array(
				'label'       => __( 'Exclude Tags', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_terms_options( 'post_tag' ),
				'condition'   => array(
					'post_type'    => 'post',
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'query_exclude_heading',
			array(
				'label'     => __( 'Exclude', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'exclude_authors',
			array(
				'label'       => __( 'Authors', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_authors_options(),
				'condition'   => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'exclude_ids',
			array(
				'label'       => __( 'Exclude By IDs', 'hester-core' ),
				'description' => __( 'Enter post IDs separated by commas.', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'       => __( 'Offset', 'hester-core' ),
				'description' => __( 'Skip first N posts from query results.', 'hester-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 0,
				'condition'   => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'exclude_current_post',
			array(
				'label'     => __( 'Exclude Current Post', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'select_date',
			array(
				'label'     => __( 'Date', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'anytime',
				'options'   => array(
					'anytime' => __( 'All', 'hester-core' ),
					'today'   => __( 'Past Day', 'hester-core' ),
					'week'    => __( 'Past Week', 'hester-core' ),
					'month'   => __( 'Past Month', 'hester-core' ),
					'quarter' => __( 'Past Quarter', 'hester-core' ),
					'year'    => __( 'Past Year', 'hester-core' ),
					'exact'   => __( 'Custom', 'hester-core' ),
				),
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->add_control(
			'date_after',
			array(
				'label'     => __( 'After', 'hester-core' ),
				'type'      => Controls_Manager::DATE_TIME,
				'condition' => array(
					'query_source' => 'latest_posts',
					'select_date'  => 'exact',
				),
			)
		);

		$this->add_control(
			'date_before',
			array(
				'label'     => __( 'Before', 'hester-core' ),
				'type'      => Controls_Manager::DATE_TIME,
				'condition' => array(
					'query_source' => 'latest_posts',
					'select_date'  => 'exact',
				),
			)
		);

		$this->add_control(
			'avoid_duplicates',
			array(
				'label'     => __( 'Avoid Duplicates', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'query_source' => 'latest_posts',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register content controls.
	 *
	 * @return void
	 */
	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'hester-core' ),
			)
		);

		$this->add_control(
			'content_image_heading',
			array(
				'label' => __( 'Image', 'hester-core' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'   => __( 'Image', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => __( 'Image Size', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium_large',
				'options'   => $this->get_image_sizes(),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'image_hover_effect',
			array(
				'label'     => __( 'Hover Effect', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'    => __( 'None', 'hester-core' ),
					'zoom'    => __( 'Zoom', 'hester-core' ),
					'overlay' => __( 'Overlay Fade', 'hester-core' ),
				),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_badges',
			array(
				'label'   => __( 'Category Badges', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'badges_limit',
			array(
				'label'     => __( 'Badge Limit', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'min'       => 1,
				'condition' => array(
					'show_badges' => 'yes',
				),
			)
		);

		$this->add_control(
			'content_text_heading',
			array(
				'label'     => __( 'Text', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => hester_get_available_tags(),
				'condition' => array(
					'title' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_data',
			array(
				'label'       => __( 'Meta Data', 'hester-core' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'default'     => array( 'author', 'date' ),
				'options'     => array(
					'author'     => __( 'Author', 'hester-core' ),
					'date'       => __( 'Date', 'hester-core' ),
					'time'       => __( 'Time', 'hester-core' ),
					'modified'   => __( 'Date Modified', 'hester-core' ),
					'categories' => __( 'Categories', 'hester-core' ),
					'comments'   => __( 'Comments', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'meta_separator',
			array(
				'label'     => __( 'Meta Separator', 'hester-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '|',
				'maxlength' => 3,
				'condition' => array(
					'meta_data!' => '',
				),
			)
		);

		$this->add_control(
			'meta_show_icons',
			array(
				'label'     => __( 'Show Icons', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'meta_data!' => '',
				),
			)
		);

		$this->add_control(
			'meta_show_author_avatar',
			array(
				'label'     => __( 'Show Author Avatar', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'meta_data' => 'author',
				),
			)
		);

		$this->add_control(
			'meta_author_avatar_position',
			array(
				'label'     => __( 'Author Avatar Position', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => __( 'Before Name', 'hester-core' ),
					'after'  => __( 'After Name', 'hester-core' ),
				),
				'condition' => array(
					'meta_data'                => 'author',
					'meta_show_author_avatar' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_position',
			array(
				'label'   => __( 'Meta Position', 'hester-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'before_title',
				'options' => array(
					'before_title' => __( 'Before Title', 'hester-core' ),
					'after_title'  => __( 'After Title', 'hester-core' ),
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => __( 'Date Format', 'hester-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default'  => __( 'Default', 'hester-core' ),
					'relative' => __( 'Relative', 'hester-core' ),
					'custom'   => __( 'Custom', 'hester-core' ),
				),
				'condition' => array(
					'meta_data' => 'date',
				),
			)
		);

		$this->add_control(
			'date_custom_format',
			array(
				'label'       => __( 'Custom Date Format', 'hester-core' ),
				'description' => __( 'Use PHP date format, e.g. F j, Y', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'meta_data'   => 'date',
					'date_format' => 'custom',
				),
			)
		);

		$this->add_control(
			'excerpt',
			array(
				'label'     => __( 'Excerpt', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'skin!' => 'full_content',
				),
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'hester-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 18,
				'min'       => 0,
				'condition' => array(
					'excerpt' => 'yes',
					'skin!'   => 'full_content',
				),
			)
		);

		$this->add_control(
			'apply_to_custom_excerpt',
			array(
				'label'     => __( 'Apply to custom Excerpt', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'excerpt' => 'yes',
					'skin!'   => 'full_content',
				),
			)
		);

		$this->add_control(
			'content_read_more_heading',
			array(
				'label'     => __( 'Read More', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'skin!' => 'full_content',
				),
			)
		);

		$this->add_control(
			'show_read_more',
			array(
				'label'     => __( 'Read More', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'skin!' => 'full_content',
				),
			)
		);

		$this->add_control(
			'readmore_text',
			array(
				'label'       => __( 'Text', 'hester-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read More »', 'hester-core' ),
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
				'condition'   => array(
					'show_read_more' => 'yes',
					'skin!'          => 'full_content',
				),
			)
		);

		$this->add_control(
			'read_more_alignment',
			array(
				'label'     => __( 'Automatically align buttons', 'hester-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'show_read_more' => 'yes',
					'skin!'          => 'full_content',
				),
			)
		);

		$this->add_control(
			'open_new_tab',
			array(
				'label'   => __( 'Open in new window', 'hester-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card style controls.
	 *
	 * @return void
	 */
	private function register_card_style_controls() {
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => __( 'Card', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_card_style' );

		$this->start_controls_tab(
			'tab_card_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'card_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__post' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .hester-posts__post',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .hester-posts__post',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_card_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'card_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__post:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_hover_border',
				'selector' => '{{WRAPPER}} .hester-posts__post:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_hover_box_shadow',
				'selector' => '{{WRAPPER}} .hester-posts__post:hover',
			)
		);

		$this->add_responsive_control(
			'card_hover_translate_y',
			array(
				'label'      => __( 'Lift on Hover', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -30,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__post:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'card_hover_transition_duration',
			array(
				'label'      => __( 'Transition Duration', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min' => 0,
						'max' => 1200,
					),
				),
				'default'    => array(
					'size' => 250,
					'unit' => 'ms',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__post' => 'transition-duration: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'card_content_padding',
			array(
				'label'      => __( 'Content Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'top'    => 24,
					'right'  => 24,
					'bottom' => 24,
					'left'   => 24,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register image style controls.
	 *
	 * @return void
	 */
	private function register_image_style_controls() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label'     => __( 'Image', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_image'      => 'yes',
					'image_position!' => 'none',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'      => __( 'Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts--image-top .hester-posts__media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hester-posts--image-left .hester-posts__media' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hester-posts--image-right .hester-posts__media' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register badge style controls.
	 *
	 * @return void
	 */
	private function register_badge_style_controls() {
		$this->start_controls_section(
			'section_style_badges',
			array(
				'label'     => __( 'Category Badges', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_badges' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .hester-posts__badge',
			)
		);

		$this->add_responsive_control(
			'badge_gap',
			array(
				'label'      => __( 'Gap Between Badges', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__badges' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_bottom_spacing',
			array(
				'label'      => __( 'Bottom Spacing (no image)', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__content .hester-posts__badges' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_badge_style' );

		$this->start_controls_tab(
			'tab_badge_normal',
			array(
				'label' => __( 'Normal', 'hester-core' ),
			)
		);

		$this->add_control(
			'badge_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_badge_hover',
			array(
				'label' => __( 'Hover', 'hester-core' ),
			)
		);

		$this->add_control(
			'badge_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__badge:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__badge:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register meta style controls.
	 *
	 * @return void
	 */
	private function register_meta_style_controls() {
		$this->start_controls_section(
			'section_style_meta',
			array(
				'label' => __( 'Meta', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__meta, {{WRAPPER}} .hester-posts__meta a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_hover_color',
			array(
				'label'     => __( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__meta a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .hester-posts__meta',
			)
		);

		$this->add_control(
			'meta_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'meta_author_avatar_heading',
			array(
				'label'     => __( 'Author Avatar', 'hester-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'meta_data'                => 'author',
					'meta_show_author_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_author_avatar_size',
			array(
				'label'      => __( 'Size', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__author-avatar-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'meta_data'                => 'author',
					'meta_show_author_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_author_avatar_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__author-avatar-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'meta_data'                => 'author',
					'meta_show_author_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_author_avatar_gap',
			array(
				'label'      => __( 'Gap', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__author-link' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'meta_data'                => 'author',
					'meta_show_author_avatar' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register title style controls.
	 *
	 * @return void
	 */
	private function register_title_style_controls() {
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Hover Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hester-posts__title',
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register excerpt style controls.
	 *
	 * @return void
	 */
	private function register_excerpt_style_controls() {
		$this->start_controls_section(
			'section_style_excerpt',
			array(
				'label' => __( 'Excerpt', 'hester-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .hester-posts__excerpt',
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'hester-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register button style controls.
	 *
	 * @return void
	 */
	private function register_button_style_controls() {
		$this->start_controls_section(
			'section_style_button',
			array(
				'label'     => __( 'Read More', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_read_more' => 'yes',
					'skin!'          => 'full_content',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .hester-posts__button',
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
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .hester-posts__button',
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
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hester-posts__button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'hester-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hester-posts__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register pagination style controls.
	 *
	 * @return void
	 */
	private function register_pagination_style_controls() {
		$this->start_controls_section(
			'section_style_pagination',
			array(
				'label'     => __( 'Pagination', 'hester-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type!' => array( 'none', 'load_more_on_click', 'load_more_infinite_scroll' ),
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .page-numbers a, {{WRAPPER}} .page-numbers span:not(.elementor-screen-only)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_border_color',
			array(
				'label'     => __( 'Border Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .page-numbers a, {{WRAPPER}} .page-numbers span:not(.elementor-screen-only)' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Active Text Color', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .page-numbers .current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_active_background',
			array(
				'label'     => __( 'Active Background', 'hester-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .page-numbers .current' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get post type options.
	 *
	 * @return array
	 */
	private function get_available_post_types() {
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		$options = array();

		foreach ( $post_types as $post_type ) {
			$options[ $post_type->name ] = $post_type->labels->singular_name;
		}

		return $options;
	}

	/**
	 * Get image size options.
	 *
	 * @return array
	 */
	private function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$registered_sizes = get_intermediate_image_sizes();
		$options          = array();

		foreach ( $registered_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$width  = (int) get_option( $size . '_size_w' );
				$height = (int) get_option( $size . '_size_h' );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$width  = (int) $_wp_additional_image_sizes[ $size ]['width'];
				$height = (int) $_wp_additional_image_sizes[ $size ]['height'];
			} else {
				continue;
			}

			$options[ $size ] = sprintf(
				'%1$s - %2$d x %3$d',
				ucwords( str_replace( '_', ' ', $size ) ),
				$width,
				$height
			);
		}

		$options['full'] = _x( 'Full', 'Image Size Control', 'hester-core' );

		return $options;
	}

	/**
	 * Get taxonomy terms as control options.
	 *
	 * @param string $taxonomy Taxonomy key.
	 * @return array
	 */
	private function get_terms_options( $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$terms   = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);
		$options = array();

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ (string) $term->term_id ] = $term->name;
		}

		return $options;
	}

	/**
	 * Get author list as control options.
	 *
	 * @return array
	 */
	private function get_authors_options() {
		$users = get_users(
			array(
				'capability' => 'edit_posts',
				'fields'     => array( 'ID', 'display_name' ),
			)
		);

		$options = array();

		foreach ( $users as $user ) {
			$options[ (string) $user->ID ] = $user->display_name;
		}

		return $options;
	}

	/**
	 * Get supported taxonomies for related queries.
	 *
	 * @return array
	 */
	private function get_supported_taxonomies() {
		$taxonomies = get_taxonomies(
			array(
				'public' => true,
			),
			'objects'
		);

		$options = array();

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Parse IDs from string/array.
	 *
	 * @param mixed $value Raw value.
	 * @return array
	 */
	private function parse_id_list( $value ) {
		if ( empty( $value ) ) {
			return array();
		}

		if ( is_array( $value ) ) {
			$parts = $value;
		} else {
			$parts = explode( ',', (string) $value );
		}

		$parts = array_map( 'trim', $parts );
		$parts = array_map( 'absint', $parts );

		return array_values( array_filter( array_unique( $parts ) ) );
	}

	/**
	 * Parse comma-separated slugs.
	 *
	 * @param string $value Raw control value.
	 * @return array
	 */
	private function parse_slug_list( $value ) {
		if ( empty( $value ) ) {
			return array();
		}

		$parts = array_map( 'trim', explode( ',', (string) $value ) );
		$parts = array_map( 'sanitize_title', $parts );

		return array_values( array_filter( array_unique( $parts ) ) );
	}

	/**
	 * Get the current page for pagination.
	 *
	 * @return int
	 */
	private function get_current_page() {
		if ( get_query_var( 'paged' ) ) {
			return absint( get_query_var( 'paged' ) );
		}

		if ( get_query_var( 'page' ) ) {
			return absint( get_query_var( 'page' ) );
		}

		return 1;
	}

	/**
	 * Normalize pagination type with backward compatibility.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_pagination_type( array $settings ) {
		if ( ! empty( $settings['pagination_type'] ) ) {
			return $settings['pagination_type'];
		}

		if ( 'yes' === ( $settings['pagination'] ?? 'no' ) ) {
			return 'numbers';
		}

		return 'none';
	}

	/**
	 * Build query args.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	private function get_query_args( array $settings ) {
		$post_type      = ! empty( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';
		$query_source   = ! empty( $settings['query_source'] ) ? $settings['query_source'] : 'latest_posts';
		$posts_per_page = isset( $settings['count'] ) ? intval( $settings['count'] ) : (int) get_option( 'posts_per_page' );
		$order          = ! empty( $settings['order'] ) ? $settings['order'] : 'DESC';
		$orderby        = ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date';
		$offset         = isset( $settings['offset'] ) ? max( 0, absint( $settings['offset'] ) ) : 0;
		$pagination     = $this->get_pagination_type( $settings );
		$current_page   = 'none' !== $pagination ? $this->get_current_page() : 1;
		$orderby        = in_array( $orderby, array( 'date', 'title', 'modified', 'author', 'rand', 'comment_count', 'menu_order', 'ID' ), true ) ? $orderby : 'date';

		$args = array(
			'post_type'           => post_type_exists( $post_type ) ? $post_type : 'post',
			'posts_per_page'      => 0 === $posts_per_page ? (int) get_option( 'posts_per_page' ) : $posts_per_page,
			'paged'               => $current_page,
			'ignore_sticky_posts' => 'yes' === ( $settings['ignore_sticky_posts'] ?? 'yes' ),
			'post_status'         => 'publish',
			'order'               => in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'DESC',
			'orderby'             => $orderby,
		);

		if ( 'none' === $pagination ) {
			$args['no_found_rows'] = true;
		}

		if ( 'current_query' === $query_source ) {
			$args['no_found_rows'] = false;
			global $wp_query;

			if ( $wp_query instanceof \WP_Query ) {
				$allowed_keys = array(
					's',
					'cat',
					'category_name',
					'category__in',
					'category__not_in',
					'tag',
					'tag_id',
					'tag__in',
					'tag__not_in',
					'tax_query',
					'author',
					'author__in',
					'author__not_in',
					'meta_query',
				);

				foreach ( $allowed_keys as $key ) {
					if ( isset( $wp_query->query_vars[ $key ] ) ) {
						$args[ $key ] = $wp_query->query_vars[ $key ];
					}
				}
			}

			return $args;
		}

		if ( 'related' === $query_source ) {
			$current_post_id = get_queried_object_id();

			if ( $current_post_id > 0 ) {
				$taxonomies = $settings['related_taxonomies'] ?? array();

				if ( ! is_array( $taxonomies ) || empty( $taxonomies ) ) {
					$taxonomies = get_object_taxonomies( get_post_type( $current_post_id ), 'names' );
				}

				$tax_query = array();

				foreach ( $taxonomies as $taxonomy ) {
					$term_ids = wp_get_post_terms( $current_post_id, $taxonomy, array( 'fields' => 'ids' ) );

					if ( ! is_wp_error( $term_ids ) && ! empty( $term_ids ) ) {
						$tax_query[] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => array_map( 'absint', $term_ids ),
							'operator' => 'IN',
						);
					}
				}

				if ( ! empty( $tax_query ) ) {
					$args['tax_query']    = array_merge( array( 'relation' => 'OR' ), $tax_query );
					$args['post__not_in'] = array( $current_post_id );
					return $args;
				}
			}

			$related_fallback = $settings['related_fallback'] ?? 'fallback_none';

			if ( 'fallback_by_id' === $related_fallback ) {
				$manual_fallback_ids = $this->parse_id_list( $settings['related_fallback_ids'] ?? '' );
				$args['post__in']    = ! empty( $manual_fallback_ids ) ? $manual_fallback_ids : array( 0 );
				$args['orderby']     = 'post__in';
			} elseif ( 'fallback_recent' !== $related_fallback ) {
				$args['post__in'] = array( 0 );
			}

			return $args;
		}

		if ( $offset > 0 ) {
			if ( $current_page > 1 && $args['posts_per_page'] > 0 ) {
				$args['offset'] = $offset + ( ( $current_page - 1 ) * $args['posts_per_page'] );
			} else {
				$args['offset'] = $offset;
			}
		}

		if ( 'yes' === ( $settings['exclude_current_post'] ?? 'yes' ) ) {
			$current_post_id = get_queried_object_id();
			if ( $current_post_id > 0 ) {
				$args['post__not_in']   = $args['post__not_in'] ?? array();
				$args['post__not_in'][] = $current_post_id;
			}
		}

		$date_after  = ! empty( $settings['date_after'] ) ? sanitize_text_field( $settings['date_after'] ) : '';
		$date_before = ! empty( $settings['date_before'] ) ? sanitize_text_field( $settings['date_before'] ) : '';
		$select_date = $settings['select_date'] ?? 'anytime';

		if ( 'today' === $select_date ) {
			$args['date_query'] = array(
				'after'     => '1 day ago',
				'inclusive' => true,
			);
		} elseif ( 'week' === $select_date ) {
			$args['date_query'] = array(
				'after'     => '1 week ago',
				'inclusive' => true,
			);
		} elseif ( 'month' === $select_date ) {
			$args['date_query'] = array(
				'after'     => '1 month ago',
				'inclusive' => true,
			);
		} elseif ( 'quarter' === $select_date ) {
			$args['date_query'] = array(
				'after'     => '3 months ago',
				'inclusive' => true,
			);
		} elseif ( 'year' === $select_date ) {
			$args['date_query'] = array(
				'after'     => '1 year ago',
				'inclusive' => true,
			);
		} elseif ( ( '' !== $date_after || '' !== $date_before ) && 'exact' === $select_date ) {
			$args['date_query'] = array();

			if ( '' !== $date_after ) {
				$args['date_query']['after'] = $date_after;
			}

			if ( '' !== $date_before ) {
				$args['date_query']['before'] = $date_before;
			}

			$args['date_query']['inclusive'] = true;
		}

		$include_posts = $this->parse_id_list( $settings['include_ids'] ?? '' );
		$exclude_posts = $this->parse_id_list( $settings['exclude_ids'] ?? '' );

		if ( 'manual_selection' === $query_source ) {
			$args['ignore_sticky_posts'] = true;
			$args['post__in']            = ! empty( $include_posts ) ? $include_posts : array( 0 );
			$args['orderby']             = 'post__in';
			$args['post__not_in']        = array();

			if ( $args['posts_per_page'] > 0 ) {
				$args['posts_per_page'] = min( $args['posts_per_page'], count( $args['post__in'] ) );
			} else {
				$args['posts_per_page'] = count( $args['post__in'] );
			}

			return $args;
		}

		if ( ! empty( $include_posts ) ) {
			$args['post__in'] = $include_posts;
			$args['orderby']  = 'post__in';
		}

		if ( ! empty( $exclude_posts ) ) {
			$args['post__not_in'] = $exclude_posts;
		}

		if ( isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array_values( array_unique( array_map( 'absint', $args['post__not_in'] ) ) );
		}

		if ( 'yes' === ( $settings['avoid_duplicates'] ?? 'no' ) ) {
			global $hester_posts_widget_seen_ids;

			if ( ! is_array( $hester_posts_widget_seen_ids ) ) {
				$hester_posts_widget_seen_ids = array();
			}

			$args['post__not_in'] = array_values( array_unique( array_merge( $args['post__not_in'] ?? array(), $hester_posts_widget_seen_ids ) ) );
		}

		$include_authors = $this->parse_id_list( $settings['include_authors'] ?? array() );
		$exclude_authors = $this->parse_id_list( $settings['exclude_authors'] ?? array() );

		if ( ! empty( $include_authors ) ) {
			$args['author__in'] = $include_authors;
		}

		if ( ! empty( $exclude_authors ) ) {
			$args['author__not_in'] = $exclude_authors;
		}

		$include_terms = $this->parse_slug_list( $settings['include_categories'] ?? '' );
		$exclude_terms = $this->parse_slug_list( $settings['exclude_categories'] ?? '' );
		$tax_query     = array();

		if ( is_object_in_taxonomy( $args['post_type'], 'category' ) ) {
			$include_category_ids = $this->parse_id_list( $settings['include_categories_ids'] ?? array() );
			$exclude_category_ids = $this->parse_id_list( $settings['exclude_categories_ids'] ?? array() );

			if ( ! empty( $include_category_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $include_category_ids,
					'operator' => 'IN',
				);
			}

			if ( ! empty( $exclude_category_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $exclude_category_ids,
					'operator' => 'NOT IN',
				);
			}

			if ( ! empty( $include_terms ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $include_terms,
					'operator' => 'IN',
				);
			}

			if ( ! empty( $exclude_terms ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $exclude_terms,
					'operator' => 'NOT IN',
				);
			}
		}

		if ( is_object_in_taxonomy( $args['post_type'], 'post_tag' ) ) {
			$include_tag_ids = $this->parse_id_list( $settings['include_tags_ids'] ?? array() );
			$exclude_tag_ids = $this->parse_id_list( $settings['exclude_tags_ids'] ?? array() );

			if ( ! empty( $include_tag_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $include_tag_ids,
					'operator' => 'IN',
				);
			}

			if ( ! empty( $exclude_tag_ids ) ) {
				$tax_query[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $exclude_tag_ids,
					'operator' => 'NOT IN',
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
		}

		return $args;
	}

	/**
	 * Render meta line.
	 *
	 * @param array  $settings Widget settings.
	 * @param string $post_type Current post type.
	 * @return void
	 */
	private function render_meta( array $settings, $post_type ) {
		$meta_data = $settings['meta_data'] ?? array();

		if ( ! is_array( $meta_data ) ) {
			$meta_data = array();
		}

		$show_author   = in_array( 'author', $meta_data, true ) || ( empty( $meta_data ) && 'yes' === ( $settings['author'] ?? 'yes' ) );
		$show_date     = in_array( 'date', $meta_data, true ) || ( empty( $meta_data ) && 'yes' === ( $settings['date'] ?? 'yes' ) );
		$show_time     = in_array( 'time', $meta_data, true );
		$show_modified = in_array( 'modified', $meta_data, true );
		$show_category = ( in_array( 'categories', $meta_data, true ) || ( empty( $meta_data ) && 'yes' === ( $settings['cat'] ?? 'yes' ) ) ) && is_object_in_taxonomy( $post_type, 'category' );
		$show_comments = ( in_array( 'comments', $meta_data, true ) || ( empty( $meta_data ) && 'yes' === ( $settings['comments'] ?? 'no' ) ) ) && comments_open() && ! post_password_required();

		if ( ! $show_author && ! $show_date && ! $show_time && ! $show_modified && ! $show_category && ! $show_comments ) {
			return;
		}

		$meta_items     = array();
		$meta_separator = isset( $settings['meta_separator'] ) ? sanitize_text_field( $settings['meta_separator'] ) : '|';
		$meta_separator = '' === trim( $meta_separator ) ? '|' : $meta_separator;
		$show_icons     = 'yes' === ( $settings['meta_show_icons'] ?? 'no' );
		$show_avatar    = 'yes' === ( $settings['meta_show_author_avatar'] ?? 'no' );
		$avatar_pos     = ( isset( $settings['meta_author_avatar_position'] ) && in_array( $settings['meta_author_avatar_position'], array( 'before', 'after' ), true ) ) ? $settings['meta_author_avatar_position'] : 'before';

		if ( $show_author ) {
			$author_id   = (int) get_the_author_meta( 'ID' );
			$author_name = get_the_author();

			if ( $author_id > 0 ) {
				$author_label_html = '<span class="hester-posts__author-name">' . esc_html( $author_name ) . '</span>';
				$author_avatar_html = '';

				if ( $show_avatar ) {
					$author_avatar_html = '<span class="hester-posts__author-avatar" aria-hidden="true">' . get_avatar( $author_id, 32, '', '', array( 'class' => 'hester-posts__author-avatar-image' ) ) . '</span>';
				}

				if ( $show_avatar && 'after' === $avatar_pos ) {
					$author_inner_html = $author_label_html . $author_avatar_html;
				} else {
					$author_inner_html = $author_avatar_html . $author_label_html;
				}

				$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--author"><a class="hester-posts__author-link" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . $author_inner_html . '</a></span>';
			} else {
				$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--author">' . esc_html( $author_name ) . '</span>';
			}
		}

		if ( $show_date ) {
			$date_format = $settings['date_format'] ?? 'default';
			$date_text   = '';

			if ( 'relative' === $date_format ) {
				$date_text = sprintf(
					/* translators: %s: human-readable time difference. */
					__( '%s ago', 'hester-core' ),
					human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
				);
			} elseif ( 'custom' === $date_format && ! empty( $settings['date_custom_format'] ) ) {
				$date_text = get_the_date( sanitize_text_field( $settings['date_custom_format'] ) );
			} else {
				$date_text = get_the_date();
			}

			$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--date">' . esc_html( $date_text ) . '</span>';
		}

		if ( $show_time ) {
			$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--time">' . esc_html( get_the_time() ) . '</span>';
		}

		if ( $show_modified ) {
			$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--modified">' . esc_html( get_the_modified_date() ) . '</span>';
		}

		if ( $show_category ) {
			$terms = get_the_term_list( get_the_ID(), 'category', '', ', ' );
			if ( $terms ) {
				$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--terms">' . wp_kses_post( $terms ) . '</span>';
			}
		}

		if ( $show_comments ) {
			$comments_text = sprintf( _n( '%s Comment', '%s Comments', get_comments_number(), 'hester-core' ), number_format_i18n( get_comments_number() ) );
			$comments_link = get_comments_link( get_the_ID() );

			if ( ! empty( $comments_link ) ) {
				$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--comments"><a href="' . esc_url( $comments_link ) . '">' . esc_html( $comments_text ) . '</a></span>';
			} else {
				$meta_items[] = '<span class="hester-posts__meta-item hester-posts__meta-item--comments">' . esc_html( $comments_text ) . '</span>';
			}
		}

		if ( empty( $meta_items ) ) {
			return;
		}

		$output = array();

		foreach ( $meta_items as $index => $meta_item ) {
			if ( $index > 0 ) {
				$output[] = '<span class="hester-posts__meta-separator" aria-hidden="true">' . esc_html( $meta_separator ) . '</span>';
			}

			$output[] = $meta_item;
		}

		$meta_class = 'hester-posts__meta' . ( $show_icons ? ' hester-posts__meta--icons' : '' ) . ( $show_avatar ? ' hester-posts__meta--author-avatar' : '' );
		echo '<div class="' . esc_attr( $meta_class ) . '">' . wp_kses_post( implode( '', $output ) ) . '</div>';
	}

	/**
	 * Render category badges.
	 *
	 * @param int $post_id Post ID.
	 * @param int $limit Maximum number of badges to show.
	 * @return void
	 */
	private function render_badges( $post_id, $limit = 3 ) {
		$categories = get_the_terms( $post_id, 'category' );

		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			return;
		}

		$categories = array_slice( $categories, 0, absint( $limit ) );

		echo '<div class="hester-posts__badges">';
		foreach ( $categories as $cat ) {
			$term_link = get_term_link( $cat );

			if ( ! is_wp_error( $term_link ) ) {
				echo '<a class="hester-posts__badge" href="' . esc_url( $term_link ) . '" title="' . esc_attr( $cat->name ) . '">' . esc_html( $cat->name ) . '</a>';
			} else {
				echo '<span class="hester-posts__badge" title="' . esc_attr( $cat->name ) . '">' . esc_html( $cat->name ) . '</span>';
			}
		}
		echo '</div>';
	}

	/**
	 * Build safe pagination args from current request.
	 *
	 * @return array
	 */
	private function get_pagination_add_args() {
		if ( empty( $_GET ) || ! is_array( $_GET ) ) {
			return array();
		}

		$allowed = array();

		foreach ( wp_unslash( $_GET ) as $key => $value ) {
			$key = sanitize_key( $key );

			if ( '' === $key || in_array( $key, array( 'paged', 'page' ), true ) ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$allowed[ $key ] = array_map( 'sanitize_text_field', $value );
			} else {
				$allowed[ $key ] = sanitize_text_field( (string) $value );
			}
		}

		return $allowed;
	}

	/**
	 * Render pagination.
	 *
	 * @param \WP_Query $query Query object.
	 * @param array     $settings Widget settings.
	 * @return void
	 */
	private function render_pagination( \WP_Query $query, array $settings ) {
		if ( $query->max_num_pages < 2 ) {
			return;
		}

		$pagination_type = $this->get_pagination_type( $settings );
		$page_limit      = isset( $settings['pagination_page_limit'] ) ? absint( $settings['pagination_page_limit'] ) : 0;
		$shorten_numbers = 'yes' === ( $settings['pagination_numbers_shorten'] ?? 'yes' );
		$total_pages     = (int) $query->max_num_pages;

		if ( $page_limit > 0 ) {
			$total_pages = min( $total_pages, $page_limit );
		}

		if ( 'none' === $pagination_type || $total_pages < 2 ) {
			return;
		}

		$current_page   = max( 1, $this->get_current_page() );
		$current_page   = min( $current_page, $total_pages );
		$prev_text      = ! empty( $settings['pagination_prev_label'] ) ? sanitize_text_field( $settings['pagination_prev_label'] ) : esc_html__( 'Previous', 'hester-core' );
		$next_text      = ! empty( $settings['pagination_next_label'] ) ? sanitize_text_field( $settings['pagination_next_label'] ) : esc_html__( 'Next', 'hester-core' );
		$load_more_text = ! empty( $settings['load_more_button_text'] ) ? sanitize_text_field( $settings['load_more_button_text'] ) : esc_html__( 'Load More', 'hester-core' );
		$no_more_text   = 'yes' === ( $settings['load_more_no_posts_message_switcher'] ?? 'no' )
			? ( ! empty( $settings['load_more_no_posts_custom_message'] ) ? sanitize_text_field( $settings['load_more_no_posts_custom_message'] ) : esc_html__( 'No more posts to show', 'hester-core' ) )
			: '';
		$add_args       = $this->get_pagination_add_args();
		$base_url       = remove_query_arg( 'paged', get_pagenum_link( 1 ) );
		$next_page_url  = $current_page < $total_pages ? get_pagenum_link( $current_page + 1 ) : '';

		if ( $next_page_url && ! empty( $add_args ) ) {
			$next_page_url = add_query_arg( $add_args, $next_page_url );
		}

		if ( in_array( $pagination_type, array( 'load_more_on_click', 'load_more_infinite_scroll' ), true ) ) {
			$classes = 'hester-posts__load-more hester-posts__load-more--' . esc_attr( $pagination_type );

			echo '<div class="' . $classes . '" data-next-url="' . esc_url( $next_page_url ) . '" aria-live="polite" aria-busy="false">';

			if ( 'load_more_on_click' === $pagination_type && $next_page_url ) {
				echo '<a class="hester-posts__load-more-button" href="' . esc_url( $next_page_url ) . '">';
				echo '<span class="hester-posts__load-more-button-label">' . esc_html( $load_more_text ) . '</span>';
				echo '<span class="hester-posts__load-more-button-spinner" aria-hidden="true"></span>';
				echo '</a>';
			}

			if ( 'load_more_infinite_scroll' === $pagination_type && $next_page_url ) {
				echo '<div class="hester-posts__load-more-loader" aria-hidden="true">';
				echo '<span class="hester-posts__load-more-spinner"></span>';
				echo '</div>';
			}

			if ( '' !== $no_more_text ) {
				$visible = $next_page_url ? ' style="display:none;"' : '';
				echo '<div class="hester-posts__load-more-message"' . $visible . '>' . esc_html( $no_more_text ) . '</div>';
			}

			echo '</div>';
			return;
		}

		if ( get_option( 'permalink_structure' ) ) {
			$pagination_base   = trailingslashit( $base_url ) . '%_%';
			$pagination_format = 'page/%#%/';
		} else {
			$pagination_base   = add_query_arg( 'paged', '%#%', $base_url );
			$pagination_format = '';
		}

		$links = paginate_links(
			array(
				'base'      => $pagination_base,
				'format'    => $pagination_format,
				'current'   => $current_page,
				'total'     => $total_pages,
				'mid_size'  => in_array( $pagination_type, array( 'numbers', 'numbers_and_prev_next' ), true ) ? ( $shorten_numbers ? 1 : 2 ) : 0,
				'end_size'  => in_array( $pagination_type, array( 'numbers', 'numbers_and_prev_next' ), true ) ? 1 : 0,
				'type'      => 'list',
				'prev_next' => in_array( $pagination_type, array( 'prev_next', 'numbers_and_prev_next' ), true ),
				'prev_text' => $prev_text,
				'next_text' => $next_text,
				'add_args'  => $add_args,
			)
		);

		if ( $links ) {
			echo '<nav class="hester-pagination" aria-label="' . esc_attr__( 'Posts pagination', 'hester-core' ) . '">' . wp_kses_post( $links ) . '</nav>';
		}
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings                = $this->get_settings_for_display();
		$query                   = new \WP_Query( $this->get_query_args( $settings ) );
		$skin                    = ! empty( $settings['skin'] ) && in_array( $settings['skin'], array( 'cards', 'classic', 'full_content' ), true ) ? $settings['skin'] : 'cards';
		$image_ratio             = ! empty( $settings['image_ratio'] ) ? $settings['image_ratio'] : '16-9';
		$image_position          = ! empty( $settings['image_position'] ) && in_array( $settings['image_position'], array( 'top', 'left', 'right', 'none' ), true ) ? $settings['image_position'] : 'top';
		$content_alignment       = ! empty( $settings['content_alignment'] ) && in_array( $settings['content_alignment'], array( 'left', 'center', 'right' ), true ) ? $settings['content_alignment'] : 'left';
		$meta_position           = ! empty( $settings['meta_position'] ) && in_array( $settings['meta_position'], array( 'before_title', 'after_title' ), true ) ? $settings['meta_position'] : 'before_title';
		$title_tag               = ! empty( $settings['title_html_tag'] ) ? hester_validate_html_tag( $settings['title_html_tag'] ) : 'h3';
		$image_size              = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'medium_large';
		$show_image              = 'yes' === ( $settings['show_image'] ?? 'yes' ) && 'none' !== $image_position;
		$show_title              = 'yes' === ( $settings['title'] ?? 'yes' );
		$show_excerpt            = 'yes' === ( $settings['excerpt'] ?? 'yes' );
		$apply_excerpt_to_custom = 'yes' === ( $settings['apply_to_custom_excerpt'] ?? 'no' );
		$show_button             = 'yes' === ( $settings['show_read_more'] ?? 'yes' );
		$open_new_tab            = 'yes' === ( $settings['open_new_tab'] ?? 'no' );
		$auto_align_buttons      = 'yes' === ( $settings['read_more_alignment'] ?? 'no' );
		$excerpt_len             = isset( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18;
		$button_text             = ! empty( $settings['readmore_text'] ) ? $settings['readmore_text'] : __( 'Read More', 'hester-core' );
		$link_attrs              = $open_new_tab ? ' target="_blank" rel="noopener"' : '';
		$masonry                 = 'yes' === ( $settings['masonry'] ?? 'no' );
		$hover_effect            = ! empty( $settings['image_hover_effect'] ) && in_array( $settings['image_hover_effect'], array( 'none', 'zoom', 'overlay' ), true ) ? $settings['image_hover_effect'] : 'none';
		$show_badges             = 'yes' === ( $settings['show_badges'] ?? 'no' );
		$badges_limit            = isset( $settings['badges_limit'] ) ? absint( $settings['badges_limit'] ) : 3;
		$post_ids                = array();

		if ( ! $query->have_posts() ) {
			echo '<div class="hester-posts__empty"><p>' . esc_html__( 'No posts found.', 'hester-core' ) . '</p></div>';
			return;
		}

		echo '<div class="hester-posts hester-posts--ratio-' . esc_attr( $image_ratio ) . ' hester-posts--skin-' . esc_attr( $skin ) . ' hester-posts--image-' . esc_attr( $image_position ) . ' hester-posts--align-' . esc_attr( $content_alignment ) . ( $auto_align_buttons ? ' hester-posts--readmore-align' : '' ) . ( $masonry ? ' hester-posts--masonry' : '' ) . ( 'none' !== $hover_effect ? ' hester-posts--hover-' . esc_attr( $hover_effect ) : '' ) . '" data-widget-id="' . esc_attr( $this->get_id() ) . '">';
		echo '<div class="hester-posts__grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_type = get_post_type() ?: 'post';

			// Pre-fetch the thumbnail HTML so we can decide whether to render the
			// media block at all — has_post_thumbnail() can return true even when
			// the attachment file is missing, which would leave an empty block-level
			// <a> with aspect-ratio CSS creating a visible blank rectangle.
			$thumbnail = '';
			if ( $show_image && has_post_thumbnail() ) {
				$thumbnail = get_the_post_thumbnail(
					get_the_ID(),
					$image_size,
					array(
						'loading' => 'lazy',
						'alt'     => the_title_attribute( array( 'echo' => false ) ),
					)
				);
			}

			$has_thumb    = ! empty( $thumbnail );
			$show_media   = $has_thumb;
			$base_class   = $has_thumb ? 'hester-posts__post' : 'hester-posts__post hester-posts__post--no-image';
			$post_classes = implode( ' ', get_post_class( $base_class, get_the_ID() ) );

			echo '<article class="' . esc_attr( $post_classes ) . '">';

			if ( $show_media ) {
				echo '<div class="hester-posts__media">';
				if ( $has_thumb ) {
					echo '<a class="hester-posts__thumbnail" href="' . esc_url( get_permalink() ) . '" aria-label="' . esc_attr( get_the_title() ) . '"' . $link_attrs . '>';
					echo $thumbnail;
					echo '</a>';
				}
				if ( $show_badges ) {
					$this->render_badges( get_the_ID(), $badges_limit );
				}
				echo '</div>';
			}

			echo '<div class="hester-posts__content">';

			if ( ! $show_media && $show_badges ) {
				$this->render_badges( get_the_ID(), $badges_limit );
			}

			if ( 'before_title' === $meta_position ) {
				$this->render_meta( $settings, $post_type );
			}

			if ( $show_title ) {
				echo '<' . esc_html( $title_tag ) . ' class="hester-posts__title">';
				echo '<a href="' . esc_url( get_permalink() ) . '"' . $link_attrs . '>' . esc_html( get_the_title() ) . '</a>';
				echo '</' . esc_html( $title_tag ) . '>';
			}

			if ( 'after_title' === $meta_position ) {
				$this->render_meta( $settings, $post_type );
			}

			if ( 'full_content' === $skin ) {
				echo '<div class="hester-posts__excerpt">' . wp_kses_post( get_the_content() ) . '</div>';
			} elseif ( $show_excerpt && $excerpt_len > 0 ) {
				$excerpt_text = get_the_excerpt();
				if ( has_excerpt() && ! $apply_excerpt_to_custom ) {
					echo '<div class="hester-posts__excerpt">' . esc_html( $excerpt_text ) . '</div>';
				} else {
					echo '<div class="hester-posts__excerpt">' . esc_html( wp_trim_words( $excerpt_text, $excerpt_len, '...' ) ) . '</div>';
				}
			}

			if ( 'full_content' !== $skin && $show_button && '' !== $button_text ) {
				echo '<a class="hester-posts__button" href="' . esc_url( get_permalink() ) . '"' . $link_attrs . '>' . esc_html( $button_text ) . '</a>';
			}

			echo '</div>';
			echo '</article>';

			$post_ids[] = get_the_ID();
		}

		echo '</div>';

		if ( 'none' !== $this->get_pagination_type( $settings ) ) {
			$this->render_pagination( $query, $settings );
		}

		echo '</div>';

		if ( 'yes' === ( $settings['avoid_duplicates'] ?? 'no' ) && ! empty( $post_ids ) ) {
			global $hester_posts_widget_seen_ids;

			if ( ! is_array( $hester_posts_widget_seen_ids ) ) {
				$hester_posts_widget_seen_ids = array();
			}

			$hester_posts_widget_seen_ids = array_values( array_unique( array_merge( $hester_posts_widget_seen_ids, $post_ids ) ) );
		}

		wp_reset_postdata();
	}
}
