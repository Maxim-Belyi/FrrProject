import type {
	MiddlewarePipeline,
} from '@/app/shared/router/middleware/types';

/**
 * @description
 * Мидлвара для роутера, которая позволяет вызывать мидлвары по очереди. При этом каждая мидлвара может вызвать следующую мидлвару в цепочке. Если мидлвара вызывает `abort()`, то цепочка прерывается. Если мидлвара вызывает `next()`, то вызывается следующая мидлвара в цепочке.
 * @param {_MiddlewareContext} context
 * @param {Middleware[]} middlewares
 * @param {number} index
 * @return {Function}
 * @example
 * ``` ts
 * const context = {
 * 		to,
 * 		from,
 * 		next,
 * 		abort: next,
 * 		// ...
 * 		// any other data
 * 		// ...
 * 	};
 *
 * 	const middlewares = [middleware1, middleware2, middleware3];
 * 	const next = middlewarePipeline(context, middlewares, 0);
 * 	next();
 * 	```
 */
const middlewarePipeline: MiddlewarePipeline = (context, middlewares, index) => {
	const nextMiddleware = middlewares[index];

	if (!nextMiddleware)
		return context.next;

	return () => {
		const nextPipeline = middlewarePipeline(context, middlewares, index + 1);
		nextMiddleware({ ...context, next: nextPipeline });
	};
};

export default middlewarePipeline;
