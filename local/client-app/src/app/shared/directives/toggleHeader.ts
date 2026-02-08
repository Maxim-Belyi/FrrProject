import type { Directive } from 'vue';

interface ScrollHandlerHTMLElement extends HTMLElement {
	_scrollHandler?: () => void;
}

const toggleHeader: Directive<ScrollHandlerHTMLElement> = {
	mounted(el) {
		const firstSection: HTMLElement | null = document.querySelector('section');

		const onScroll = () => {
			if (!firstSection)
				return;

			if (window.pageYOffset > firstSection.scrollHeight - el.offsetHeight) {
				el.classList.remove('header--transparent');
			}
			else {
				el.classList.add('header--transparent');
			}
		};

		window.addEventListener('scroll', onScroll);
		el._scrollHandler = onScroll;
	},

	unmounted(el) {
		if (el._scrollHandler) {
			window.removeEventListener('scroll', el._scrollHandler);
		}
	},
};

export default toggleHeader;
