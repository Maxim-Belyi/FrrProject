export function initBurgerMenu() {
	const menuOpenButton = document.querySelector('[data-menu-open]');
	const menuCloseButton = document.querySelector('[data-menu-close]');
	const mobileMenu = document.querySelector('.mobile-menu');

	if (!menuOpenButton || !menuCloseButton || !mobileMenu)
		return;

	function openMenu() {
		mobileMenu!.classList.add('active');
	}

	function closeMenu() {
		mobileMenu!.classList.remove('active');
	}

	menuOpenButton.addEventListener('click', openMenu);

	menuCloseButton.addEventListener('click', closeMenu);

	document.addEventListener('scroll', closeMenu);
}
