import { ref } from 'vue';

export type Locale = 'ru' | 'en';

const messages = ref<Record<string, any>>({});

export function useI18n() {
	async function initI18n(code: Locale) {
		messages.value = await import(`./locales/${code}.ts`)
			.then(module => module.default)
			.catch(e => console.error(e));
	}

	function t(msg: string, content: { [key: string]: string | number } | null = null) {
		if (!messages.value) {
			console.error('i18n not initialized');
			return;
		}
		if (!Object.keys(messages.value).length)
			return '';
		const val = msg
			.split('.')
			.reduce((val, part) => {
				return val[part] || msg;
			}, messages.value);
		if (typeof val === 'function') {
			return val(content);
		}
		return val;
	}

	return {
		t,
		initI18n,
	};
}
