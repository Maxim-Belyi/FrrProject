<script setup lang="ts">
import type { PaginationOptions, Swiper as SwiperInstance } from 'swiper/types';
import ImgTemplate from '@app/shared/components/templates/ImgTemplate.vue';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { storeToRefs } from 'pinia';
import { Keyboard, Pagination } from 'swiper/modules';
import { Swiper } from 'swiper/vue';
import { ref } from 'vue';

export interface GallerySliderSlide {
	src: string;
	alt?: string;
	width?: string;
	height?: string;
}

interface GallerySliderProps {
	title: string;
	slides: GallerySliderSlide[];
}

defineProps<GallerySliderProps>();

const { currentGallerySlide } = storeToRefs(useModalsStore());

const preload = 3;
const preloadedSlides = new Set();
const slider = ref<SwiperInstance | null>(null);
const pagination: PaginationOptions = {
	el: '#gallery-slider .gallery-slider__pagination',
	type: 'fraction',
	renderFraction(currentClass, totalClass) {
		return `
			<span class="${currentClass}"></span>
			<span>/</span>
			<span class="${totalClass}"></span>
		`;
	},
};
const breakpoints = {
	769: {
		slidesPerView: 1.4,
	},
	1: {
		slidesPerView: 1,
	},
};

function onSwiper(swiper: SwiperInstance) {
	slider.value = swiper;

	initSlideMedia();
}

function onSlideChange() {
	if (!slider.value)
		return;

	initSlideMedia();
}

function initSlideMedia() {
	if (!slider.value)
		return;

	currentGallerySlide.value = slider.value.activeIndex;

	const minSlide = currentGallerySlide.value - preload < 0 ? 0 : currentGallerySlide.value - preload;
	const maxSlide = currentGallerySlide.value + preload > slider.value.slides.length + 1 ? slider.value.slides.length + 1 : currentGallerySlide.value + preload;

	for (let i = minSlide; i <= maxSlide; i++) {
		preloadedSlides.add(i);
	}
}
</script>

<template>
	<div id="gallery-slider" class="gallery-slider">
		<div class="gallery-slider__title title title--h3">
			{{ title }}
		</div>

		<Swiper
			class="gallery-slider__slider"
			:modules="[Pagination, Keyboard]"
			:slides-per-view="1.5"
			:space-between="8"
			:breakpoints
			:pagination
			centered-slides
			:speed="500"
			:initial-slide="currentGallerySlide || 0"
			slide-to-clicked-slide
			:keyboard="{
				enabled: true,
			}"
			@swiper="onSwiper"
			@slide-change="onSlideChange"
		>
			<template #wrapper-start>
				<div v-for="(slide, key) in slides" :key class="gallery-slider__slide swiper-slide">
					<ImgTemplate
						v-if="preloadedSlides.has(key)"
						class-name="gallery-slider__media"
						:src="slide.src"
						:alt="slide.alt"
						:width="slide.width"
						:height="slide.height"
					/>
				</div>
			</template>

			<template v-if="slides.length > 1" #container-end>
				<div class="gallery-slider__pagination" />
			</template>
		</Swiper>
	</div>
</template>

<style lang="sass">
@import '@styles/shared/ui/gallery-slider.sass'
</style>
