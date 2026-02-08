<script setup lang="ts">
import type { Instance, Placement } from '@popperjs/core';

import { createPopper } from '@popperjs/core';
import { onClickOutside } from '@vueuse/core';
import { computed, onMounted, ref, useTemplateRef } from 'vue';

interface Props {
	placement?: Placement;
	offset?: [number, number];
	flip?: boolean;
	showOnClick?: boolean;
}

const {
	placement = 'auto',
	offset = [0, 12],
	flip = true,
	showOnClick = false,
} = defineProps<Props>();

const tooltip = useTemplateRef<HTMLElement>('tooltip');
const trigger = useTemplateRef<HTMLElement>('trigger');
const tooltipContent = useTemplateRef<HTMLElement>('tooltipContent');

const popperWillHide = ref(false);
const popperHideTimeout = ref<number | null>(null);
const popperShown = ref(false);
const popper = ref<Instance>();

const popperEvents = computed(() => ({
	mouseleave: startHidingPopover,
	...(showOnClick ? { click: showPopper } : { mouseenter: showPopper }),
}));

onMounted(() => {
	initPopper();
});

function initPopper() {
	if (!trigger.value || !tooltip.value)
		return;

	popper.value = createPopper(
		trigger.value,
		tooltip.value,
		{
			placement,
			modifiers: [
				{
					name: 'preventOverflow',
					options: {
						padding: 8,
					},
				},
				{
					name: 'offset',
					options: {
						offset,
					},
				},
				{
					name: 'flip',
					enabled: flip,
					options: {
						padding: 8,
					},
				},
			],
		},
	);
}

function showPopper() {
	if (popperShown.value) {
		popperWillHide.value = false;
		return;
	}

	popperShown.value = true;

	popper.value?.setOptions(options => ({
		...options,
		modifiers: [
			...(options.modifiers as unknown[]),
			{
				name: 'eventListeners',
				enabled: true,
			},
		],
	}));

	popper.value?.update();
}

function startHidingPopover() {
	popperWillHide.value = true;

	if (popperHideTimeout.value)
		clearTimeout(popperHideTimeout.value);

	popperHideTimeout.value = setTimeout(() => {
		if (popperWillHide.value)
			hidePopper();
	}, 300);
}

function hidePopper() {
	popperWillHide.value = false;
	popperShown.value = false;

	popper.value?.setOptions(options => ({
		...options,
		modifiers: [
			...(options.modifiers as unknown[]),
			{
				name: 'eventListeners',
				enabled: true,
			},
		],
	}));

	popper.value?.update();
}

function onTouch() {
	if (popperShown.value) {
		hidePopper();
	}
	else {
		showPopper();
	}
}

// https://vueuse.org/core/onClickOutside/#onclickoutside
onClickOutside(tooltip, () => hidePopper());
</script>

<template>
	<div
		ref="tooltip"
		class="tooltip"
		v-on="popperEvents"
	>
		<div ref="trigger" class="tooltip__trigger" aria-describedby="tooltip" @touchend="onTouch">
			<slot :is-open="popperShown" />
		</div>
		<div v-show="popperShown" ref="tooltipContent" class="tooltip__content" role="tooltip">
			<slot name="tooltip" />
		</div>
	</div>
</template>

<style scoped lang="sass">
.tooltip
	&__trigger
		position: relative

	&__content
		z-index: var(--z-index-dropdown)
		width: 100%
</style>
