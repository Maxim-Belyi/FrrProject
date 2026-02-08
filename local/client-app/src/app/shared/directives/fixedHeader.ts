import type { Directive } from 'vue';

interface ScrollHandlerHTMLElement extends HTMLElement {
	_scrollHandler?: () => void;
	_lastScrollY?: number;
}

const fixedHeader: Directive<ScrollHandlerHTMLElement> = {
	mounted(el) {
		const onScroll = () => {
			const scrollY = document.documentElement.scrollTop || document.body.scrollTop;
			const windowHeight = window.innerHeight;
			const scrollDirectionDown = scrollY < (el._lastScrollY ?? 0);
			el._lastScrollY = scrollY;

			if (scrollY >= windowHeight && scrollDirectionDown) {
				el.classList.add('fixed', 'visible');
			}
			else {
				el.classList.remove('visible');
				setTimeout(() => {
					if (!el.classList.contains('visible')) {
						el.classList.remove('fixed');
					}
				}, 300);
			}
		};

		el._lastScrollY = document.documentElement.scrollTop || document.body.scrollTop;

		window.addEventListener('scroll', onScroll);
		el._scrollHandler = onScroll;
	},

	unmounted(el) {
		if (el._scrollHandler) {
			window.removeEventListener('scroll', el._scrollHandler);
		}
	},
};

export default fixedHeader;
