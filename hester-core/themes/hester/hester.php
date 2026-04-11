<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
require HESTER_CORE_PLUGIN_DIR . 'themes/hester/customizer/customizer.php';
require HESTER_CORE_PLUGIN_DIR . 'themes/hester/sections/index.php';

function hester_core_hester_enqueue_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	$style_file      = HESTER_CORE_PLUGIN_DIR . 'assets/css/style' . $suffix . '.css';
	$swiper_css_file = HESTER_CORE_PLUGIN_DIR . 'assets/css/swiper-bundle' . $suffix . '.css';
	$swiper_js_file  = HESTER_CORE_PLUGIN_DIR . 'assets/js/swiper-bundle' . $suffix . '.js';
	$glightbox_css   = HESTER_CORE_PLUGIN_DIR . 'assets/css/glightbox' . $suffix . '.css';
	$glightbox_js    = HESTER_CORE_PLUGIN_DIR . 'assets/js/glightbox' . $suffix . '.js';
	$app_js_file     = HESTER_CORE_PLUGIN_DIR . 'assets/js/app' . $suffix . '.js';

	$style_version      = file_exists( $style_file ) ? filemtime( $style_file ) : HESTER_CORE_VERSION;
	$swiper_css_version = file_exists( $swiper_css_file ) ? filemtime( $swiper_css_file ) : HESTER_CORE_VERSION;
	$swiper_js_version  = file_exists( $swiper_js_file ) ? filemtime( $swiper_js_file ) : HESTER_CORE_VERSION;
	$glightbox_css_ver  = file_exists( $glightbox_css ) ? filemtime( $glightbox_css ) : HESTER_CORE_VERSION;
	$glightbox_js_ver   = file_exists( $glightbox_js ) ? filemtime( $glightbox_js ) : HESTER_CORE_VERSION;
	$app_js_version     = file_exists( $app_js_file ) ? filemtime( $app_js_file ) : HESTER_CORE_VERSION;

	wp_register_style( 'hester-core-hester', HESTER_CORE_PLUGIN_URL . '/assets/css/style' . $suffix . '.css', array(), $style_version );
	wp_register_style( 'glightbox', HESTER_CORE_PLUGIN_URL . 'assets/css/glightbox' . $suffix . '.css', array(), $glightbox_css_ver );

	if ( ! wp_style_is( 'swiper', 'registered' ) ) {
		wp_register_style( 'swiper', HESTER_CORE_PLUGIN_URL . '/assets/css/swiper-bundle' . $suffix . '.css', array(), $swiper_css_version );
	}

	if ( ! wp_script_is( 'swiper', 'registered' ) ) {
		wp_register_script( 'swiper', HESTER_CORE_PLUGIN_URL . '/assets/js/swiper-bundle' . $suffix . '.js', array(), $swiper_js_version, true );
	}

	wp_register_script( 'glightbox-js', HESTER_CORE_PLUGIN_URL . 'assets/js/glightbox' . $suffix . '.js', array(), $glightbox_js_ver, true );
	wp_register_script( 'hester-core-hester-app-js', HESTER_CORE_PLUGIN_URL . '/assets/js/app' . $suffix . '.js', array( 'swiper', 'glightbox-js' ), $app_js_version, true );

	wp_enqueue_style( 'hester-core-hester' );
	wp_enqueue_style( 'swiper' );
	wp_enqueue_style( 'glightbox' );

	wp_enqueue_script( 'swiper' );
	wp_enqueue_script( 'glightbox-js' );
	wp_enqueue_script( 'hester-core-hester-app-js' );
}
add_action( 'wp_enqueue_scripts', 'hester_core_hester_enqueue_scripts' );

/**
 * Set Front page displays option to A static page
 */
function hester_core_hester_set_frontpage() {
	if ( class_exists( 'Hester_Theme_Setup' ) ) {
		$is_fresh_site = get_option( 'fresh_site' );
		if ( (bool) $is_fresh_site === false ) {
			$frontpage_title = esc_html__( 'Front Page', 'hester-core' );
			$front_id        = hester_core_hester_create_page( 'hester-front', $frontpage_title );
			$blogpage_title  = esc_html__( 'Blog', 'hester-core' );
			$blog_id         = hester_core_hester_create_page( 'blog', $blogpage_title );
			set_theme_mod( 'show_on_front', 'page' );
			update_option( 'show_on_front', 'page' );
			if ( ! empty( $front_id ) ) {
				update_option( 'page_on_front', $front_id );
			}
			if ( ! empty( $blog_id ) ) {
				update_option( 'page_for_posts', $blog_id );
			}
		}
	}
}

add_action( 'after_switch_theme', 'hester_core_hester_set_frontpage' );

/**
 * Function that checks if a page with a slug exists. If not, it create one.
 *
 * @param string $slug Page slug.
 * @param string $page_title Page title.
 * @return int
 */
function hester_core_hester_create_page( $slug, $page_title ) {
	// Check if page exists
	$args = array(
		'name'        => $slug,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1,
	);
	$post = get_posts( $args );
	if ( ! empty( $post ) ) {
		$page_id = $post[0]->ID;
	} else {
		// Page doesn't exist. Create one.
		$postargs = array(
			'post_type'   => 'page',
			'post_name'   => $slug,
			'post_title'  => $page_title,
			'post_status' => 'publish',
			'post_author' => 1,
		);
		// Insert the post into the database
		$page_id = wp_insert_post( $postargs );
	}
	return $page_id;
}


if ( ! function_exists( 'hester_display_customizer_shortcut' ) ) {
	/**
	 * This function display a shortcut to a customizer control.
	 *
	 * @param string $class_name The name of control we want to link this shortcut with.
	 * @param bool   $is_section_toggle Tells function to display eye icon if it's true.
	 */
	function hester_display_customizer_shortcut( $class_name, $is_section_toggle = false, $should_return = false ) {
		if ( ! is_customize_preview() ) {
			return;
		}
		$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
					<path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>
				</svg>';
		if ( $is_section_toggle ) {
			$icon = '<i class="far fa-eye"></i>';
		}

		$data = '<span class="hester-hide-section-shortcut customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . '">
					<button class="customize-partial-edit-shortcut-button">
						' . $icon . '
					</button>
				</span>';
		if ( $should_return === true ) {
			return $data;
		}
		echo wp_kses_post( $data );
	}
}
