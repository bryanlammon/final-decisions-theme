(function () {
	'use strict';

	var storageKey = 'final-decisions-theme';
	var root = document.documentElement;
	var control = document.querySelector('[data-theme-control]');
	var toggle = document.querySelector('[data-theme-toggle]');
	var current = document.querySelector('[data-theme-current]');
	var status = document.querySelector('[data-theme-status]');
	var mode = 'system';
	var persisted = true;
	var media = null;

	try {
		if (window.matchMedia) {
			media = window.matchMedia('(prefers-color-scheme: light)');
		}
	} catch (error) {
		media = null;
	}

	try {
		var saved = window.localStorage.getItem(storageKey);
		if (saved === 'light' || saved === 'dark') {
			mode = saved;
		}
	} catch (error) {
		mode = 'system';
	}

	function applyTheme() {
		var appearance;
		if (mode === 'system') {
			appearance = media && media.matches ? 'light' : 'dark';
		} else {
			appearance = mode;
		}
		root.setAttribute('data-theme', appearance);
		if (control && toggle && current) {
			toggle.setAttribute('aria-pressed', appearance === 'dark' ? 'true' : 'false');
			current.textContent = 'Current: ' + appearance + (mode === 'system' ? ' (system setting)' : ' (explicit choice)') + (persisted ? '' : ' (not saved)');
		}
		return appearance;
	}

	function updateMode(next) {
		mode = next;
		var appearance;
		try {
			window.localStorage.setItem(storageKey, mode);
			persisted = true;
			appearance = applyTheme();
			status.textContent = 'Theme set to ' + appearance + ' and saved.';
		} catch (error) {
			persisted = false;
			appearance = applyTheme();
			status.textContent = 'Theme set to ' + appearance + ' for this page, but the preference could not be saved.';
		}
	}

	if (control && toggle && current && status) {
		control.hidden = false;
		toggle.addEventListener('click', function () {
			updateMode(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
		});
	}

	applyTheme();
	function followSystem() {
		if (mode === 'system') {
			applyTheme();
		}
	}
	if (media && typeof media.addEventListener === 'function') {
		media.addEventListener('change', followSystem);
	} else if (media && typeof media.addListener === 'function') {
		media.addListener(followSystem);
	}
	window.addEventListener('storage', function (event) {
		if (event.key === storageKey || event.key === null) {
			mode = event.newValue === 'light' || event.newValue === 'dark' ? event.newValue : 'system';
			persisted = true;
			applyTheme();
		}
	});

	var menu = document.querySelector('.nav-disclosure');
	var search = document.querySelector('.header-search');
	var narrow = window.matchMedia ? window.matchMedia('(max-width: 76rem)') : null;
	if (menu && narrow) {
		function setMenuForViewport() {
			if (!narrow.matches) {
				menu.open = true;
				if (search && !search.contains(document.activeElement)) {
					search.open = false;
				}
				if (document.activeElement === menu.querySelector('summary')) {
					var target = menu.querySelector('.nav-panel a, .nav-panel input, .nav-panel button');
					if (target) {
						target.focus();
					}
				}
			} else {
				if (search) {
					search.open = true;
					if (document.activeElement === search.querySelector('summary')) {
						search.querySelector('input').focus();
					}
				}
				if (!menu.contains(document.activeElement)) {
					menu.open = false;
				}
			}
		}

		setMenuForViewport();
		if (typeof narrow.addEventListener === 'function') {
			narrow.addEventListener('change', setMenuForViewport);
		} else if (typeof narrow.addListener === 'function') {
			narrow.addListener(setMenuForViewport);
		}
		menu.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && menu.open && narrow.matches) {
				menu.open = false;
				menu.querySelector('summary').focus();
			}
		});
	}
	if (search) {
		search.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && search.open && (!narrow || !narrow.matches)) {
				search.open = false;
				search.querySelector('summary').focus();
				event.stopPropagation();
			}
		});
	}

	var emailLink = document.querySelector('[data-email-link]');
var emailFallback = document.querySelector('[data-email-fallback]');

if (emailLink && emailFallback) {
	var encodedAddress = emailLink.getAttribute('data-email-encoded');

	try {
		var address = window.atob(encodedAddress);

		if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(address)) {
			emailLink.href = 'mailto:' + address;
			emailLink.textContent = address;
			emailLink.hidden = false;
			emailFallback.hidden = true;
		}
	} catch (error) {
		// Keep the readable fallback if decoding fails.
	}
}
})();
