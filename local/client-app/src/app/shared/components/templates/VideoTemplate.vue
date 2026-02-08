<script lang="ts" setup>
import { useTemplateRef } from 'vue';

export interface VideoSource {
	src: string;
	type: 'video/webm' | 'video/mp4';
}

export interface Video {
	poster: string;
	sources: VideoSource[];
}

interface Props {
	video: Video;
	loading?: 'lazy' | 'eager';
	muted?: boolean;
	controls?: boolean;
	autoplay?: boolean;
	loop?: boolean;
}

const {
	loading = 'lazy',
} = defineProps<Props>();

const videoRef = useTemplateRef<HTMLVideoElement>('video');

defineExpose({ videoRef });
</script>

<template>
	<div class="img">
		<video
			ref="video"
			class="img__image"
			:poster="video.poster"
			:loading
			:controls
			:muted
			:loop
			:autoplay
		>
			<source
				v-for="(source, key) in video.sources"
				:key
				:src="source.src"
				:type="source.type"
			>
		</video>
	</div>
</template>
