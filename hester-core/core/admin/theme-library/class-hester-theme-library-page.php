<?php
/**
 * Hester Theme Library Page. Admin page for browsing Peregrine Themes.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.1.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Theme Library Page Class.
 *
 * @since 1.1.6
 * @package Hester Core
 */
final class Hester_Theme_Library_Page {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester Theme Library Page Instance.
	 *
	 * @since 1.1.6
	 * @return Hester_Theme_Library_Page
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Theme_Library_Page ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.1.6
	 */
	public function __construct() {

		$theme_name = hester_core()->theme_name;

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 100 );
		add_action( 'admin_print_footer_scripts-hester_page_' . $theme_name . '-theme-library', array( $this, 'print_templates' ) );
		add_filter( $theme_name . '_dashboard_navigation_items', array( $this, 'update_navigation_items' ) );

		do_action( 'hester_theme_library_page_loaded' );
	}

	/**
	 * Add submenu page.
	 *
	 * @since 1.1.6
	 */
	public function add_admin_menu() {

		$theme_name = hester_core()->theme_name;

		add_submenu_page(
			$theme_name . '-dashboard',
			esc_html__( 'Themes', 'hester-core' ),
			'Themes',
			apply_filters( 'hester_manage_cap', 'edit_theme_options' ),
			$theme_name . '-theme-library',
			array( $this, 'render_theme_library' )
		);
	}

	/**
	 * Render the Theme Library admin page.
	 *
	 * @since 1.1.6
	 */
	public function render_theme_library() {

		$theme_name       = hester_core()->theme_name;
		$hester_dashboard = $theme_name . '_dashboard';

		$hester_dashboard()->render_navigation();

		?>
		<div class="hester-container">

			<div class="hester-section-title">
				<h2 class="hester-section-title"><?php esc_html_e( 'Themes', 'hester-core' ); ?></h2>

				<div class="demo-search">
					<input type="search" placeholder="<?php esc_attr_e( 'Filter&hellip;', 'hester-core' ); ?>" id="hester-search-themes" />
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8" />
						<path d="M21 21l-4.35-4.35" />
					</svg>
				</div>

				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=' . $theme_name . '-theme-library' ), 'refresh_themes', 'hester_core_nonce' ) ); ?>" class="hester-btn secondary">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M23 4v6h-6M1 20v-6h6" />
						<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
					</svg>
					<?php esc_html_e( 'Refresh', 'hester-core' ); ?>
				</a>
			</div><!-- END .hester-section-title -->

			<div class="hester-section hester-columns hester-themes-grid">
			</div><!-- END .hester-themes-grid -->

			<p class="hester-themes-notice hester-hidden">
				<?php esc_html_e( 'No themes found.', 'hester-core' ); ?>
			</p>

		</div>
		<?php
	}

	/**
	 * Print Underscore.js templates in the page footer.
	 *
	 * @since 1.1.6
	 */
	public function print_templates() {
		?>

		<!-- Theme grid card -->
		<script type="text/template" id="tmpl-hester-core-theme-item">
			<div class="hester-column">
				<div class="hester-theme"
					data-theme-slug="{{data.slug}}"
					data-theme-status="{{data.status}}"
					data-theme-preview-url="{{{data.preview_url}}}"
					data-theme-name="{{{data.name}}}"
					data-theme-screenshot="{{{data.screenshot_url}}}"
					data-theme-description="{{{data.description}}}"
					data-theme-version="{{data.version}}"
					>

					<div class="demo-screenshot theme-screenshot-wrap">
						<# if ( data.screenshot_url ) { #>
							<img src="{{{data.screenshot_url}}}" alt="{{data.name}}" />
						<# } else { #>
							<div class="hester-no-screenshot"></div>
						<# } #>
						<span class="text-overlay">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							<span><?php esc_html_e( 'Preview Theme', 'hester-core' ); ?></span>
						</span>
						<# if ( 'active' === data.status ) { #>
							<span class="hester-theme-badge hester-theme-badge-active"><?php esc_html_e( 'Active', 'hester-core' ); ?></span>
						<# } else if ( 'installed' === data.status ) { #>
							<span class="hester-theme-badge hester-theme-badge-installed"><?php esc_html_e( 'Installed', 'hester-core' ); ?></span>
						<# } #>
					</div>

					<div class="demo-meta">
						<div class="demo-name">
							<span class="name">{{data.name}}</span>
							<span class="hester-theme-version">v{{data.version}}</span>
						</div>
						<div class="demo-actions">
							<# if ( 'active' === data.status ) { #>
								<a class="hester-btn secondary btn-small" href="{{{data.customize_url}}}"><?php esc_html_e( 'Customize', 'hester-core' ); ?></a>
							<# } else { #>
								<# if ( 'installed' === data.status && data.canSwitchThemes ) { #>
									<a class="hester-btn primary btn-small hester-activate-theme" href="#" data-slug="{{data.slug}}"><?php esc_html_e( 'Activate', 'hester-core' ); ?></a>
								<# } else if ( 'not-installed' === data.status && data.canInstallThemes ) { #>
									<a class="hester-btn primary btn-small hester-install-theme" href="#" data-slug="{{data.slug}}"><?php esc_html_e( 'Install', 'hester-core' ); ?></a>
								<# } #>
								<a class="hester-btn secondary btn-small hester-preview-theme" href="#" data-slug="{{data.slug}}"><?php esc_html_e( 'Preview', 'hester-core' ); ?></a>
							<# } #>
						</div>
					</div>

				</div>
			</div>
		</script>

		<!-- Full-screen preview overlay -->
		<script type="text/template" id="tmpl-hester-core-theme-preview">
			<div class="hester-theme-preview theme-install-overlay wp-full-overlay expanded">

				<div class="wp-full-overlay-sidebar">

					<div class="wp-full-overlay-header"
						data-theme-slug="{{{data.slug}}}"
						data-theme-status="{{data.status}}"
						data-theme-preview-url="{{{data.preview_url}}}"
						data-theme-name="{{{data.name}}}"
						data-theme-screenshot="{{{data.screenshot_url}}}"
						data-theme-description="{{{data.description}}}"
						data-theme-version="{{data.version}}">

						<button class="close-full-overlay"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'hester-core' ); ?></span></button>
						<button class="previous-theme"><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'hester-core' ); ?></span></button>
						<button class="next-theme"><span class="screen-reader-text"><?php esc_html_e( 'Next', 'hester-core' ); ?></span></button>

						<# if ( 'active' === data.status ) { #>
							<a class="hester-btn secondary btn-small" href="{{{data.customize_url}}}"><?php esc_html_e( 'Customize', 'hester-core' ); ?></a>
						<# } else if ( 'installed' === data.status && data.canSwitchThemes ) { #>
							<a class="hester-btn primary btn-small hester-activate-theme" href="#" data-slug="{{data.slug}}"><?php esc_html_e( 'Activate', 'hester-core' ); ?></a>
						<# } else if ( 'not-installed' === data.status && data.canInstallThemes ) { #>
							<a class="hester-btn primary btn-small hester-install-theme" href="#" data-slug="{{data.slug}}"><?php esc_html_e( 'Install', 'hester-core' ); ?></a>
						<# } #>

					</div><!-- END .wp-full-overlay-header -->

					<div class="wp-full-overlay-sidebar-content">
						<div class="install-theme-info">

							<div class="hester-demo-name">
								<span><?php esc_html_e( 'You are previewing', 'hester-core' ); ?></span>
								<h3>{{{data.name}}}</h3>
							</div>

							<# if ( data.screenshot_url ) { #>
								<div class="theme-screenshot-wrap">
									<img class="theme-screenshot" src="{{{data.screenshot_url}}}" alt="{{{data.name}}}" />
								</div>
							<# } #>

							<# if ( 'active' === data.status ) { #>
								<p class="hester-theme-status-pill hester-theme-status-active">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Currently Active', 'hester-core' ); ?>
								</p>
							<# } else if ( 'installed' === data.status ) { #>
								<p class="hester-theme-status-pill hester-theme-status-installed">
									<span class="dashicons dashicons-download"></span>
									<?php esc_html_e( 'Installed', 'hester-core' ); ?>
								</p>
							<# } #>

							<# if ( data.description ) { #>
								<div class="theme-description">{{{data.description}}}</div>
							<# } #>

							<div class="hester-theme-detail-meta">
								<# if ( data.version ) { #>
									<span><?php esc_html_e( 'Version', 'hester-core' ); ?>: <strong>{{data.version}}</strong></span>
								<# } #>
							</div>

						</div>
					</div><!-- END .wp-full-overlay-sidebar-content -->

					<div class="wp-full-overlay-footer">
						<div class="footer-import-button-wrap">

							<# if ( 'active' === data.status ) { #>
								<a class="hester-btn secondary large-button" href="{{{data.customize_url}}}"><?php esc_html_e( 'Customize', 'hester-core' ); ?></a>
							<# } else if ( 'installed' === data.status && data.canSwitchThemes ) { #>
								<a class="hester-btn primary large-button hester-activate-theme" href="#" data-slug="{{data.slug}}">
									<span class="status"><?php esc_html_e( 'Activate', 'hester-core' ); ?></span>
								</a>
							<# } else if ( 'not-installed' === data.status && data.canInstallThemes ) { #>
								<a class="hester-btn primary large-button hester-install-theme" href="#" data-slug="{{data.slug}}">
									<span class="status"><?php esc_html_e( 'Install', 'hester-core' ); ?></span>
								</a>
							<# } #>

						</div><!-- END .footer-import-button-wrap -->

						<button type="button" class="collapse-sidebar button" aria-expanded="true"
								aria-label="<?php esc_attr_e( 'Collapse Sidebar', 'hester-core' ); ?>">
							<span class="collapse-sidebar-arrow"></span>
							<span class="collapse-sidebar-label"><?php esc_html_e( 'Collapse', 'hester-core' ); ?></span>
						</button>

						<div class="devices-wrapper">
							<div class="devices">
								<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter desktop preview mode', 'hester-core' ); ?></span>
								</button>
								<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter tablet preview mode', 'hester-core' ); ?></span>
								</button>
								<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter mobile preview mode', 'hester-core' ); ?></span>
								</button>
							</div>
						</div>

					</div><!-- END .wp-full-overlay-footer -->

				</div><!-- END .wp-full-overlay-sidebar -->

				<div class="wp-full-overlay-main">
					<iframe src="{{{data.preview_url}}}" title="<?php esc_attr_e( 'Preview', 'hester-core' ); ?>"></iframe>
				</div><!-- END .wp-full-overlay-main -->

			</div><!-- END .hester-theme-preview -->
		</script>

		<?php
	}

	/**
	 * Add Themes item to the dashboard navigation, inserted after Demo Library.
	 *
	 * @since 1.1.6
	 * @param array $items Array of navigation items.
	 * @return array
	 */
	public function update_navigation_items( $items ) {

		$theme_name = hester_core()->theme_name;
		$new_item   = array(
			'theme-library' => array(
				'id'   => 'theme-library',
				'name' => esc_html__( 'Themes', 'hester-core' ),
				'icon' => '',
				'url'  => admin_url( 'admin.php?page=' . $theme_name . '-theme-library' ),
			),
		);

		$insert_array = $theme_name . '_array_insert';
		if ( is_callable( $insert_array ) ) {
			$items = $insert_array( $items, $new_item, 'demo-library', 'after' );
		}

		return $items;
	}
}

/**
 * Returns the one Hester_Theme_Library_Page instance.
 *
 * @since 1.1.6
 * @return Hester_Theme_Library_Page
 */
function hester_theme_library_page() {
	return Hester_Theme_Library_Page::instance();
}

hester_theme_library_page();
