<script setup lang="ts">
import type { IFileData } from '@/app/shared/composables/useFileData';

import type { ErrorObject } from '@vuelidate/core';
import InputTemplate from '@/app/shared/components/templates/InputTemplate.vue';
import { useFileData } from '@/app/shared/composables/useFileData';
import IconSVG from '@app/shared/components/IconSVG.vue';
import useBaseStore from '@app/shared/stores/base/base.store';
import { useDropZone } from '@vueuse/core';
import { computed, useId, useTemplateRef } from 'vue';

// https://developer.mozilla.org/en-US/docs/Web/HTTP/MIME_types/Common_types
export type MIMETypes =
	// Text types
	| 'text/css'
	| 'text/html'
	| 'text/javascript'
	| 'text/plain'
	| 'text/xml'

	// Image types
	| 'image/gif'
	| 'image/jpeg'
	| 'image/png'
	| 'image/svg+xml'
	| 'image/webp'

	// Audio types
	| 'audio/midi'
	| 'audio/mpeg'
	| 'audio/ogg'
	| 'audio/wav'
	| 'audio/webm'

	// Video types
	| 'video/mp4'
	| 'video/mpeg'
	| 'video/ogg'
	| 'video/webm'

	// Application types
	| 'application/json'
	| 'application/ld+json'
	| 'application/msword'
	| 'application/pdf'
	| 'application/vnd.ms-excel'
	| 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
	| 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
	| 'application/vnd.mozilla.xul+xml'
	| 'application/x-www-form-urlencoded'
	| 'application/zip'
	| 'application/octet-stream'
	| 'application/rtf'

	// Font types
	| 'font/otf'
	| 'font/ttf'
	| 'font/woff'
	| 'font/woff2'

	// Multipart types
	| 'multipart/form-data'

	// Other
	| 'application/x-rar-compressed'
	| 'application/x-tar'
;

interface Props {
	accept: MIMETypes[];
	id?: string;
	multiple?: boolean;
	label?: string;
	placeholder?: string;
	readonly?: boolean;
	disabled?: boolean;
	required?: boolean;
	name?: string;
	title?: string;
	errors?: string[] | ErrorObject[];
	maxSizeMB?: number;
}

const {
	id,
	multiple,
	placeholder = 'Прикрепить файл',
	accept,
	maxSizeMB = 5,
} = defineProps<Props>();

const modelValue = defineModel<string[] | FileList | null>();

const inputFile = useTemplateRef<HTMLInputElement>('inputFile');

const { getFileData } = useFileData();
const { errorMessage } = useBaseStore();
const valueWithData = computed<IFileData[]>(() => {
	if (!modelValue.value)
		return [];

	if (modelValue.value instanceof FileList) {
		return Array.from(modelValue.value).map(file => ({
			...getFileData.value(file),
		}));
	}

	return modelValue.value.map(file => ({
		...getFileData.value(file),
	}));
});

const shouldShowPlaceholder = computed(() => {
	const isEmpty = !modelValue.value || (modelValue.value instanceof FileList && modelValue.value.length === 0) || (Array.isArray(modelValue.value) && modelValue.value.length === 0);
	return isEmpty || multiple;
});

function checkMIMEType(files: File[], acceptedTypes: MIMETypes[]) {
	return files.every(file => acceptedTypes.includes(file.type as MIMETypes));
}

function checkSize(files: File[], maxSizeMB: number = 5): boolean {
	return files.every((file) => {
		if (!file || !(file instanceof File))
			return false;

		const fileSizeInMB = file.size / (1024 * 1024); // Размер файла в мегабайтах
		return fileSizeInMB <= maxSizeMB;
	});
}

function validateFile(files: File[], accept: MIMETypes[] | string[]): boolean {
	// Проверка размера файлов
	if (!checkSize(files, maxSizeMB)) {
		errorMessage(`Размер файла превышает ${maxSizeMB} МБ`);
		return false;
	}

	// Проверка на MIME-type
	if (accept.every(item => item.includes('/'))) {
		if (!checkMIMEType(files, accept as MIMETypes[])) {
			errorMessage('Недопустимый тип-файла');
			return false;
		}
	}

	return true; // Все проверки пройдены
}

function onChange(e: Event) {
	const target: HTMLInputElement = e.target as HTMLInputElement;

	const files: File[] = target.files ? Array.from(target.files) : [];
	if (files.length === 0 || !validateFile(files, accept))
		return;

	const dt = new DataTransfer();
	files.forEach(file => dt.items.add(file));

	if (multiple && modelValue.value instanceof FileList)
		Array.from(modelValue.value).forEach(file => dt.items.add(file));

	modelValue.value = dt.files;
	target.value = '';
}

function deleteFile(deletingFile: IFileData) {
	if (deletingFile.type === 'url') {
		modelValue.value = (modelValue.value as string[]).filter(item => item !== deletingFile.url);
		return;
	}

	const { name } = deletingFile;
	const files = Array.from(modelValue.value as FileList).filter(file => file.name !== name);
	const dt = new DataTransfer();
	files.forEach(file => dt.items.add(file));

	modelValue.value = dt.files;
}

function simulateClick(): void {
	inputFile.value?.click();
}

// DRAG & DROP
const dropZoneRef = useTemplateRef<HTMLElement>('dropZoneRef');

// https://vueuse.org/core/useDropZone/
const { isOverDropZone } = useDropZone(dropZoneRef, {
	onDrop(files: File[] | null) {
		if (!files || files.length === 0 || !validateFile(files, accept))
			return;

		const dt = new DataTransfer();
		files.forEach(file => dt.items.add(file));

		if (multiple && modelValue.value instanceof FileList)
			Array.from(modelValue.value).forEach(file => dt.items.add(file));

		modelValue.value = dt.files;
	},
});

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :id="inputId" :label :required :errors tag="span">
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<div class="input">
			<label
				ref="dropZoneRef"
				tabindex="0"
				class="input__input input__input--file"
				:class="[{ error: errors?.length }, { disabled }, { readonly }]"
				@keydown.enter="simulateClick"
			>
				<input
					:id="inputId"
					ref="inputFile"
					:multiple
					:disabled="disabled || readonly"
					:title
					:accept="accept?.join(', ')"
					:name
					type="file"
					class="input-file"
					@change="onChange"
				>
				<span v-if="shouldShowPlaceholder" class="input__placeholder">{{ isOverDropZone ? 'Перетащите один или несколько файлов в это поле' : placeholder }}</span>
				<template v-else>
					<span v-for="file in valueWithData.slice(0, 1)" :key="file.name" :title="file.name" class="input__value">{{ file.name }}</span>
				</template>
			</label>
			<span class="input__icon input__icon--file">
				<IconSVG class="input__icon-image" name="clip" />
			</span>
		</div>

		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>

		<template v-if="multiple" #underInput>
			<div class="files-preview">
				<div
					v-for="file in valueWithData"
					:key="file.name"
					:title="file.name"
					class="files-preview__item file-preview"
				>
					<img v-if="file.isImage" class="file-preview__img" :src="file.url" :alt="file.name">
					<span v-if="file.ext" class="file-preview__ext">.{{ file.ext }}</span>
					<span
						v-if="!readonly"
						aria-hidden="true"
						tabindex="0"
						role="button"
						class="file-preview__del"
						@click="deleteFile(file)"
						@keydown.enter="deleteFile(file)"
					>
						<span class="file-preview__del-icon" />
					</span>
				</div>
			</div>
		</template>
	</InputTemplate>
</template>
