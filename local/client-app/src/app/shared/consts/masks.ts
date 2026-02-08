import type { MaskInputOptions, MaskOptions } from 'maska';

const phoneMask: MaskInputOptions = {
	mask: '+# (###) ###-##-##',
	preProcess: (value: string) => {
		if (value.startsWith('9') || value.startsWith('+9'))
			return value.replace(/^9|^\+\d/, '79');

		return value.replace(/^\d|^\+\d/, '7');
	},
	eager: true,
};

const codeMask: MaskOptions = {
	mask: '######',
	eager: true,
};

const decimalMask: MaskOptions = {
	mask: '0.9',
	eager: true,
	tokens: {
		0: {
			pattern: /\d/,
			multiple: true,
		},
		9: {
			pattern: /\d/,
			optional: true,
			multiple: true,
		},
	},
};

const integerMask: MaskOptions = {
	mask: '0',
	tokens: {
		0: {
			pattern: /\d/,
			multiple: true,
		},
	},
};

export { codeMask, decimalMask, integerMask, phoneMask };
