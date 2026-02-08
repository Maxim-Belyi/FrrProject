<script setup lang="ts">
import type { PartnerProjectsResponse } from '@app/modules/partners/api/partners.api.types';
import ImgTemplate from '@app/shared/components/templates/ImgTemplate.vue';

interface Props {
	project: PartnerProjectsResponse;
}

defineProps<Props>();

const { BASE_URL } = import.meta.env;
</script>

<template>
	<div class="project-card">
		<template v-if="project?.pictures.length">
			<ImgTemplate class-name="project-card__image" :src="project?.pictures[0]?.src" />
		</template>
		<template v-else>
			<ImgTemplate class-name="project-card__image" :src="`${BASE_URL}/img/content/media/plug.webp`" />
		</template>
		<div class="project-card__info">
			<div class="project-card__title title title--h4" :title="project.label">
				{{ project.label }}
			</div>
			<div class="project-card__caption text">
				{{ project.address }}
			</div>
		</div>
		<a :href="project.link" class="project-card__link"></a>
	</div>
</template>
