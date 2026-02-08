<script setup lang="ts">
import type { GallerySliderSlide } from '@app/shared/components/sliders/GallerySlider.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import IconSVG from '@app/shared/components/IconSVG.vue';
import GallerySlider from '@app/shared/components/sliders/GallerySlider.vue';

interface Props {
	id?: string;
	title: string;
	slides: GallerySliderSlide[];
}

defineProps<Props>();
</script>

<template>
	<ModalTemplate :id="id || 'modal-gallery'" class-name="modal-gallery">
		<template #default="{ close }">
			<div class="modal-gallery__modal">
				<button class="modal__close" data-dialog-close aria-label="закрыть" @click="close">
					<IconSVG class="modal__close-icon" name="close" />
				</button>
				<div class="modal-gallery__content">
					<GallerySlider
						:title="title"
						:slides="slides"
						class="modal-gallery__slider"
					/>
				</div>
			</div>
		</template>
	</ModalTemplate>
</template>

<style lang="sass">
@import '@styles/shared/modals/modal-gallery.sass'
</style>
