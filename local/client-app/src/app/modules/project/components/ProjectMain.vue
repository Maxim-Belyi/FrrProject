<script setup lang="ts">
import type { ProjectResponse } from '@app/modules/project/api/projects.api.types';
import ProjectParticipant from '@app/modules/project/components/ProjectParticipant.vue';

interface Props {
	project: ProjectResponse;
}

defineProps<Props>();
</script>

<template>
	<div class="project-detail__main">
		<div class="project-detail__content" v-html="project.description"></div>
		<div v-if="project.team" class="project-detail__command project-command">
			<div class="project-command__header">
				<div class="project-command__title title title--h5">
					Проектная команда
				</div>
			</div>
			<div class="project-command__list">
				<ProjectParticipant v-for="participant in project.team" :key="participant.id" :participant />
			</div>
		</div>
	</div>
</template>
