<?php
/**
 * Hester Elementor Helpers
 *
 * @package Hester Core
 * @subpackage Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'hester_get_available_tags' ) ) {
	function hester_get_available_tags() {
		$tags = array(
			'h1'   => __( 'H1', 'hester-core' ),
			'h2'   => __( 'H2', 'hester-core' ),
			'h3'   => __( 'H3', 'hester-core' ),
			'h4'   => __( 'H4', 'hester-core' ),
			'h5'   => __( 'H5', 'hester-core' ),
			'h6'   => __( 'H6', 'hester-core' ),
			'div'  => __( 'div', 'hester-core' ),
			'span' => __( 'span', 'hester-core' ),
			'p'    => __( 'p', 'hester-core' ),
		);

		return apply_filters( 'hester_title_tags', $tags );
	}
}

if ( ! function_exists( 'hester_validate_html_tag' ) ) {
	function hester_validate_html_tag( $tag ) {
		static $allowed_html_wrapper_tags = array(
			'article',
			'aside',
			'div',
			'footer',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'header',
			'main',
			'nav',
			'p',
			'section',
			'span',
		);

		$tag = strtolower( (string) $tag );
		return in_array( $tag, $allowed_html_wrapper_tags, true ) ? $tag : 'div';
	}
}

if ( ! function_exists( 'hester_get_available_sidebars' ) ) {
	function hester_get_available_sidebars() {
		global $wp_registered_sidebars;

		$sidebars = array();

		if ( empty( $wp_registered_sidebars ) ) {
			$sidebars['0'] = __( 'No sidebars were found', 'hester-core' );
		} else {
			$sidebars['0'] = __( '-- Select --', 'hester-core' );
			foreach ( $wp_registered_sidebars as $id => $sidebar ) {
				$sidebars[ $id ] = $sidebar['name'];
			}
		}

		return $sidebars;
	}
}

if ( ! function_exists( 'hester_get_available_templates' ) ) {
	function hester_get_available_templates() {
		$templates = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'posts_per_page' => -1,
			)
		);

		$result = array( __( '-- Select --', 'hester-core' ) );

		if ( ! empty( $templates ) && ! is_wp_error( $templates ) ) {
			foreach ( $templates as $item ) {
				$result[ $item->ID ] = $item->post_title;
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'hester_is_contact_form_7_active' ) ) {
	function hester_is_contact_form_7_active() {
		return class_exists( 'WPCF7_ContactForm' );
	}
}

if ( ! function_exists( 'hester_pagination' ) ) {
	function hester_pagination( $query = '', $echo = true ) {
		$prev_arrow = is_rtl() ? 'fas fa-angle-right' : 'fas fa-angle-left';
		$next_arrow = is_rtl() ? 'fas fa-angle-left' : 'fas fa-angle-right';

		if ( ! $query ) {
			global $wp_query;
			$query = $wp_query;
		}

		$total = $query->max_num_pages;
		$big   = 999999999;

		if ( $total > 1 ) {
			if ( $current_page = get_query_var( 'paged' ) ) {
				$current_page = $current_page;
			} elseif ( $current_page = get_query_var( 'page' ) ) {
				$current_page = $current_page;
			} else {
				$current_page = 1;
			}

			$format = get_option( 'permalink_structure' ) ? ( is_page() ? 'page/%#%/' : '/%#%/' ) : '&paged=%#%';

			$args = array(
				'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
				'format'    => $format,
				'current'   => max( 1, $current_page ),
				'total'     => $total,
				'mid_size'  => 3,
				'type'      => 'list',
				'prev_text' => '<i class="' . $prev_arrow . '"></i>',
				'next_text' => '<i class="' . $next_arrow . '"></i>',
			);

			$output = '<div class="hester-pagination clr">' . wp_kses_post( paginate_links( $args ) ) . '</div>';

			if ( $echo ) {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				return $output;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'hester_excerpt' ) ) {
	function hester_excerpt( $length = 15 ) {
		global $post;

		if ( ! $post ) {
			return;
		}

		$id      = $post->ID;
		$excerpt = $post->post_excerpt;
		$content = $post->post_content;

		if ( ! empty( $excerpt ) && ! ctype_space( $excerpt ) ) {
			$output = $excerpt;
		} elseif ( strpos( $content, '<!--more-->' ) !== false ) {
			$output = get_the_content( $excerpt );
		} else {
			$output = wp_trim_words( strip_shortcodes( get_the_content( $id ) ), $length );
		}

		echo wp_kses_post( $output );
	}
}

if ( ! function_exists( 'hester_get_inline_content_allowed_html' ) ) {
	/**
	 * Return allowed inline HTML tags/attributes for text content.
	 *
	 * @return array<string, array<string, bool>>
	 */
	function hester_get_inline_content_allowed_html() {
		$allowed_html = wp_kses_allowed_html( 'post' );

		foreach ( array( 'span', 'strong', 'em', 'small', 'mark', 'sub', 'sup', 'b', 'i', 'u', 'a', 'br' ) as $tag ) {
			if ( ! isset( $allowed_html[ $tag ] ) || ! is_array( $allowed_html[ $tag ] ) ) {
				$allowed_html[ $tag ] = array();
			}

			$allowed_html[ $tag ]['class'] = true;
			$allowed_html[ $tag ]['style'] = true;
		}

		return apply_filters( 'hester_inline_content_allowed_html', $allowed_html );
	}
}
