<script setup lang="ts">
import { onClickOutside } from '@vueuse/core';
import { computed, reactive, ref, useTemplateRef } from 'vue';

interface Props {
	showOnClick?: boolean;
	theme?: 'default' | 'bordered';
	isFixed?: boolean;
}

interface DropdownPosition {
	top?: string;
	left?: string;
	right?: string;
	position: 'fixed' | 'absolute';
}

const {
	showOnClick = false,
	theme = 'default',
	isFixed = false,
} = defineProps<Props>();

const dropdown = useTemplateRef<HTMLElement>('dropdown');
const dropdownWillHide = ref(false);
const dropdownHideTimeout = ref<number | null>(null);
const dropdownShown = ref(false);
const dropdownPosition = reactive<DropdownPosition>({
	position: 'absolute',
	top: '100%',
	left: '0',
	right: undefined,
});

const dropdownEvents = computed(() => ({
	mouseleave: startHidingDropdown,
	...(showOnClick ? { click: toggleDropdown } : { mouseenter: showDropdown }),
}));

function toggleDropdown() {
	if (dropdownShown.value)
		hideDropdown();
	else
		showDropdown();
}

function showDropdown() {
	if (dropdownShown.value) {
		dropdownWillHide.value = false;
		return;
	}

	if (isFixed)
		window.addEventListener('scroll', getPosition);

	getPosition();
	dropdownShown.value = true;
}

function startHidingDropdown() {
	dropdownWillHide.value = true;

	if (dropdownHideTimeout.value)
		clearTimeout(dropdownHideTimeout.value);

	dropdownHideTimeout.value = setTimeout(() => {
		if (dropdownWillHide.value)
			hideDropdown();
	}, 500);
}

function hideDropdown() {
	dropdownWillHide.value = false;
	dropdownShown.value = false;
}

function afterTransitionLeaveHandler() {
	if (isFixed)
		window.removeEventListener('scroll', getPosition);
}

function getPosition() {
	const bodyRect = dropdown.value;

	if (!bodyRect) {
		dropdownPosition.position = 'absolute';
		dropdownPosition.top = '100%';
		dropdownPosition.left = '0';
		dropdownPosition.right = undefined;
		return;
	}

	const bodyRectCoords = bodyRect.getBoundingClientRect();
	if (bodyRectCoords.left > window.innerWidth / 2) {
		dropdownPosition.right = isFixed ? `${window.innerWidth - bodyRectCoords.right}px` : '0';
		dropdownPosition.left = undefined;
	}
	else {
		dropdownPosition.left = isFixed ? `${bodyRectCoords.left}px` : '0';
		dropdownPosition.right = undefined;
	}

	if (isFixed) {
		dropdownPosition.position = 'fixed';
		dropdownPosition.top = `${bodyRectCoords.top + bodyRectCoords.height}px`;
	}
	else {
		dropdownPosition.position = 'absolute';
		dropdownPosition.top = '100%';
	}
}

// https://vueuse.org/core/onClickOutside/#onclickoutside
onClickOutside(dropdown, () => hideDropdown());
</script>

<template>
	<div
		ref="dropdown"
		class="dropdown"
		:class="[{ active: dropdownShown }, `dropdown--${theme}`]"
		v-on="dropdownEvents"
	>
		<div class="dropdown__trigger">
			<slot name="trigger" :dropdown-shown />
		</div>

		<Transition name="dropdown" @after-leave="afterTransitionLeaveHandler">
			<div v-show="dropdownShown" class="dropdown__body" :style="dropdownPosition">
				<div class="dropdown__content">
					<slot />
				</div>
			</div>
		</Transition>
	</div>
</template>

<style lang="sass">
.dropdown
	--dropdown-border-width: 2px
	--dropdown-border-style: solid
	--dropdown-border-color: transparent

	position: relative

	&.active
		& .dropdown
			&__icon
				transform: rotate(180deg)

	&__body
		position: absolute
		top: 100%
		z-index: var(--z-index-dropdown)
		width: max-content
		max-width: 280px

	&__content
		padding: 26px
		border: var(--dropdown-border-width) var(--dropdown-border-style) var(--dropdown-border-color)
		border-radius: var(--radius-l)
		background: var(--color-neutral-100)

		@include media('max', 'tablet')
			padding: 16px

	&__icon
		transition: transform 0.3s ease

	&__nav
		display: flex
		flex-direction: column
		gap: 12px

	&__divider
		display: block
		width: 100%
		height: 2px
		background-color: var(--color-neutral-200)

	&--bordered
		--dropdown-border-color: var(--color-primary-500)

.dropdown-enter-active,
.dropdown-leave-active
	transition: opacity 0.3s ease, transform 0.3s ease

.dropdown-enter-from,
.dropdown-leave-to
	opacity: 0
	transform: translateY(-8px)
</style>
