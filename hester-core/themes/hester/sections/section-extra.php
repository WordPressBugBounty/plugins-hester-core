<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! function_exists( 'hester_section_extra' ) ) {
	function hester_section_extra() {
		$show_section = hester_option( 'enable_extra' );

		$page_id = hester_option( 'section_extra_page' );

		$page = '';
		if ( '' != $page_id ) {
			$page = get_post( $page_id );
		}

		$section_style = '';
		if ( (bool) $show_section === false || $page == '' ) {
			if ( is_customize_preview() ) {
				$section_style .= 'display: none;';
			} else {
				return;
			}
		}
		$section_style = 'style="' . $section_style . '"';
		do_action( 'hester_before_extra_start' ); ?>

		<section id="hester-extra" class="hester_home_section hester_section_extra" <?php echo wp_kses_post( $section_style ); ?>>
			<?php hester_display_customizer_shortcut( 'hester_enable_extra', true ); ?>
			<div class="hester_bg hester-py-default">
				<div class="hester-container">
					<div class="hester-flex-row">
						<div class="col-xs-12">
							<?php
							if ( is_object( $page ) ) {
								echo get_the_content( '', '', $page );
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- #hester-extra -->
		<?php
		do_action( 'hester_after_extra_end' );
	}
}
