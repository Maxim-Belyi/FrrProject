export type ViewportNames =
	| 'mobile'
	| 'mobile-xl'
	| 'tablet'
	| 'tablet-xl'
	| 'laptop'
	| 'laptop-xl'
	| 'laptop-xxl'
	| 'container'
	| 'pc';

const VIEWPORT_SIZES: Record<ViewportNames, number> = {
	'mobile': 320,
	'mobile-xl': 480,
	'tablet': 768,
	'tablet-xl': 992,
	'laptop': 1024,
	'laptop-xl': 1280,
	'laptop-xxl': 1440,
	'container': 1315,
	'pc': 1600,
};

export { VIEWPORT_SIZES };
