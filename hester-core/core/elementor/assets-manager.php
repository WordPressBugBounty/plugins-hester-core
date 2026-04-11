<?php
/**
 * Elementor Assets Manager
 *
 * @package Hester Core
 * @subpackage Elementor
 */

namespace Hester_Core\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets Manager Class
 *
 * Handles enqueueing of scripts and styles
 *
 * @since 1.0.0
 */
class Assets_Manager {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'wp_ajax_hester_search', array( $this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_hester_search', array( $this, 'ajax_search' ) );
	}

	/**
	 * Enqueue frontend scripts and styles for frontend display
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		$css_file              = HESTER_CORE_ELEMENTOR_PATH . 'assets/css/hester-widgets.css';
		$js_file               = HESTER_CORE_ELEMENTOR_PATH . 'assets/js/hester-widgets.js';
		$typed_file            = HESTER_CORE_ELEMENTOR_PATH . 'assets/vendor/js/typed.min.js';
		$morphext_file         = HESTER_CORE_ELEMENTOR_PATH . 'assets/vendor/js/morphext.min.js';
		$swiper_css_file       = HESTER_CORE_PLUGIN_DIR . 'assets/css/swiper-bundle.min.css';
		$swiper_js_file        = HESTER_CORE_PLUGIN_DIR . 'assets/js/swiper-bundle.min.js';
		$swiper_custom_file    = HESTER_CORE_ELEMENTOR_PATH . 'assets/css/swiper-custom.css';
		$css_version           = file_exists( $css_file ) ? filemtime( $css_file ) : HESTER_CORE_VERSION;
		$js_version            = file_exists( $js_file ) ? filemtime( $js_file ) : HESTER_CORE_VERSION;
		$typed_version         = file_exists( $typed_file ) ? filemtime( $typed_file ) : HESTER_CORE_VERSION;
		$morphext_version      = file_exists( $morphext_file ) ? filemtime( $morphext_file ) : HESTER_CORE_VERSION;
		$swiper_css_version    = file_exists( $swiper_css_file ) ? filemtime( $swiper_css_file ) : HESTER_CORE_VERSION;
		$swiper_js_version     = file_exists( $swiper_js_file ) ? filemtime( $swiper_js_file ) : HESTER_CORE_VERSION;
		$swiper_custom_version = file_exists( $swiper_custom_file ) ? filemtime( $swiper_custom_file ) : HESTER_CORE_VERSION;

		// Register Main CSS - All widgets share this
		wp_register_style(
			'hester-widgets',
			HESTER_CORE_ELEMENTOR_URL . 'assets/css/hester-widgets.css',
			array(),
			$css_version
		);

		// Register Main JS - All widgets share this
		wp_register_script(
			'hester-widgets',
			HESTER_CORE_ELEMENTOR_URL . 'assets/js/hester-widgets.js',
			array( 'jquery' ),
			$js_version,
			true
		);

		wp_localize_script(
			'hester-widgets',
			'hesterWidgetsData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'hester_nonce' ),
			)
		);

		// Bundled library dependencies.
		wp_register_script(
			'typed',
			HESTER_CORE_ELEMENTOR_URL . 'assets/vendor/js/typed.min.js',
			array(),
			$typed_version,
			true
		);

		wp_register_script(
			'morphext',
			HESTER_CORE_ELEMENTOR_URL . 'assets/vendor/js/morphext.min.js',
			array( 'jquery' ),
			$morphext_version,
			true
		);

		// Swiper CSS & JS for woo-carousel.
		if ( ! wp_style_is( 'swiper', 'registered' ) ) {
			wp_register_style(
				'swiper',
				HESTER_CORE_PLUGIN_URL . 'assets/css/swiper-bundle.min.css',
				array(),
				$swiper_css_version
			);
		}

		if ( ! wp_script_is( 'swiper', 'registered' ) ) {
			wp_register_script(
				'swiper',
				HESTER_CORE_PLUGIN_URL . 'assets/js/swiper-bundle.min.js',
				array(),
				$swiper_js_version,
				true
			);
		}

		// Swiper Custom Styles
		wp_register_style(
			'hester-swiper-custom',
			HESTER_CORE_ELEMENTOR_URL . 'assets/css/swiper-custom.css',
			array( 'swiper' ),
			$swiper_custom_version
		);

		// Compatibility handles used by widget files.
		$compat_styles = array(
			'hester-core',
			'hester-animated-heading',
			'hester-posts',
			'hester-pricing',
			'hester-slides',
			'hester-tabs',
			'hester-flip-box',
			'hester-search',
			'hester-off-canvas',
			'hester-contact-form',
			'hester-contact-form-7',
			'hester-woo-products',
			'hester-woo-carousel',
			'hester-woo-categories',
			'hester-woo-addtocart',
		);

		foreach ( $compat_styles as $handle ) {
			wp_register_style( $handle, false, array( 'hester-widgets' ), $css_version );
		}

		$compat_scripts = array(
			'hester-core',
			'hester-animated-heading',
			'hester-pricing',
			'hester-slides',
			'hester-tabs',
			'hester-flip-box',
			'hester-search',
			'hester-off-canvas',
			'hester-contact-form',
			'hester-contact-form-7',
			'hester-woo-products',
			'hester-woo-carousel',
			'hester-woo-categories',
			'hester-woo-addtocart',
		);

		foreach ( $compat_scripts as $handle ) {
			wp_register_script( $handle, false, array( 'hester-widgets' ), $js_version, true );
		}
	}

	/**
	 * Enqueue editor scripts
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		// Enqueue any editor-specific scripts
	}

	/**
	 * AJAX Search handler - searches published posts/pages/products.
	 *
	 * @return void
	 */
	public function ajax_search() {
		check_ajax_referer( 'hester_nonce', '_wpnonce' );

		$query  = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		if ( '' === $query && isset( $_POST['q'] ) ) {
			$query = sanitize_text_field( wp_unslash( $_POST['q'] ) );
		}
		$source = isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : 'all';
		$page   = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$limit  = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 5;
		$page   = max( 1, $page );

		if ( $limit < 1 ) {
			$limit = 5;
		}

		if ( $limit > 50 ) {
			$limit = 50;
		}

		if ( empty( $query ) || strlen( $query ) < 2 ) {
			wp_send_json_error( array( 'message' => __( 'Search query too short', 'hester-core' ) ) );
		}

		$allowed_sources = array( 'all', 'any', 'post', 'page', 'product' );

		if ( ! in_array( $source, $allowed_sources, true ) ) {
			$source = 'all';
		}

		$args = array(
			'posts_per_page' => $limit,
			'paged'          => $page,
			's'              => $query,
			'post_status'    => 'publish',
		);

		if ( 'all' !== $source ) {
			$args['post_type'] = $source;
		} else {
			$args['post_type'] = array( 'post', 'page', 'product' );
		}

		$results         = new \WP_Query( $args );
		$items           = array();
		$all_results_url = add_query_arg( 's', $query, home_url( '/' ) );

		if ( ! in_array( $source, array( 'all', 'any' ), true ) ) {
			$all_results_url = add_query_arg( 'post_type', $source, $all_results_url );
		}

		if ( $results->have_posts() ) {
			while ( $results->have_posts() ) {
				$results->the_post();

				$post_id    = get_the_ID();
				$post_type  = get_post_type( $post_id );
				$thumb_url  = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
				$price_html = '';

				if ( ! $thumb_url && 'product' === $post_type && function_exists( 'wc_placeholder_img_src' ) ) {
					$thumb_url = wc_placeholder_img_src( 'thumbnail' );
				}

				if ( 'product' === $post_type && function_exists( 'wc_get_product' ) ) {
					$product = wc_get_product( $post_id );
					if ( $product ) {
						$price_html = $product->get_price_html();
					}
				}

				$items[] = array(
					'id'      => $post_id,
					'title'   => get_the_title(),
					'url'     => get_the_permalink(),
					'excerpt' => wp_trim_words( get_the_excerpt(), 15 ),
					'type'    => $post_type,
					'thumb'   => $thumb_url ? esc_url_raw( $thumb_url ) : '',
					'price'   => $price_html ? wp_kses_post( $price_html ) : '',
				);
			}
			wp_reset_postdata();

			wp_send_json_success(
				array(
					'items'           => $items,
					'total'           => $results->found_posts,
					'pages'           => $results->max_num_pages,
					'limit'           => $limit,
					'all_results_url' => esc_url_raw( $all_results_url ),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'No results found', 'hester-core' ) ) );
		}
	}
}
