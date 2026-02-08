<script setup lang="ts">
import type { ProjectResponse } from '@app/modules/project/api/projects.api.types';
import SectionSlider from '@/app/shared/components/sliders/SectionSlider.vue';
import ImgTemplate from '@app/shared/components/templates/ImgTemplate.vue';

interface Props {
	project: ProjectResponse;
}

const { project } = defineProps<Props>();
const { BASE_URL } = import.meta.env;
</script>

<template>
	<div class="project-detail__aside">
		<SectionSlider v-if="project.pictures?.length" id="project-detail-slider" class="project-detail__slider" :space-between="0" :pagination="{ clickable: true }">
			<div v-for="img in project.pictures" :key="img.id" class="swiper-slide">
				<ImgTemplate class-name="project-detail__image" :src="img.src" />
			</div>
		</SectionSlider>
		<ImgTemplate v-else class-name="project-detail__image" :src="`${BASE_URL}/img/content/media/plug.webp`" />

		<div class="project-detail__info">
			<div class="project-detail__head">
				<div class="project-detail__title title title--h2">
					{{ project.label }}
				</div>
				<div class="project-detail__caption title title--h4">
					{{ project.address }}
				</div>
			</div>
			<div class="project-detail__status">
				<div class="project-detail__status-title text">
					Статус
				</div>
				<div :class="`tag tag--${project.status.xmlId}`">
					<span class="tag__color"></span>
					<span class="tag__text">{{ project.status.name }}</span>
				</div>
			</div>
		</div>
	</div>
</template>
