<?php
namespace Hester_Core\Elementor\Modules\QueryPost;

use Hester_Core\Elementor\Base\Module_Base;
use Hester_Core\Elementor\Modules\QueryPost\Controls\Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module extends Module_Base {

	/**
	 * Validate query AJAX requests.
	 *
	 * @return void
	 */
	private function authorize_ajax_request() {
		if ( ! check_ajax_referer( 'hester_nonce', '_wpnonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'hester-core' ) ), 403 );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized', 'hester-core' ) ), 403 );
		}
	}

	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	public function get_name() {
		return 'query-control';
	}

	public function get_widgets() {
		return array();
	}

	public function register_controls( $controls_manager ) {
		$class_name = '\\Hester_Core\\Elementor\\Modules\\QueryPost\\Controls\\Query';

		if ( ! class_exists( $class_name, false ) ) {
			require_once HESTER_CORE_ELEMENTOR_PATH . 'modules/query-post/controls/query.php';
		}

		if ( class_exists( $class_name ) ) {
			$controls_manager->register( new $class_name() );
		}
	}

	public function get_posts_title_by_id() {
		$this->authorize_ajax_request();

		$ids = isset( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : array();
		$ids = is_array( $ids ) ? array_map( 'intval', $ids ) : array( intval( $ids ) );

		$results = array();
		$query   = new \WP_Query(
			array(
				'post_type'      => 'any',
				'post__in'       => $ids,
				'posts_per_page' => -1,
			)
		);

		foreach ( $query->posts as $post ) {
			$results[ $post->ID ] = $post->post_title;
		}

		wp_send_json( $results );
	}

	public function get_posts_by_query() {
		$this->authorize_ajax_request();

		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';
		$req_post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'all';

		$result = array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output   = 'names';
		$operator = 'and';

		if ( 'all' === $req_post_type ) {
			$post_types          = get_post_types( $args, $output, $operator );
			$post_types['Posts'] = 'post';
			$post_types['Pages'] = 'page';
		} else {
			$post_types[ $req_post_type ] = $req_post_type;
		}

		foreach ( $post_types as $key => $post_type ) {
			$data = array();

			add_filter( 'posts_search', array( $this, 'search_only_titles' ), 10, 2 );

			$query = new \WP_Query(
				array(
					's'              => $search_string,
					'post_type'      => $post_type,
					'posts_per_page' => -1,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$title  = get_the_title();
					$title .= ( 0 !== intval( $query->post->post_parent ) ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : '';
					$id     = get_the_id();
					$data[] = array(
						'id'   => $id,
						'text' => $title,
					);
				}
			}

			remove_filter( 'posts_search', array( $this, 'search_only_titles' ), 10 );

			if ( ! empty( $data ) ) {
				$result[] = array(
					'text'     => $key,
					'children' => $data,
				);
			}
		}

		wp_reset_postdata();
		wp_send_json( $result );
	}

	public function search_only_titles( $search, $wp_query ) {
		if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
			global $wpdb;

			$q = $wp_query->query_vars;
			$n = ! empty( $q['exact'] ) ? '' : '%';

			$search = array();
			foreach ( (array) $q['search_terms'] as $term ) {
				$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
			}

			if ( ! is_user_logged_in() ) {
				$search[] = "$wpdb->posts.post_password = ''";
			}

			$search = ' AND ' . implode( ' AND ', $search );
		}

		return $search;
	}

	protected function add_actions() {
		add_action( 'wp_ajax_hester_get_posts_by_query', array( $this, 'get_posts_by_query' ) );
		add_action( 'wp_ajax_hester_get_posts_title_by_id', array( $this, 'get_posts_title_by_id' ) );
		add_action( 'elementor/controls/register', array( $this, 'register_controls' ) );
	}
}
