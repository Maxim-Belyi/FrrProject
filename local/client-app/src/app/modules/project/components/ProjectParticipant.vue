<script setup lang="ts">
import type { TeamMember } from '@app/modules/project/api/projects.api.types';

interface Props {
	participant: TeamMember;
}

defineProps<Props>();
</script>

<template>
	<div class="employee-item employee-item--row">
		<div class="employee-item__info">
			<div class="employee-item__title title title--h5" v-html="participant.name" />
			<div class="employee-item__caption text">
				{{ participant.description }}
			</div>
		</div>
	</div>
</template>

<style scoped lang="sass">

</style>
