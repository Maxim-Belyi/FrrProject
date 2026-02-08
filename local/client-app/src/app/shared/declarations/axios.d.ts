import 'axios';

declare module 'axios' {
	export interface AxiosRequestConfig {
		internal?: boolean;
		csrf?: boolean;
		captcha?: boolean;
		errorToast?: boolean;
	}
}
