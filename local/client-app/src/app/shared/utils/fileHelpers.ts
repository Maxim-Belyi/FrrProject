import { IMAGE_TYPES } from '@/app/shared/consts/utils';

function getUrlExtension(url: string) {
	const regex = /([^/]+?)\.(?:([^/]+)|$)/;
	const match = url.match(regex);
	if (match) {
		const fileNameWithoutExtension = match[1];
		const fileExtension = match[2];
		const fileName = `${fileNameWithoutExtension}.${fileExtension}`;

		return {
			fileName,
			fileExtension,
			fileNameWithoutExtension,
		};
	}
	return {
		fileName: '',
		fileExtension: '',
		fileNameWithoutExtension: '',
	};
}

function isImage(fileType: string) {
	return IMAGE_TYPES.some(imageType => fileType.includes(imageType));
}

export { getUrlExtension, isImage };
