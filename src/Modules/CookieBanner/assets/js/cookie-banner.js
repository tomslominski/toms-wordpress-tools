/**
 * Cookie banner module.
 */

(function () {
	const banner = document.querySelector('.ts-cookie-banner');
	const close = document.querySelector('.ts-cookie-banner-close');

	if( banner && close ) {
		close.addEventListener('click', function () {
			localStorage.setItem('ts-cookie-banner', 'accepted');
			banner.classList.add('ts-hidden');
		});

		document.addEventListener('DOMContentLoaded', function () {
			if ('accepted' !== localStorage.getItem('ts-cookie-banner')) {
				banner.classList.remove('ts-hidden');
			}
		});
	}
})();
