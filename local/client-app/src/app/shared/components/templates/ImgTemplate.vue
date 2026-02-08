<script lang="ts" setup>
import type { ViewportNames } from '@app/shared/consts/viewport';
import { VIEWPORT_SIZES } from '@app/shared/consts/viewport';

interface Breakpoint {
	src: string;
	mediaQuery: 'max-width' | 'min-width';
	breakpoint: ViewportNames;
}

interface Props {
	className: string;
	src: string;
	width?: string;
	height?: string;
	alt?: string;
	loading?: 'lazy' | 'eager';
	images?: Breakpoint[];
}

const {
	alt = 'alt',
	width = 'width',
	height = 'height',
	loading = 'lazy',
} = defineProps<Props>();
</script>

<template>
	<picture class="img" :class="className">
		<source
			v-for="(image, key) in images"
			:key
			:srcset="image.src"
			:media="`(${image.mediaQuery}: ${image.mediaQuery === 'max-width' ? VIEWPORT_SIZES[image.breakpoint] / 16 : VIEWPORT_SIZES[image.breakpoint] / 16 + 0.1}em)`"
		>
		<img
			class="img__image"
			:src="src"
			:width
			:height
			:alt
			:loading
		/>
	</picture>
</template>
