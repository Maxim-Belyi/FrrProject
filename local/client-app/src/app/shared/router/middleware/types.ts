import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

export interface MiddlewareContext {
	to: RouteLocationNormalized;
	from: RouteLocationNormalized;
	next: NavigationGuardNext;
	abort: NavigationGuardNext;
}

export type Middleware = (context: MiddlewareContext) => void;

export type MiddlewarePipeline = (
	context: MiddlewareContext,
	middleware: Middleware[],
	index: number
) => () => void;
