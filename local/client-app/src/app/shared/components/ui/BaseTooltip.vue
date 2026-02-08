<script setup lang="ts">
import type { Instance } from '@popperjs/core';

import { createPopper } from '@popperjs/core';
import { onClickOutside } from '@vueuse/core';
import { computed, onMounted, ref, useTemplateRef } from 'vue';

interface Props {
	placement?: 'top' | 'bottom' | 'left' | 'right';
	offset?: [number, number];
	flip?: boolean;
	showOnClick?: boolean;
}

const {
	placement = 'top',
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
	if (!trigger.value || !tooltipContent.value)
		return;

	popper.value = createPopper(
		trigger.value,
		tooltipContent.value,
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
	}, 500);
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

function clearHideTimeout() {
	if (popperHideTimeout.value)
		clearTimeout(popperHideTimeout.value);
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
		<div ref="trigger" class="tooltip__trigger" aria-describedby="tooltip">
			<slot />
		</div>
		<div
			v-show="popperShown"
			ref="tooltipContent"
			class="tooltip__content"
			role="tooltip"
			@mouseenter.stop="clearHideTimeout"
		>
			<div class="tooltip__arrow" data-popper-arrow />
			<slot name="tooltip" />
		</div>
	</div>
</template>

<style scoped lang="sass">
.tooltip
	&__content
		max-width: 280px
		padding: 16px 24px
		border-radius: var(--radius-l)
		background: var(--color-neutral-100)
		font-size: var(--font-size-text-m)
		line-height: var(--line-height-xs)

		&[data-popper-placement^='top'] > .tooltip__arrow
			bottom: -8px

		&[data-popper-placement^='bottom'] > .tooltip__arrow
			top: -8px

		&[data-popper-placement^='left'] > .tooltip__arrow
			right: -8px

		&[data-popper-placement^='right'] > .tooltip__arrow
			left: -8px

	&__arrow
		position: absolute
		z-index: -1
		visibility: hidden
		size: 24px
		background: inherit

		&::before
			content: ''
			position: absolute
			visibility: visible
			size: 24px
			background: inherit
			transform: rotate(45deg)
</style>
