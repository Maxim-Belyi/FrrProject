import { getNoun } from '@app/shared/utils/getNoun';

export default {
	test: {
		hello: 'Привет, ',
		applesCount: ({ count }: { count: number }) => `${count} ${getNoun(5, { single: 'яблоко', dual: 'яблока', many: 'яблок' })}`,
		agreement: ({ policy, personal }: { policy: string; personal: string }): string => `Я соглашаюсь <a href=“${policy}” class=“link” target=“_blank”>с политикой конфиденциальности</a> и <a href=“${personal}” class=“link” target=“_blank”>пользовательским соглашением</a>`,

	},
};
