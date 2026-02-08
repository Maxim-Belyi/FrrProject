<script setup lang="ts">
import IconSVG from '@/app/shared/components/IconSVG.vue';
import { onUnmounted, ref } from 'vue';

const video = ref<HTMLVideoElement | null>(null);
const isPlaying = ref(false);
const isErrored = ref(false);

onUnmounted(() => {
	video.value?.removeEventListener('play', playHandler);
	video.value?.removeEventListener('pause', pauseHandler);
});

function canPlayHandler(event: Event) {
	video.value = event.target as HTMLVideoElement;

	video.value.addEventListener('play', playHandler);

	video.value.addEventListener('pause', pauseHandler);
}

function ErrorHandler() {
	isErrored.value = true;
}

function playHandler() {
	isPlaying.value = true;
}

function pauseHandler() {
	isPlaying.value = false;
}

function clickHandler() {
	if (isPlaying.value)
		video.value?.pause();
	else
		video.value?.play();
}
</script>

<template>
	<div class="video" :class="{ 'video--playing': isPlaying }" @click="clickHandler">
		<slot :can-play-handler="canPlayHandler" :error-handler="ErrorHandler" />
		<slot v-if="!isErrored" name="btn">
			<button class="video__play btn">
				<IconSVG class="video__play-icon btn__icon" :name="isPlaying ? 'pause' : 'play'" />
			</button>
		</slot>
		<slot v-else name="error">
			<span class="video__error">Ошибка загрузки видео</span>
		</slot>
	</div>
</template>

<style scoped lang="sass">
.video
	--width: 100%
	--object-fit: cover
	position: relative
	isolation: isolate
	cursor: pointer
	// -----------------

	&__play
		position: absolute
		top: 50%
		left: 50%
		z-index: var(--z-index-above)
		transform: translate(-50%, -50%)

		&-icon
			color: currentcolor

	&__error
		position: absolute
		top: 50%
		left: 50%
		z-index: var(--z-index-above)

		color: var(--color-white)
		font-size: var(--font-size-text-s)
		line-height: var(--line-height-s)

		text-align: center
		text-transform: uppercase

		transform: translate(-50%, -50%)

	&--playing
		@include hover
			& .video
				&__play
					opacity: 1

		& .video
			&__play
				opacity: 0
</style>
