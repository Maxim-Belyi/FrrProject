<script setup lang="ts">
import type { NavigationOptions, PaginationOptions, Swiper as SwiperInstance } from 'swiper/types';
import { Navigation, Pagination } from 'swiper/modules';
import { Swiper } from 'swiper/vue';
import { ref } from 'vue';

const slider = ref<SwiperInstance | null>(null);
const modules = [Navigation, Pagination];
const navigation: NavigationOptions = {
	nextEl: '#content-slider .swiper-button-next',
	prevEl: '#content-slider .swiper-button-prev',
};
const pagination: PaginationOptions = {
	el: '#content-slider .swiper-pagination',
};

function onSwiper(swiper: SwiperInstance) {
	slider.value = swiper;
}
</script>

<template>
	<Swiper
		id="content-slider"
		:modules
		:slides-per-view="1"
		:space-between="8"
		:navigation
		:pagination
		@swiper="onSwiper"
	>
		<template #wrapper-start>
			<slot />
		</template>

		<template #container-end>
			<div class="swiper-pagination" />
		</template>
	</Swiper>
</template>
