<script setup lang="ts">
import { reactive, ref } from 'vue';

interface AccordionStyles {
	scrollHeight: string;
	transitionDuration: string;
}

const accordionDropdown = ref<HTMLElement>();
const isOpen = ref(false);
const accordionStyles = reactive<AccordionStyles>({
	scrollHeight: '0px',
	transitionDuration: '0.3s',
});

function onToggle() {
	if (isOpen.value && accordionDropdown.value)
		calculateSizes(accordionDropdown.value);
	isOpen.value = !isOpen.value;
}

function calculateSizes(el: Element): void {
	accordionStyles.scrollHeight = `${el.scrollHeight}px`;
	accordionStyles.transitionDuration = `${Math.max(0.3, Math.min(1.5, el.scrollHeight / 1000))}s`;
}
</script>

<template>
	<div :class="{ active: isOpen }">
		<slot :is-open="isOpen" :on-toggle="onToggle" />
		<Transition name="accordion" @enter="calculateSizes">
			<div
				v-show="isOpen"
				ref="accordionDropdown"
				:style="`--accordion-dropdown-height: ${accordionStyles.scrollHeight}; --accordion-dropdown-transition-duration: ${accordionStyles.transitionDuration}`"
			>
				<slot name="body" />
			</div>
		</Transition>
	</div>
</template>

<style scoped lang="sass">
.accordion-enter-active,
.accordion-leave-active
	overflow: hidden
	transition: height var(--accordion-dropdown-transition-duration) ease

.accordion-enter-to,
.accordion-leave-from
	height: var(--accordion-dropdown-height)

.accordion-enter-from,
.accordion-leave-to
	height: 0
</style>
