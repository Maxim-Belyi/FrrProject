<script setup lang="ts">
import type { NavigationOptions, SwiperOptions } from 'swiper/types';
import { Navigation, Pagination } from 'swiper/modules';
import { Swiper } from 'swiper/vue';

interface Props {
	id: string;
	slidesPerView?: SwiperOptions['slidesPerView'];
	breakpoints?: SwiperOptions['breakpoints'];
	centeredSlides?: SwiperOptions['centeredSlides'];
	loop?: SwiperOptions['loop'];
	spaceBetween?: SwiperOptions['spaceBetween'];
	pagination?: SwiperOptions['pagination'];
}

const {
	id,
	slidesPerView = 1,
	spaceBetween = 8,
	pagination = false,
} = defineProps<Props>();

const modules = [Navigation, Pagination];
const navigation: NavigationOptions = {
	nextEl: `#${id} .swiper-button-next`,
	prevEl: `#${id} .swiper-button-prev`,
};
</script>

<template>
	<Swiper
		:modules
		:slides-per-view
		:space-between
		:centered-slides
		:navigation
		:pagination
		:loop
		:breakpoints
	>
		<template #wrapper-start>
			<slot />
		</template>
	</Swiper>
</template>
