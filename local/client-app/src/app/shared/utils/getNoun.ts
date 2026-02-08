export interface NounForms {
	single: string;
	dual: string;
	many: string;
}

/**
 * Возвращает правильную форму слова в зависимости от переданного числа.
 *
 * @param count - Числовое значение, на основе которого выбирается форма слова.
 * @param forms - Объект, содержащий три варианта слова: для одного, двух и нескольких предметов.
 * @returns Правильная форма слова в зависимости от числа.
 *
 * @example
 * const count = 1;
 * const noun = getNoun(1, { single: "товар", dual: "товара", many: "товаров" });
 * console.log(noun); // "товар"
 */
export function getNoun(count: number, forms: NounForms) {
	const absCount = Math.abs(count) % 100;
	const remainder = absCount % 10;

	return absCount >= 11 && absCount <= 19
		? forms.many
		: remainder === 1
			? forms.single
			: remainder >= 2 && remainder <= 4
				? forms.dual
				: forms.many;
}
