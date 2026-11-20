(function () {
	'use strict';

	var theme = 'dark';

	try {
		var savedTheme = window.localStorage.getItem('final-decisions-theme');
		if (savedTheme === 'light' || savedTheme === 'dark') {
			theme = savedTheme;
		} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
			theme = 'light';
		}
	} catch (error) {
		try {
			if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
				theme = 'light';
			}
		} catch (mediaError) {
			theme = 'dark';
		}
	}

	document.documentElement.setAttribute('data-theme', theme);
})();
