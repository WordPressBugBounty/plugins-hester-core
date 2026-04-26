//--------------------------------------------------------------------//
// Hester Core Theme Library script.
//--------------------------------------------------------------------//
; (function ($) {
	'use strict';

	var $body = $('body');
	var $html = $('html');
	var $document = $(document);

	var HesterCoreThemeLibrary = {

		/**
		 * Start the engine.
		 */
		init: function () {
			$(document).ready(HesterCoreThemeLibrary.ready);
			$(window).on('load', HesterCoreThemeLibrary.load);
			HesterCoreThemeLibrary.bindUIActions();
		},

		ready: function () { },

		/**
		 * Window load — render theme grid.
		 */
		load: function () {
			HesterCoreThemeLibrary.renderThemes(hesterCoreThemeLibrary.themes);
		},

		//--------------------------------------------------------------------//
		// Events
		//--------------------------------------------------------------------//

		bindUIActions: function () {

			// Live search filter.
			$document.on('input', '#hester-search-themes', HesterCoreThemeLibrary.onSearch);

			// Open preview overlay.
			$document.on('click', '.hester-theme .demo-screenshot, .hester-preview-theme', HesterCoreThemeLibrary.preview);

			// Close preview overlay.
			$document.on('click', '.hester-theme-preview .close-full-overlay', HesterCoreThemeLibrary.closePreview);

			// Escape key closes overlay.
			$document.on('keyup', function (e) {
				if (27 === e.keyCode) {
					$('.hester-theme-preview .close-full-overlay').trigger('click');
				}
			});

			// Previous / next navigation.
			$document.on('click', '.hester-theme-preview .next-theme', HesterCoreThemeLibrary.previewNext);
			$document.on('click', '.hester-theme-preview .previous-theme', HesterCoreThemeLibrary.previewPrevious);

			// Device preview buttons.
			$document.on('click', '.hester-theme-preview .devices button', HesterCoreThemeLibrary.previewDevice);

			// Collapse sidebar.
			$document.on('click', '.hester-theme-preview .collapse-sidebar', HesterCoreThemeLibrary.collapseSidebar);

			// Install theme (card or overlay).
			$document.on('click', '.hester-install-theme', function (e) {
				e.preventDefault();
				var $btn = $(this);
				HesterCoreThemeLibrary.installTheme($btn, $btn.data('slug'));
			});

			// Activate theme (card or overlay).
			$document.on('click', '.hester-activate-theme', function (e) {
				e.preventDefault();
				var $btn = $(this);
				HesterCoreThemeLibrary.activateTheme($btn, $btn.data('slug'));
			});
		},

		//--------------------------------------------------------------------//
		// Grid rendering
		//--------------------------------------------------------------------//

		/**
		 * Render the theme grid from a themes array.
		 *
		 * @param {Array} themes
		 */
		renderThemes: function (themes) {

			var template = wp.template('hester-core-theme-item');
			var $container = $('.hester-themes-grid');
			var $notice = $('.hester-themes-notice');

			$container.empty();

			if (!themes || !themes.length) {
				$notice.removeClass('hester-hidden');
				return;
			}

			$notice.addClass('hester-hidden');

			$.each(themes, function (i, theme) {
				$container.append(template(HesterCoreThemeLibrary.extendThemeData(theme)));
			});
		},

		/**
		 * Merge a theme object with global capability + URL data for templates.
		 *
		 * @param  {Object} theme
		 * @return {Object}
		 */
		extendThemeData: function (theme) {
			return $.extend({}, theme, {
				canInstallThemes: hesterCoreThemeLibrary.canInstallThemes,
				canSwitchThemes: hesterCoreThemeLibrary.canSwitchThemes,
				customize_url: hesterCoreThemeLibrary.customizeUrl,
			});
		},

		/**
		 * Re-render a single card in-place by slug.
		 *
		 * @param {string} slug
		 */
		refreshCard: function (slug) {

			var themeData = HesterCoreThemeLibrary.findTheme(slug);
			if (!themeData) {
				return;
			}

			var template = wp.template('hester-core-theme-item');
			var $existing = $('.hester-theme[data-theme-slug="' + slug + '"]').closest('.hester-column');

			if ($existing.length) {
				$existing.replaceWith(template(HesterCoreThemeLibrary.extendThemeData(themeData)));
			}
		},

		/**
		 * Live search — filter grid by name / description.
		 */
		onSearch: function () {

			var term = $(this).val().toLowerCase().trim();
			var all = hesterCoreThemeLibrary.themes;
			var results = !term ? all : all.filter(function (theme) {
				return (
					theme.name.toLowerCase().indexOf(term) !== -1 ||
					(theme.description && theme.description.toLowerCase().indexOf(term) !== -1)
				);
			});

			HesterCoreThemeLibrary.renderThemes(results);
		},

		//--------------------------------------------------------------------//
		// Preview overlay
		//--------------------------------------------------------------------//

		/**
		 * Open preview for the clicked card.
		 */
		preview: function (event) {
			event.preventDefault();

			var $card = $(this).closest('.hester-theme');
			var slug = $card.data('theme-slug');

			if (!slug) {
				return;
			}

			$('.hester-theme-previewed').removeClass('hester-theme-previewed');
			$card.addClass('hester-theme-previewed');
			$html.addClass('hester-demo-preview-on');
			HesterCoreThemeLibrary.showPreview($card);
		},

		/**
		 * Build preview template data from a card element and render the overlay.
		 *
		 * @param {jQuery} $card  .hester-theme element.
		 */
		showPreview: function ($card) {

			var slug = $card.data('theme-slug');
			var themeData = HesterCoreThemeLibrary.findTheme(slug);

			var data = $.extend(
				{
					name: $card.data('theme-name'),
					screenshot_url: $card.data('theme-screenshot'),
					description: $card.data('theme-description'),
					version: $card.data('theme-version'),
					preview_url: $card.data('theme-preview-url'),
					slug: slug,
					status: $card.data('theme-status'),
				},
				themeData || {},
				HesterCoreThemeLibrary.extendThemeData(themeData || {})
			);

			var template = wp.template('hester-core-theme-preview');

			$('.hester-theme-preview').remove();
			$('.hester-themes-grid').append(template(data));
			$('.hester-theme-preview').css('display', 'block');

			HesterCoreThemeLibrary.updateNextPrev();
		},

		/**
		 * Disable prev/next buttons at the boundaries of the visible grid.
		 */
		updateNextPrev: function () {

			var $current = $('.hester-theme-previewed').closest('.hester-column');
			var hasNext = $current.nextAll('.hester-column').length;
			var hasPrev = $current.prevAll('.hester-column').length;

			$('.hester-theme-preview .next-theme').toggleClass('disabled', 0 === hasNext);
			$('.hester-theme-preview .previous-theme').toggleClass('disabled', 0 === hasPrev);
		},

		/**
		 * Close the preview overlay.
		 */
		closePreview: function (event) {

			event.preventDefault();

			$('.hester-theme-preview').css('display', 'none').remove();
			$('.hester-theme-previewed').removeClass('hester-theme-previewed');
			$html.removeClass('hester-demo-preview-on');
		},

		/**
		 * Navigate to the previous theme in the grid.
		 */
		previewPrevious: function (event) {

			event.preventDefault();

			var $current = $('.hester-theme-previewed').removeClass('hester-theme-previewed').closest('.hester-column');
			var $prev = $current.prev('.hester-column').find('.hester-theme').addClass('hester-theme-previewed');

			if ($prev.length) {
				HesterCoreThemeLibrary.showPreview($prev);
			}
		},

		/**
		 * Navigate to the next theme in the grid.
		 */
		previewNext: function (event) {

			event.preventDefault();

			var $current = $('.hester-theme-previewed').removeClass('hester-theme-previewed').closest('.hester-column');
			var $next = $current.next('.hester-column').find('.hester-theme').addClass('hester-theme-previewed');

			if ($next.length) {
				HesterCoreThemeLibrary.showPreview($next);
			}
		},

		/**
		 * Switch iframe device preview mode.
		 */
		previewDevice: function (event) {

			var device = $(event.currentTarget).data('device');

			$('.hester-theme-preview')
				.removeClass('preview-desktop preview-tablet preview-mobile')
				.addClass('preview-' + device);

			$('.hester-theme-preview .devices button')
				.removeClass('active')
				.attr('aria-pressed', false);

			$('.hester-theme-preview .devices .preview-' + device)
				.addClass('active')
				.attr('aria-pressed', true);
		},

		/**
		 * Toggle the overlay sidebar collapsed / expanded state.
		 */
		collapseSidebar: function (event) {

			event.preventDefault();

			var $overlay = $('.wp-full-overlay');
			var $btn = $('.collapse-sidebar');

			if ($overlay.hasClass('expanded')) {
				$overlay.removeClass('expanded').addClass('collapsed');
				$btn.attr('aria-expanded', 'false');
			} else {
				$overlay.removeClass('collapsed').addClass('expanded');
				$btn.attr('aria-expanded', 'true');
			}
		},

		//--------------------------------------------------------------------//
		// Install / Activate
		//--------------------------------------------------------------------//

		/**
		 * Install a theme via wp.updates.
		 *
		 * @param {jQuery} $btn
		 * @param {string} slug
		 */
		installTheme: function ($btn, slug) {

			var strings = hesterCoreThemeLibrary.strings;
			var inOverlay = $btn.closest('.hester-theme-preview').length > 0;
			var $card = $('.hester-theme[data-theme-slug="' + slug + '"]');

			if (wp.updates.shouldRequestFilesystemCredentials && !wp.updates.ajaxLocked) {
				wp.updates.requestFilesystemCredentials(event);
			}

			$btn.text(strings.installing).addClass('updating-message').prop('disabled', true);
			$card.addClass('busy');

			wp.updates.installTheme({
				slug: slug,
				success: function () {
					window.HesterToast && HesterToast.success(slug.charAt(0).toUpperCase() + slug.slice(1) + ' ' + strings.installSuccess.toLowerCase());
					// Update local status.
					var theme = HesterCoreThemeLibrary.findTheme(slug);
					if (theme) {
						theme.status = strings.installed;
					}

					// Refresh the card in the grid.
					HesterCoreThemeLibrary.refreshCard(slug);

					// If preview was open, re-show it with updated data.
					if (inOverlay) {
						if ($card.length) {
							$card.data('theme-status', strings.installed);
							HesterCoreThemeLibrary.showPreview($card);
						}
					}

					$card.removeClass('busy');
				},
				error: function (response) {
					var msg = (response && response.errorMessage) ? response.errorMessage : strings.installFailed;
					window.HesterToast && HesterToast.error(msg);
					$btn.text(strings.install).removeClass('updating-message button-disabled').prop('disabled', false);
					$card.removeClass('busy');
				}
			});
		},

		/**
		 * Activate an installed theme via AJAX, then redirect to themes page.
		 *
		 * @param {jQuery} $btn
		 * @param {string} slug
		 */
		activateTheme: function ($btn, slug) {

			var strings = hesterCoreThemeLibrary.strings;

			$btn.text(strings.activating).addClass('updating-message').prop('disabled', true);
			$btn.closest('.hester-theme').addClass('busy');

			$.ajax({
				url: hesterCoreThemeLibrary.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'hester_core_activate_theme',
					nonce: hesterCoreThemeLibrary.wpnonce,
					slug: slug,
				},
				success: function (response) {
					if (response.success) {
						window.HesterToast && HesterToast.success(slug.charAt(0).toUpperCase() + slug.slice(1) + ' ' + strings.activateSuccess.toLowerCase());
						// Redirect to themes page which always exists regardless of active theme.
						if (response.data && response.data.redirect) {
							window.location.href = response.data.redirect;
						} else {
							window.location.reload();
						}
					} else {
						var msg = (response.data && response.data.message) ? response.data.message : strings.activateFailed;
						window.HesterToast && HesterToast.error(msg);
						$btn.text(strings.activate).removeClass('updating-message').prop('disabled', false);
						$btn.closest('.hester-theme').removeClass('busy');
					}
				},
				error: function () {
					window.HesterToast && HesterToast.error(strings.activateFailed);
					$btn.text(strings.activate).removeClass('updating-message').prop('disabled', false);
					$btn.closest('.hester-theme').removeClass('busy');
				}
			});
		},

		//--------------------------------------------------------------------//
		// Helpers
		//--------------------------------------------------------------------//

		/**
		 * Find a theme object in the local data array by slug.
		 *
		 * @param  {string} slug
		 * @return {Object|null}
		 */
		findTheme: function (slug) {
			var found = null;
			$.each(hesterCoreThemeLibrary.themes, function (i, theme) {
				if (theme.slug === slug) {
					found = theme;
					return false;
				}
			});
			return found;
		}
	};

	HesterCoreThemeLibrary.init();

}(jQuery));
