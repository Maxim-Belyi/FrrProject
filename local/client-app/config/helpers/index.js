import { readdirSync, statSync } from 'node:fs';
import { join } from 'node:path';

function searchDirectory(dir, includePath, excludeDir) {
	if (includePath === void 0)
		includePath = true;
	if (excludeDir === void 0)
		excludeDir = '';
	const files = readdirSync(dir);
	let result = [];
	files.forEach((file) => {
		const filePath = join(dir, file);
		const fileStat = statSync(filePath);
		if (fileStat.isDirectory() && file !== excludeDir)
			result = result.concat(searchDirectory(filePath, includePath, excludeDir));

		else if (fileStat.isFile())
			result.push(includePath ? filePath : file);
	});
	return result;
}
function getAllPages(pagesDir) {
	const pages = searchDirectory(pagesDir);
	return pages
		.filter((file) => { return file.match(/\.(html|pug)$/); })
		.reduce((acc, page) => {
			const name = page
				.replace(pagesDir, '')
				.replace(/\.(html|pug)$/, '')
				.replace(/^\//, '');
			acc[name] = page;
			return acc;
		}, {});
}
export { getAllPages, searchDirectory };
