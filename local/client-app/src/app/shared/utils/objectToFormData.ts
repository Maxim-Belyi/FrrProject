import {
	isArrayOrObject,
	isBoolean,
	isDate,
	isFile,
	isNull,
	isUndefined,
} from '@/app/shared/utils/helpers';

interface DataObject {
	[key: string]: any;
}

function getKey(key: string, parentKey: string): string {
	return parentKey ? `${parentKey}[${key}]` : key;
}

type Param = [string, string | File];
type Params = Array<Param>;

function getParams(data: DataObject | unknown[], parentKey = ''): Params {
	const result: Params = [];

	Object.entries(data).forEach(([key, value]) => {
		if (isArrayOrObject(value)) {
			result.push(...getParams(value, getKey(key, parentKey)));
		}

		else if (isBoolean(value)) {
			result.push([getKey(key, parentKey), encodeURIComponent(value ? 'Y' : 'N')]);
		}

		else if (isDate(value)) {
			result.push([getKey(key, parentKey), encodeURIComponent(value.toISOString())]);
		}

		else if (isFile(value)) {
			if (value instanceof FileList) {
				const files = Array.from(value);
				if (files.length > 1)
					result.push(...getParams(files, getKey(key, parentKey)));
				else
					result.push([getKey(key, parentKey), files[0]]);
			}
			else {
				result.push([getKey(key, parentKey), value]);
			}
		}

		else if (!isNull(value) && !isUndefined(value)) {
			result.push([getKey(key, parentKey), encodeURIComponent(value as string)]);
		}
	});

	return result;
}

export function queryString(data: DataObject): string {
	return `?${getParams(data)
		.map(arr => arr.join('='))
		.join('&')}`;
}

export function objectToFormData(data: DataObject): FormData {
	const params = getParams(data);
	const fd = new FormData();

	params.forEach(([key, value]) => {
		if (typeof value === 'string')
			fd.append(`${key}`, decodeURIComponent(value));

		else
			fd.append(`${key}`, value);
	});

	return fd;
}
