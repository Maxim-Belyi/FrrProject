<script setup lang="ts">
import type { PartnerPayload, PartnerProjectsResponse } from '@app/modules/partners/api/partners.api.types';
import BaseLoader from '@/app/shared/components/ui/BaseLoader.vue';
import PartnerProjectCard from '@app/modules/partners/components/PartnerProjectCard.vue';
import usePartnerStore from '@app/modules/partners/store/partners.store';
import useBaseStore from '@app/shared/stores/base/base.store';
import { onMounted, ref } from 'vue';

const props = defineProps<Props>();

interface Props {
	data: PartnerPayload;
}

const { isActionPending } = useBaseStore();

const { requestPartnerProjects } = usePartnerStore();

const projects = ref<PartnerProjectsResponse[]>([]);

onMounted(async () => {
	const response = await requestPartnerProjects(props.data);
	projects.value = response.data;
});
</script>

<template>
	<div v-if="isActionPending('requestPartnerProjects')" class="partner-projects-loading">
		<BaseLoader position="static" />
	</div>
	<div v-else class="partner-projects">
		<template v-if="projects">
			<div class="partner-projects__list">
				<PartnerProjectCard v-for="project in projects" :key="project.id" :project />
			</div>
		</template>
		<template v-else>
			<div class="partner-projects__empty">
				<div class="title title--h3">
					Проекты не найдены
				</div>
			</div>
		</template>
	</div>
</template>
