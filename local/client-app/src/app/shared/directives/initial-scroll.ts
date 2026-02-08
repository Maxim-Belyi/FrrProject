import type { Directive } from 'vue';

interface ScrollElement extends HTMLElement {
	_scrollHandler?: () => void;
}

const initialScroll: Directive<ScrollElement> = {
	mounted: (el) => {
		const element = el;

		element._scrollHandler = () => {
			const scrollContainer = element.parentElement;
			if (scrollContainer) {
				const elementRect = element.getBoundingClientRect();
				const parentRect = scrollContainer.getBoundingClientRect();
				const leftOffset = elementRect.left - parentRect.left;
				const scrollTo = leftOffset - (scrollContainer.clientWidth / 2) + (element.offsetWidth / 2);
				scrollContainer.scrollTo({
					left: scrollTo,
				});
			}
		};

		element._scrollHandler();
	},
	unmounted: (el) => {
		const element = el;
		if (!element._scrollHandler)
			return;
		delete element._scrollHandler;
	},
};

export default initialScroll;
