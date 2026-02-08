import useBaseStore from '@app/shared/stores/base/base.store';
import { storeToRefs } from 'pinia';

declare global {
	interface Window {
		grecaptcha: ReCaptchaInstance;
		captchaOnLoad: () => void;
	}
}

export interface ReCaptchaExecuteOptions {
	action: string;
}

export interface ReCaptchaRenderOptions {
	sitekey: string;
	size: 'invisible';
}

export interface ReCaptchaInstance {
	ready: (cb: () => unknown) => void;
	execute: (siteKey: string, options?: ReCaptchaExecuteOptions) => Promise<string>;
	render: (id: string, options: ReCaptchaRenderOptions) => unknown;
}

export function useCaptcha() {
	const { captchaKey } = storeToRefs(useBaseStore());
	function initCaptcha() {
		const script = document.createElement('script');
		script.src = `https://www.google.com/recaptcha/api.js?render=${captchaKey.value}`;
		script.async = true;
		script.defer = true;
		script.onerror = () => {
			console.error('Error load recaptcha script');
		};
		document.head.appendChild(script);
	};

	async function getToken(action: string) {
		if (!window.grecaptcha)
			return;

		let result = '';

		const actionName = action.replace(/[^A-Z/_]/gi, '');

		try {
			result = await window.grecaptcha.execute(captchaKey.value, { action: actionName }) || '';
		}
		catch (e) {
			console.error('Error get token', e);
		}

		return result;
	}
	return { initCaptcha, getToken };
}
