import type { ResponseError } from '@app/shared/api/api.types';
import type { AxiosError } from 'axios';
import { requestInterceptors } from '@app/shared/api/api.interceptors';
import useBaseStore from '@app/shared/stores/base/base.store';
import axios from 'axios';

export const api = axios.create({
	baseURL: '/api/',
	timeout: 10000,
	internal: false,
	csrf: true,
	captcha: false,
	errorToast: true,
});

requestInterceptors.forEach((interceptor) => {
	api.interceptors.request.use(interceptor);
});

// Обработка ошибок
api.interceptors.response.use(
	response => response,
	(error: AxiosError<ResponseError>) => {
		const { errorMessage } = useBaseStore();

		if (!error.response)
			return;

		let message = error.response.data.message || error.message || '';
		if (error.response.config.errorToast && message) {
			message = message.replaceAll(/<br>/g, ' ');
			message = message.replaceAll(/<\/br>/g, ' ');
			message = message.replaceAll(/\n/g, ' ');
			errorMessage(message);
		}

		return error.response;
	},
);
