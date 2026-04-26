/*!
 * Hester Core — Toast Notification Service
 * Exposes: window.HesterToast  |  No dependencies.
 */
(function (window, document) {
	'use strict';

	var CONTAINER_ID = 'hester-toast-container';
	var DEFAULTS     = { duration: 5000, detail: '', dismissible: true };

	function _container() {
		var el = document.getElementById(CONTAINER_ID);
		if ( ! el ) {
			el    = document.createElement('div');
			el.id = CONTAINER_ID;
			document.body.appendChild(el);
		}
		return el;
	}

	function _findDuplicate(container, message, type) {
		var toasts = container.querySelectorAll('.hester-toast-' + type + ':not(.hester-toast-hiding)');
		for (var i = 0; i < toasts.length; i++) {
			var msgEl = toasts[i].querySelector('.hester-toast-message');
			if (msgEl && msgEl.textContent === message) {
				return toasts[i];
			}
		}
		return null;
	}

	function _dismiss(toast, immediate) {
		if ( ! toast || ! toast.parentNode ) { return; }
		clearTimeout(toast._dismissTimer);

		if (immediate) {
			toast.parentNode.removeChild(toast);
			return;
		}
		toast.classList.remove('hester-toast-visible');
		toast.classList.add('hester-toast-hiding');
		setTimeout(function () {
			if (toast.parentNode) { toast.parentNode.removeChild(toast); }
		}, 300);
	}

	function show(message, type, options) {
		if ( ! message ) { return null; }

		type    = type    || 'info';
		options = Object.assign({}, DEFAULTS, options || {});

		var container = _container();

		// Deduplicate: remove identical visible toast before showing a fresh one.
		var dupe = _findDuplicate(container, message, type);
		if (dupe) { _dismiss(dupe, true); }

		// Build element.
		var toast       = document.createElement('div');
		toast.className = 'hester-toast hester-toast-' + type;
		toast.setAttribute('role', 'alert');
		toast.setAttribute('aria-live', 'error' === type ? 'assertive' : 'polite');

		var inner = '<span class="hester-toast-message">' + message + '</span>';
		if (options.detail) {
			inner += '<span class="hester-toast-detail">' + options.detail + '</span>';
		}
		if (false !== options.dismissible) {
			inner += '<button type="button" class="hester-toast-dismiss" aria-label="Dismiss">&times;</button>';
		}
		toast.innerHTML = inner;
		container.appendChild(toast);

		// Two rAF calls so the CSS transition fires after the element is in the DOM.
		requestAnimationFrame(function () {
			requestAnimationFrame(function () {
				toast.classList.add('hester-toast-visible');
			});
		});

		// Dismiss button.
		var btn = toast.querySelector('.hester-toast-dismiss');
		if (btn) {
			btn.addEventListener('click', function () { _dismiss(toast); });
		}

		// Auto-dismiss with hover-pause.
		function _schedule() {
			if (options.duration > 0) {
				toast._dismissTimer = setTimeout(function () { _dismiss(toast); }, options.duration);
			}
		}
		_schedule();
		toast.addEventListener('mouseenter', function () { clearTimeout(toast._dismissTimer); });
		toast.addEventListener('mouseleave', _schedule);

		return toast;
	}

	window.HesterToast = {
		show:    show,
		success: function (msg, opts) { return show(msg, 'success', opts); },
		error:   function (msg, opts) { return show(msg, 'error',   opts); },
		warning: function (msg, opts) { return show(msg, 'warning', opts); },
		info:    function (msg, opts) { return show(msg, 'info',    opts); },
	};

}(window, document));
