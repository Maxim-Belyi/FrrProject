<script setup lang="ts">
import { useResizeObserver } from '@vueuse/core';
import { ref, useTemplateRef } from 'vue';

type Mode = 'text' | 'block';

interface Props {
	mode?: Mode;
}
const { mode = 'text' } = defineProps<Props>();

const content = useTemplateRef('content');
const isActive = ref(false);
const hasOverflow = ref(false);

useResizeObserver(content, () => {
	if (!content.value)
		return;
	hasOverflow.value = content.value.scrollHeight > content.value.offsetHeight;
});

function toggleActive() {
	isActive.value = !isActive.value;
}
</script>

<template>
	<div
		ref="content"
		class="show-more"
		:class="[
			`show-more--${mode}`,
			{ active: isActive },
			{ hidden: hasOverflow },
		]"
		v-bind="$attrs"
	>
		<slot />
	</div>
	<slot
		name="trigger"
		:has-overflow
		:is-active
		:toggle-active
	/>
</template>
