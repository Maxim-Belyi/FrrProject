import type { BaseState } from '@/app/shared/stores/base/base.store.types';
import { useCookies } from '@vueuse/integrations/useCookies';
import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';

// https://vueuse.org/integrations/useCookies/
const { get: getCookie } = useCookies();

const toast = useToast();
const useBaseStore = defineStore('base', {
	state: (): BaseState => ({
		loadingPool: new Set(),
		cookieConfirmed: getCookie('cookieConfirmed') ?? 'N',
		csrfToken: '',
		captchaKey: '',
		docs: {
			privacyPolicy: '',
			userAgreement: '',
		},
	}),
	actions: {
		startLoading(actionName: string) {
			this.loadingPool.add(actionName);
		},
		stopLoading(actionName: string) {
			if (!this.loadingPool.has(actionName))
				console.error(`${actionName} : не найдено в пуле загрузки`);
			this.loadingPool.delete(actionName);
		},
		errorMessage(message: string) {
			toast.error(message);
		},
		warningMessage(message: string) {
			toast.warning(message);
		},
		infoMessage(message: string) {
			toast.info(message);
		},
		successMessage(message: string) {
			toast.success(message);
		},
	},
	getters: {
		isLoading(): boolean {
			return this.loadingPool.size > 0;
		},
		isActionPending(): (actionName: string) => boolean {
			return actionName => this.loadingPool.has(actionName);
		},
		isCookieConfirmed(): boolean {
			return this.cookieConfirmed === 'Y';
		},
	},
});

export default useBaseStore;
