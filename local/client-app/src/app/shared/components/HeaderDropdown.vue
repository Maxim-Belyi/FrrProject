<script setup lang="ts">
import type { Placement } from '@popperjs/core';
import type { TriggerEvent } from 'floating-vue';
import { useResizeObserver } from '@vueuse/core';
import { Dropdown } from 'floating-vue';
import { ref, useTemplateRef } from 'vue';

interface Props {
	ariaId?: string;
	distance?: number;
	skidding?: number;
	instantMove?: boolean;
	disposeTimeout?: number;
	flip?: boolean;
	shift?: boolean;
	shiftCrossAxis?: boolean;
	placement?: Placement;
	triggers?: TriggerEvent[];
	popperTriggers?: TriggerEvent[];
	strategy?: 'absolute' | 'fixed';
	delay?: number | PopperDelay;
	autoHide?: boolean;
	overflowPadding?: number;
	arrowPadding?: number;
	arrowOverflow?: boolean;
	positioningDisabled?: boolean;
	theme?: 'default' | 'null' | 'without-arrow' | 'secondary' | 'header';
}

interface PopperDelay {
	show: number;
	hide: number;
}

const {
	ariaId = null,
	distance = 0,
	skidding = 0,
	instantMove = false,
	disposeTimeout = 0,
	flip = true,
	shift = true,
	shiftCrossAxis = false,
	placement = 'auto',
	triggers = ['click'],
	strategy = 'fixed',
	delay = 0,
	autoHide = true,
	overflowPadding = 20,
	arrowPadding = 10,
	arrowOverflow = true,
	positioningDisabled = false,
	theme = 'header',
} = defineProps<Props>();

const offsetX = ref(0);
const dropdown = useTemplateRef('dropdown');

function getOffset() {
	if (!dropdown.value) {
		return;
	}

	const dropdownElement: HTMLElement = dropdown.value.$el;

	offsetX.value = dropdownElement.offsetLeft;
}

useResizeObserver(document.querySelector('body'), () => {
	getOffset();
});
</script>

<template>
	<Dropdown
		ref="dropdown"
		:aria-describedby="ariaId"
		:distance
		:skidding
		:instant-move="instantMove"
		:dispose-timeout="disposeTimeout"
		:flip
		:shift
		:shift-cross-axis="shiftCrossAxis"
		:placement
		:triggers
		:popper-triggers="popperTriggers"
		:strategy="strategy"
		:overflow-padding="overflowPadding"
		:arrow-padding="arrowPadding"
		:arrow-overflow="arrowOverflow"
		:delay
		:auto-hide="autoHide"
		:positioning-disabled="positioningDisabled"
		:container="ariaId ? `[aria-describedby='${ariaId}']` : '#modals-container'"
		:theme="theme"
		boundary="body"
		auto-boundary-max-size
		prevent-overflow
		:style="`--left: ${offsetX}px;`"
		@show="getOffset"
	>
		<slot name="trigger" />

		<template #popper="{ hide }">
			<div class="header-menu__dropdown">
				<slot :hide="hide" />
			</div>
		</template>
	</Dropdown>
</template>

<style lang="sass" src="@styles/shared/libs/floating-vue.sass">
</style>
