<script setup lang="ts">
import type { NavigationOptions, PaginationOptions, Swiper as SwiperInstance } from 'swiper/types';
import IconSVG from '@app/shared/components/IconSVG.vue';
import { Autoplay, Navigation, Pagination, Thumbs } from 'swiper/modules';
import { Swiper } from 'swiper/vue';

import { ref } from 'vue';
import 'swiper/scss/effect-creative';

const slider = ref<SwiperInstance | null>(null);
const modules = [Navigation, Pagination, Thumbs, Autoplay];
const navigation: NavigationOptions = {
	nextEl: '.hero-slider .swiper-button-next',
	prevEl: '.hero-slider .swiper-button-prev',
};
const pagination: PaginationOptions = {
	el: '.hero-slider__pagination',
	type: 'custom',
	renderCustom(swiper, current, total) {
		return `
			<span class="hero-slider__current">${current.toString().padStart(2, '0')}</span>
			<span>/</span>
			<span class="hero-slider__total">${total.toString().padStart(2, '0')}</span>
		`;
	},
};
const breakpoints = {
	1: {
		autoplay: {
			delay: 5000,
			disableOnInteraction: false,
		},
	},
	769: {
		autoplay: false,
	},
};

function onSwiper(swiper: SwiperInstance) {
	slider.value = swiper;
}
</script>

<template>
	<div class="hero-slider">
		<div class="hero-slider__info">
			<span class="hero-slider__title">Последние новости</span>
			<div class="hero-slider__pagination" />
		</div>
		<Swiper
			class="hero-slider__slider"
			:class="$attrs.class"
			:modules="modules"
			:slides-per-view="1"
			:space-between="0"
			:speed="500"
			:navigation
			:pagination
			:breakpoints
			:autoplay="{
				delay: 5000,
				disableOnInteraction: false,
			}"
			loop
			@swiper="onSwiper"
		>
			<template #wrapper-start>
				<slot />
			</template>
		</Swiper>
		<div class="hero-slider__navigation">
			<div class="hero-slider__btn btn btn--color-tertiary swiper-button-next">
				<IconSVG class="icon" name="arrow-right" />
			</div>
			<div class="hero-slider__btn btn btn--color-tertiary swiper-button-prev">
				<IconSVG class="icon" name="arrow-left" />
			</div>
		</div>
	</div>
</template>
