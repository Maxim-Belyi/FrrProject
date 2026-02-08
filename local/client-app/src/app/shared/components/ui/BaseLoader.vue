<script setup lang="ts">
interface Props {
	colorScheme?: 'default' | 'dark';
	position?: 'fixed' | 'static';
}

const {
	colorScheme = 'default',
	position = 'fixed',
} = defineProps<Props>();
</script>

<template>
	<div class="loader" :class="`loader--${colorScheme} loader--${position}`" />
</template>

<style scoped lang="sass">
.loader
	position: fixed
	top: 0
	left: 0
	z-index: var(--z-index-modal)

	display: flex
	align-items: center
	justify-content: center

	width: 100%
	height: 100%

	&::before
		content: ''
		position: absolute
		top: 0
		left: 0
		width: 100%
		height: 100%
		background: rgba(var(--color-neutral-900-trans), 0.5)

	&::after
		content: ''
		size: fluid(124, 48)
		border-width: 4px
		border-style: solid
		border-radius: 50%
		animation: spin360 0.7s linear infinite

		@include media('max', 'laptop')
			border-width: 2px

	&--default
		&::after
			border-color: var(--color-primary-500) var(--color-primary-500) var(--color-neutral-300)

	&--dark
		&::after
			border-color: var(--color-neutral-100) var(--color-neutral-100) var(--color-primary-400)

	&--static
		position: static

		&::before
			content: none

		&::after
			size: fluid(120, 40)
</style>
