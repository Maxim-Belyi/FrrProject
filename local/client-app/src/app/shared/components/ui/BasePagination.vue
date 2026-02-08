<script setup lang="ts">
import IconSVG from '@/app/shared/components/IconSVG.vue';
import { computed, ref } from 'vue';

interface Props {
	total?: number;
	limit?: number;
	maxButtons?: number;
	hideButtons?: boolean;
	hideBreakpoints?: boolean;
	theme?: 'default' | 'light';
}

const {
	total = 0,
	limit = 10,
	maxButtons = 5,
	hideButtons = false,
	hideBreakpoints = false,
	theme = 'default',
} = defineProps<Props>();

const emit = defineEmits(['change']);

const innerValue = ref(1);
const modelValue = defineModel<number>({
	default: 1,
	get(v) {
		return v ?? innerValue.value;
	},
	set(value: number) {
		innerValue.value = value;
		emit('change', value);
		return value;
	},
});

const pagesArr = computed(() => {
	return Array.from({ length: Math.ceil(total / limit) }, (_, i) => i + 1);
});

const middlePages = computed(() => {
	const middle = Math.ceil(maxButtons / 2);
	const start = modelValue.value - middle + 1;
	const end = modelValue.value + middle - 1;

	if (start <= 0)
		return pagesArr.value.slice(0, maxButtons);

	if (end >= pagesArr.value.length)
		return pagesArr.value.slice(-maxButtons);

	return pagesArr.value.slice(start - 1, end);
});

const lastPage = computed(() => {
	return pagesArr.value[pagesArr.value.length - 1];
});

const shouldShowStartPage = computed(() => {
	return !hideBreakpoints && middlePages.value[0] > 1;
});

const shouldShowEndPage = computed(() => {
	return !hideBreakpoints && middlePages.value[middlePages.value.length - 1] < lastPage.value;
});

const shouldShowLeftBreakpoint = computed(() => {
	return !hideBreakpoints && middlePages.value[0] > 2;
});

const shouldShowRightBreakpoint = computed(() => {
	return !hideBreakpoints && middlePages.value[middlePages.value.length - 1] < lastPage.value - 1;
});

function setPage(page: number): void {
	if (page < 1 || page > pagesArr.value.length)
		return;
	modelValue.value = page;
}

function setBreakpointPage(direction: 'left' | 'right' = 'right'): void {
	if (direction === 'left')
		setPage(middlePages.value[0] - 1);
	else
		setPage(middlePages.value[middlePages.value.length - 1] + 1);
}
</script>

<template>
	<slot>
		<ul class="pagination">
			<li v-if="!hideButtons" class="pagination__item">
				<button class="pagination-button pagination-button--type-navigation" :class="[`pagination-button--theme-${theme}`]" :disabled="modelValue === 1" @click="setPage(modelValue - 1)">
					<IconSVG class="pagination-button__icon" name="arrow-left" />
				</button>
			</li>
			<li v-if="shouldShowStartPage" class="pagination__item">
				<button class="pagination-button pagination-button--type-number" :class="[`pagination-button--theme-${theme}`]" @click="setPage(1)">
					1
				</button>
			</li>
			<li v-if="shouldShowLeftBreakpoint" class="pagination__item">
				<button class="pagination-button pagination-button--type-breakpoint" :class="[`pagination-button--theme-${theme}`]" @click="setBreakpointPage('left')">
					...
				</button>
			</li>
			<li v-for="(page, index) in middlePages" :key="index" class="pagination__item">
				<button class="pagination-button pagination-button--type-number" :class="[`pagination-button--theme-${theme}`, { active: modelValue === page }]" @click="setPage(page)">
					{{ page }}
				</button>
			</li>
			<li v-if="shouldShowRightBreakpoint" class="pagination__item">
				<button class="pagination-button pagination-button--type-breakpoint" :class="[`pagination-button--theme-${theme}`]" @click="setBreakpointPage('right')">
					...
				</button>
			</li>
			<li v-if="shouldShowEndPage" class="pagination__item">
				<button class="pagination-button pagination-button--type-number" :class="[`pagination-button--theme-${theme}`]" @click="setPage(lastPage)">
					{{ lastPage }}
				</button>
			</li>
			<li v-if="!hideButtons" class="pagination__item">
				<button class="pagination-button pagination-button--type-navigation" :class="[`pagination-button--theme-${theme}`]" :disabled="modelValue === lastPage" @click="setPage(modelValue + 1)">
					<IconSVG class="pagination-button__icon" name="arrow-right" />
				</button>
			</li>
		</ul>
	</slot>
</template>

<style lang="sass">
.pagination
	display: flex
	align-items: center
	justify-content: center
	gap: 4px

.pagination-button
	--pagination-button-size: 44px
	--pagination-button-px: 4px
	--pagination-button-py: 4px
	--pagination-button-gap: 8px
	// -----------------
	--pagination-font-family: var(--font-family-primary)
	--pagination-font-size: var(--font-size-text-m)
	--pagination-line-height: 20px
	--pagination-letter-spacing: var(--letter-spacing-s)
	--pagination-font-weight: 500
	--pagination-text-transform: none
	// -----------------
	--pagination-color: inherit
	--pagination-color-hover: inherit
	--pagination-color-disabled: inherit
	--pagination-color-active: inherit
	--pagination-color-active-hover: inherit
	--pagination-color-active-disabled: inherit
	// -----------------
	--pagination-bg: transparent
	--pagination-bg-hover: transparent
	--pagination-bg-disabled: transparent
	--pagination-bg-active: transparent
	--pagination-bg-active-hover: transparent
	--pagination-bg-active-disabled: transparent
	// -----------------
	--pagination-border-size: 0
	--pagination-border-color: transparent
	--pagination-border-color-hover: transparent
	--pagination-border-color-disabled: transparent
	--pagination-border-color-active: transparent
	--pagination-border-color-active-hover: transparent
	--pagination-border-color-active-disabled: transparent
	// -----------------
	--pagination-outline-size: 2px
	--pagination-outline-color: var(--color-warning)
	// -----------------
	--pagination-radius: 50%
	// -----------------
	--pagination-icon-size: 20px
	--btn-corners-size: 6px
	--btn-corners-color: var(--color-primary-500)
	--calc-corners-size: calc(100% - var(--btn-corners-size))
	position: relative

	display: inline-flex
	align-items: center
	justify-content: center
	gap: var(--pagination-button-gap)

	min-width: var(--pagination-button-size)
	min-height: var(--pagination-button-size)
	padding: var(--pagination-button-py) var(--pagination-button-px)

	border: var(--pagination-border-size) solid var(--pagination-border-color)
	border-radius: var(--pagination-radius)

	background-color: var(--pagination-bg)

	color: var(--pagination-color)

	font-family: var(--pagination-font-family)
	font-size: var(--pagination-font-size)
	font-weight: var(--pagination-font-weight)
	letter-spacing: var(--pagination-letter-spacing)
	line-height: var(--pagination-line-height)

	text-align: center
	text-decoration: none

	text-transform: var(--pagination-text-transform)

	transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease

	cursor: pointer

	&::before
		content: ''
		position: absolute
		inset: var(--pagination-button-py) var(--pagination-button-px)
		z-index: 10

		width: calc(100% - var(--pagination-button-px) * 2)
		height: calc(100% - var(--pagination-button-py) * 2)

		border: 1px solid var(--btn-corners-color)

		opacity: 0

		transition: opacity 0.3s, inset 0.5s, width 0.5s, height 0.5s
		clip-path: polygon(0 0, var(--btn-corners-size) 0, var(--btn-corners-size) 2px, var(--calc-corners-size) 2px, var(--calc-corners-size) 0, 100% 0, 100% var(--btn-corners-size), calc(100% - 2px) var(--btn-corners-size), calc(100% - 2px) var(--calc-corners-size), 100% var(--calc-corners-size), 100% 100%, var(--calc-corners-size) 100%, var(--calc-corners-size) calc(100% - 2px), var(--btn-corners-size) calc(100% - 2px), var(--btn-corners-size) 100%, 0 100%, 0 var(--calc-corners-size), 2px var(--calc-corners-size), 2px var(--btn-corners-size), 0 var(--btn-corners-size))

	&:focus
		outline: none

	&:focus-visible
		outline: var(--pagination-outline-color) solid var(--pagination-outline-size)
		outline-offset: 2px

	@include hover
		border-color: var(--pagination-border-color-hover)
		background-color: var(--pagination-bg-hover)
		color: var(--pagination-color-hover)

		&::before
			inset: -1px
			width: calc(100% + 2px)
			height: calc(100% + 2px)
			opacity: 1

	&:active,
	&.active
		border-color: var(--pagination-border-color-active)
		background-color: var(--pagination-bg-active)
		color: var(--pagination-color-active)

		@include hover
			border-color: var(--pagination-border-color-active-hover)
			background-color: var(--pagination-bg-active-hover)
			color: var(--pagination-color-active-hover)

		&.disabled,
		&:disabled
			border-color: var(--pagination-border-color-active-disabled)
			background-color: var(--pagination-bg-active-disabled)
			color: var(--pagination-color-active-disabled)

	&:disabled,
	&.disabled
		border-color: var(--pagination-border-color-disabled)
		background-color: var(--pagination-bg-disabled)
		color: var(--pagination-color-disabled)
		pointer-events: none

	&__icon
		display: block
		flex: none
		size: var(--pagination-icon-size)
		color: currentcolor

	&--theme
		&-default
			--pagination-color: var(--color-neutral-900)
			--pagination-color-hover: var(--color-neutral-900)
			--pagination-color-disabled: var(--color-neutral-500)
			--pagination-color-active: var(--color-neutral-100)
			--pagination-color-active-hover: var(--color-neutral-100)
			--pagination-color-active-disabled: var(--color-neutral-100)
			// -----------------
			--pagination-bg: var(--color-neutral-300)
			--pagination-bg-hover: var(--color-neutral-400)
			--pagination-bg-disabled: transparent
			--pagination-bg-active: var(--color-primary-500)
			--pagination-bg-active-hover: var(--color-primary-400)
			--pagination-bg-active-disabled: var(--color-neutral-300)

		&-light
			--pagination-color: var(--color-neutral-100)
			--pagination-color-hover: var(--color-neutral-100)
			--pagination-color-disabled: rgba(var(--color-neutral-900-trans), 0.5)
			--pagination-color-active: var(--color-primary-500)
			--pagination-color-active-hover: var(--color-primary-500)
			--pagination-color-active-disabled: var(--color-primary-500)
			// -----------------
			--pagination-bg: transparent
			--pagination-bg-hover: rgba(var(--color-neutral-100-trans), 0.1)
			--pagination-bg-disabled: transparent
			--pagination-bg-active: var(--color-neutral-100)
			--pagination-bg-active-hover: rgba(var(--color-neutral-900-trans), 0.8)
			--pagination-bg-active-disabled: rgba(var(--color-neutral-900-trans), 0.3)

	&--type
		&-navigation
			--pagination-bg: transparent
			--pagination-bg-disabled: var(--color-neutral-300)
</style>
