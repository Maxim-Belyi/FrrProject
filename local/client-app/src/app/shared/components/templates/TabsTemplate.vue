<script setup lang="ts">
import { computed, ref } from 'vue';

type Tab = number | string;

interface Props {
	activeClass: string;
	initialTab?: Tab;
	isHover?: boolean;
}

const { initialTab, isHover, activeClass } = defineProps<Props>();

const activeTab = ref<number | string>(initialTab || '');

const tabEvents = computed(() => (tab: Tab) => ({
	...(isHover ? { mouseover: (e: PointerEvent) => setTab(e, tab) } : { click: (e: PointerEvent) => setTab(e, tab) }),
}));

function setTab(event: PointerEvent, tab: Tab): void {
	activeTab.value = tab;
	if (event.target instanceof HTMLElement) {
		event.target.scrollIntoView({
			behavior: 'smooth',
			block: 'nearest',
			inline: 'center',
		});
	}
}

function getActiveClass(tab: Tab) {
	return activeTab.value === tab ? activeClass : '';
}
</script>

<template>
	<slot
		:active-tab
		:tab-events
		:get-active-class
	/>
</template>
