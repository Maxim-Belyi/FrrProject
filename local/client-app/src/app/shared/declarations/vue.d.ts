declare module '*.vue' {
	import type { DefineComponent } from 'vue';
	// eslint-disable-next-line
	const component: DefineComponent<{}, {}, any>;

	export interface HTMLAttributes {
		vModel?: any;
	}

	export default component;
}
