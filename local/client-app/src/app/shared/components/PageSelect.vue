<script setup lang="ts">
import InputSelect from '@app/shared/components/inputs/InputSelect.vue';
import { computed, ref } from 'vue';

interface PageOption {
	id: number;
	label: string;
	link: string;
}

interface Props {
	options: PageOption[];
	value?: number;
}

const props = defineProps<Props>();

const pageId = ref<number>(props.value || props.options[0].id);
const currentPage = computed(() => {
	return props.options[props.options.findIndex((page: PageOption) => pageId.value === page.id)];
});

function changePage() {
	window.location.href = currentPage.value.link;
}
</script>

<template>
	<InputSelect
		v-model="pageId"
		:can-clear="false"
		:can-deselect="false"
		value-prop="id"
		:options
		@change="changePage"
	/>
</template>
