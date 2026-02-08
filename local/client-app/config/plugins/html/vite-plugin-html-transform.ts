import type { Plugin } from 'vite';

export function vitePluginHtmlTransform(title: string): Plugin {
	return {
		name: 'vite-plugin-html-transform',
		transformIndexHtml(html) {
			return html
				.replace(
					/<title>(.*?)<\/title>/,
					`<title>${title}</title>`,
				)
				.replace(
					/<meta property="og:title" \/>/,
					`<meta property="og:title" content="${title}" />`,
				)
				.replace(
					/<h1 class="pv-title__text">(.*?)<\/h1>/,
					`<h1 class="pv-title__text">${title}</h1>`,
				)
			;
		},
	};
}
