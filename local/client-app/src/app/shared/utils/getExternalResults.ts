import type { ResponseError } from '@app/shared/api/api.types';

interface Result {
	[key: string]: string[];
}

export function getExternalResults(errors: ResponseError[]): Result {
	return errors.reduce<Result>((acc, error) => {
		acc[error.code] = [error.message];
		return acc;
	}, {});
}
