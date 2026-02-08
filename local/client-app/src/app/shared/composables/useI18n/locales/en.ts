import { getNoun } from '@app/shared/utils/getNoun';

export default {
	test: {
		hello: 'Hello, ',
		applesCount: ({ count }: { count: number }) => `${count} ${getNoun(5, { single: 'apple', dual: 'apples', many: 'apples' })}`,
		agreement: ({ policy, personal }: { policy: string; personal: string }): string => `I agree <a href=“${policy}” class=“link” target=“_blank”>with privacy policy</a> and <a href=“${personal}” class=“link” target=“_blank”>user agreement</a>`,

	},
};
