/**
 * Оборачивает элемент в div с заданным классом
 * @param {Element} element элемент, который нужно обернуть
 * @param {HTMLElement} wrapElement элемент-обертка
 * @returns {void}
 */
function wrap(element: Element, wrapElement: HTMLElement) {
	const elParent = element.parentNode;
	if (!elParent)
		return;

	elParent.insertBefore(wrapElement, element);
	wrapElement.appendChild(element);
}

/**
 * Оборачивает на контентных страницах все таблицы и видео в div с классом table-wrap и video-wrap соответственно
 * @returns {void}
 */
export default function initMediaAutoWrapper() {
	const videos = document.querySelectorAll('.standard-block iframe:not([class])');
	const tables = document.querySelectorAll('.standard-block table:not([class])');

	[...Array.from(tables), ...Array.from(videos)].forEach((elem) => {
		const wrapper = document.createElement('div');

		if (elem.tagName === 'TABLE') {
			wrapper.classList.add('table-wrap');
		}
		else if (elem.tagName === 'IFRAME') {
			wrapper.classList.add('video-wrap');
			elem.classList.add('video-wrap__video');
		}

		wrap(elem, wrapper);
	});
}
