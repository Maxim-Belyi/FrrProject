import type { Middleware } from '@/app/shared/router/middleware/types';
import type { BaseLayouts } from '@/app/shared/router/types';

import 'vue-router';

export {};

declare module 'vue-router' {
	interface RouteMeta {
		name: string;
		layout?: BaseLayouts;
		middleware?: Array<Middleware>;
	}
}
