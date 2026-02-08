<script setup lang="ts">
import type { ProjectResponse } from '@app/modules/project/api/projects.api.types';
import BaseLoader from '@/app/shared/components/ui/BaseLoader.vue';
import ProjectAside from '@app/modules/project/components/ProjectAside.vue';
import ProjectMain from '@app/modules/project/components/ProjectMain.vue';
import useProjectStore from '@app/modules/project/store/projects.store';
import useBaseStore from '@app/shared/stores/base/base.store';
import { onMounted, ref } from 'vue';

const props = defineProps<Props>();

const { isActionPending } = useBaseStore();

interface Props {
	id: number;
}
const { requestProject } = useProjectStore();

const project = ref<ProjectResponse | null>(null);

onMounted(async () => {
	const response = await requestProject({ id: props.id });
	history.pushState({}, '', `?idPoint=${props.id}`);
	project.value = response.data;
});
</script>

<template>
	<div v-if="isActionPending('requestProject')" class="project-loading">
		<BaseLoader position="static" />
	</div>
	<div v-else class="project-detail">
		<template v-if="project">
			<ProjectAside :project />
			<ProjectMain :project />
		</template>
		<template v-else>
			<div class="project-detail__empty">
				<div class="title title--h3">
					Информации о проекте не найдено
				</div>
			</div>
		</template>
	</div>
</template>
