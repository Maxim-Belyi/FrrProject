import type { AuthPayload } from '@/app/modules/user/api/user.api.types';
import type { UsersState } from '@/app/modules/user/store/user.store.types';
import { userApi } from '@/app/modules/user/api/user.api';
import { UserActions } from '@/app/modules/user/store/user.store.types';
import useBaseStore from '@/app/shared/stores/base/base.store';
import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
	state: (): UsersState => ({
		isLoggedIn: false,
		userData: null,
	}),
	actions: {
		async requestUser() {
			const { startLoading, stopLoading } = useBaseStore();
			startLoading(UserActions.USER);

			const response = await userApi.requestUser();
			if (response.success)
				this.userData = response.data;

			stopLoading(UserActions.USER);
			return response;
		},

		async requestAuthUser(data: AuthPayload) {
			const { startLoading, stopLoading } = useBaseStore();
			startLoading(UserActions.AUTH_USER);

			const response = await userApi.requestAuthUser(data);
			if (response.success)
				this.userData = response.data;

			stopLoading(UserActions.AUTH_USER);
			return response;
		},
	},
	getters: {
		getUserId(): number | null {
			return this.userData?.id || null;
		},
	},
});
