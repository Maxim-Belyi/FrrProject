<script setup lang="ts">
import IconSVG from '@/app/shared/components/IconSVG.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import ImgTemplate from '@app/shared/components/templates/ImgTemplate.vue';
import { useCopyToClipboard } from '@app/shared/composables/useCopyToClipboards';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed } from 'vue';

interface VideoSource {
	type: string;
	src: string;
}

interface VideoShare {
	social: string;
	link: string;
}

interface VideoData {
	title: string;
	poster: string;
	source: VideoSource[];
	datetime: string;
	link: string;
	share: VideoShare[];
}

const { getModalData } = useModalsStore();
const modalData = computed(() => getModalData<VideoData>('modal-video'));

const { copyToClipboard } = useCopyToClipboard();
const { BASE_URL } = import.meta.env;
</script>

<template>
	<teleport defer to="#modals-container">
		<ModalTemplate id="modal-video" animation="fade-in-up">
			<template #default="{ close }">
				<section class="modal modal--full">
					<div v-if="modalData" class="modal-video">
						<BaseVideo class="modal-video__video video">
							<template #default="{ canPlayHandler, errorHandler }">
								<video class="img__image" :poster="modalData.poster" loop preload="metadata" @loadedmetadata="canPlayHandler" @error="errorHandler">
									<source v-for="(item, i) in modalData.source" :key="i" :src="item.src" :type="item.type" />
								</video>
							</template>
						</BaseVideo>
						<div class="modal-video__info">
							<div class="modal-video__header">
								<span class="modal-video__title title title--h3">{{ modalData.title }}</span>
								<button class="modal-video__close" data-dialog-close aria-label="закрыть" @click="close">
									<IconSVG class="modal__close-icon" name="close" />
								</button>
							</div>
							<div class="modal-video__actions">
								<div class="modal-video__badges badge">
									<div class="badges__list">
										<div class="badge">
											<IconSVG class="badge__icon" name="calendar-mini" />
											<span class="badge__text">{{ modalData.datetime }}</span>
										</div>
									</div>
								</div>
								<div class="modal-video__socials soc-share">
									<div class="soc-share__title text">
										Поделиться
									</div>
									<div class="soc-share__list">
										<a v-for="item in modalData.share" :key="item.social" target="_blank" :href="item.link" class="soc-share__item btn btn--color-secondary">
											<ImgTemplate
												class-name="btn__icon"
												:src="`${BASE_URL}img/content/socials/${item.social}.svg`"
											/>
										</a>
										<button class="soc-share__item btn btn--color-secondary" @click="copyToClipboard(modalData.link, 'Ссылка скопирована')">
											<ImgTemplate
												class-name="btn__icon"
												:src="`${BASE_URL}img/content/socials/copy.svg`"
											/>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</template>
		</ModalTemplate>
	</teleport>
</template>

<style lang="sass">
@import '@styles/shared/modals/modal-video.sass'
</style>
