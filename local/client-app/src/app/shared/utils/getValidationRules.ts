import type { ValidationRule } from '@vuelidate/core';
import { email, helpers, maxLength, minLength, required, sameAs } from '@vuelidate/validators';

export type InputTypes = 'phone' | 'email' | 'string' | 'checkbox' | 'text' | 'code' | 'password' | 'passwordConfirm' | 'file';

export function getValidationRules(inputType: InputTypes = 'string', isRequired = true, field?: string) {
	const { withMessage } = helpers;
	const validators: Record<string, ValidationRule> = {};
	switch (inputType) {
		case 'string': {
			validators.maxLength = withMessage('Максимальное кол-во символов: 30', maxLength(30));
			break;
		}
		case 'text': {
			validators.maxLength = withMessage('Максимальное кол-во символов: 500', maxLength(500));
			break;
		}
		case 'email': {
			validators.minLength = withMessage('Минимальное кол-во символов 2', minLength(2));
			validators.maxLength = withMessage('Максимальное кол-во символов: 30', maxLength(30));
			validators.email = withMessage('Неверный формат email', email);
			break;
		}
		case 'checkbox': {
			validators.sameAs = withMessage('Нужно нажать чекбокс', sameAs(true));
			break;
		}
		case 'phone': {
			validators.minLength = withMessage('Некорректный номер телефона', minLength(18));
			break;
		}
		case 'code': {
			validators.minLength = withMessage('Минимальное количество символов 6', minLength(6));
			break;
		}
		case 'password': {
			validators.minLength = withMessage('Минимальное кол-во символов 6', minLength(6));
			validators.maxLength = withMessage('Максимальное кол-во символов 30', maxLength(30));
			break;
		}
		case 'passwordConfirm': {
			validators.minLength = withMessage('Минимальное кол-во символов 6', minLength(6));
			validators.maxLength = withMessage('Максимальное кол-во символов 30', maxLength(30));
			validators.sameAs = withMessage('Пароли не совпадают', sameAs(field));
			break;
		}
	}

	if (isRequired)
		validators.required = withMessage('Поле обязательно для заполнения', required);

	return validators;
}
