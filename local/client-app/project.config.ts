import { basename, resolve } from 'node:path';
import process from 'node:process';
import { loadEnv } from 'vite';

const SOURCE_DIR = resolve(__dirname, 'src');
const OUTPUT_DIR = resolve(__dirname, '../frontend-build');
const INITIAL_OUTPUT_DIR = resolve(__dirname, 'dist');
const VIEWS_DIR = resolve(SOURCE_DIR, 'views');

const BASE_PORT = 8080;

const PROJECT_ENV = loadEnv('', process.cwd());

const PROJECT_NAME = PROJECT_ENV.VITE_APP_NAME ?? basename(__dirname);
const PROJECT_TITLE = PROJECT_ENV.VITE_APP_TITLE ?? PROJECT_NAME;
const PROJECT_URL = PROJECT_ENV.VITE_APP_BASE_URL ?? `/${PROJECT_NAME}/`;
const PROJECT_INITIAL_URL = PROJECT_ENV.VITE_APP_INITIAL_URL ?? `/${PROJECT_NAME}/`;

export {
	BASE_PORT,
	INITIAL_OUTPUT_DIR,
	OUTPUT_DIR,
	PROJECT_INITIAL_URL,
	PROJECT_NAME,
	PROJECT_TITLE,
	PROJECT_URL,
	SOURCE_DIR,
	VIEWS_DIR,
};
