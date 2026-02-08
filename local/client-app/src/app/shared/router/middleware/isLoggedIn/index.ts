import type { Middleware } from '@/app/shared/router/middleware/types';
import { useUserStore } from '@/app/modules/user/store/user.store';
import { RouteNames } from '@/app/shared/router/types';

const isLoggedIn: Middleware = async ({ from, next, abort }) => {
	const userStore = useUserStore();

	if (userStore.isLoggedIn) {
		next();
	}
	else {
		const request = await userStore.requestUser();
		if (request.success) {
			next();
		}
		else {
			from.meta.loginFailed = true;
			abort({ name: RouteNames.AUTH_PAGE });
		}
	}
};

export default isLoggedIn;
