import { getUrlExtension, isImage } from '@/app/shared/utils/fileHelpers';
import { computed } from 'vue';

export interface IFileData {
	name: string;
	ext: string;
	url: string;
	type: string;
	isImage: boolean;
}

/**
 * @name useFileData
 * @description
 * Миксин для получения данных о файле
 * @example
 * ``` js
 * getFileData('https://example.com/image.jpg') // => { name: 'image', ext: 'jpg', url: 'https://example.com/image.jpg', type: 'url', isImage: true }
 * getFileData(new File([''], 'image.jpg', { type: 'image/jpeg' })) // => { name: 'image', ext: 'jpg', url: 'blob:https://example.com/123', type: 'file', isImage: true }
 * ```
 */
export function useFileData() {
	const getFileData = computed(() => (file: File | string): IFileData => {
		if (typeof file === 'string') {
			const { fileName, fileExtension } = getUrlExtension(file);
			return {
				name: fileName,
				ext: fileExtension,
				url: file,
				type: 'url',
				isImage: isImage(fileExtension),
			};
		}

		const { name, type } = file;
		return {
			name,
			ext: name.split('.').pop() || '',
			url: isImage(type) ? URL.createObjectURL(file) : '',
			type: 'file',
			isImage: isImage(type),
		};
	});

	return { getFileData };
}
