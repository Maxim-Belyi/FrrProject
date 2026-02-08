import type { InternalAxiosRequestConfig } from 'axios';
import { useCaptcha } from '@app/shared/composables/useCaptcha';
import useBaseStore from '@app/shared/stores/base/base.store';

// baseURL для авторизации
function internalUrlInterceptor(config: InternalAxiosRequestConfig): InternalAxiosRequestConfig {
	if (config.internal)
		config.baseURL = '/api-internal/';
	return config;
}

// CSRF-токен
function csrfTokenInterceptor(config: InternalAxiosRequestConfig): InternalAxiosRequestConfig {
	if (config.csrf) {
		const { csrfToken } = useBaseStore();
		config.headers['X-Bitrix-Csrf-Token'] = csrfToken;
	}
	return config;
}

// Captcha-токен
async function captchaTokenInterceptor(config: InternalAxiosRequestConfig): Promise<InternalAxiosRequestConfig> {
	if (config.captcha) {
		const { getToken } = useCaptcha();
		config.headers['X-ReCaptcha-Token'] = await getToken(config.url || '');
	}
	return config;
}

export const requestInterceptors = [
	internalUrlInterceptor,
	csrfTokenInterceptor,
	captchaTokenInterceptor,
];
