import isArray from 'lodash/isArray';
import isBoolean from 'lodash/isBoolean';
import isDate from 'lodash/isDate';
import isEqual from 'lodash/isEqual';
import isFunction from 'lodash/isFunction';
import isNaN from 'lodash/isNaN';
import isNull from 'lodash/isNull';
import isPlainObject from 'lodash/isPlainObject';
import isUndefined from 'lodash/isUndefined';

function isFile(value: unknown): value is File | FileList {
	return value instanceof File || value instanceof FileList;
}

function isArrayOrObject(value: unknown): value is Record<string, unknown> | unknown[] {
	return isPlainObject(value) || isArray(value);
}

export {
	isArray,
	isArrayOrObject,
	isBoolean,
	isDate,
	isEqual,
	isFile,
	isFunction,
	isNaN,
	isNull,
	isPlainObject,
	isUndefined,
};
