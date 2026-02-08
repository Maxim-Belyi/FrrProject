import type { Middleware } from '@/app/shared/router/middleware/types';
import { useUserStore } from '@/app/modules/user/store/user.store';
import { RouteNames } from '@/app/shared/router/types';

const isLogouted: Middleware = async ({ from, next, abort }) => {
	if (
		from.meta.loginFailed
		|| [RouteNames.REGISTER_PAGE, RouteNames.AUTH_PAGE].includes(from.name as RouteNames)
	) {
		next();
		return;
	}

	const userStore = useUserStore();
	if (userStore.isLoggedIn) {
		abort({ name: RouteNames.MAIN_PAGE });
	}
	else {
		const request = await userStore.requestUser();
		if (request.success)
			abort({ name: RouteNames.MAIN_PAGE });
		else next();
	}
};

export default isLogouted;
