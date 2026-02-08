import { useResizeObserver } from '@vueuse/core';

export default function calcAppProperties() {
	const header = document.querySelector('.header') as HTMLElement;
	// https://vueuse.org/core/useResizeObserver/#useresizeobserver
	useResizeObserver(header, (entries) => {
		const entry = entries[0];
		const height = entry.borderBoxSize[0].blockSize;
		document.documentElement.style.setProperty('--header-height', `${height}px`);
	});
}
