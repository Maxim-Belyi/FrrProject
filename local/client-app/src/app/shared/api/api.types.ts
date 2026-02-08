export interface BaseResponse<T> {
	status: 'success' | 'error';
	data: T;
	errors?: ResponseError[];
	message: string;
	success: boolean;
}

export interface ResponseError {
	message: string;
	code: string;
	customData?: unknown;
}
