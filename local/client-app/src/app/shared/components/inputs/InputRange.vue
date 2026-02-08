<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import type { MaskOptions } from 'maska';
import InputTemplate from '@app/shared/components/templates/InputTemplate.vue';
import { integerMask } from '@app/shared/consts/masks';
import Slider from '@vueform/slider';
import { useId } from 'vue';

type Position = 'min' | 'max';

interface FormatTooltipsObject {
	suffix?: string;
	decimals?: number;
	thousands?: string;
}

export interface Props {
	min?: number;
	max?: number;
	step?: number;
	tooltips?: boolean;
	tooltipMerge?: number;
	tooltipFormat?: ((value: number) => string | number) | FormatTooltipsObject;
	tooltipPosition?: 'top' | 'bottom' | 'left' | 'right';
	id?: string;
	label?: string;
	readonly?: boolean;
	disabled?: boolean;
	required?: boolean;
	title?: string;
	name?: string;
	suffix?: string;
	mask?: MaskOptions;
	errors?: string[] | ErrorObject[];
}

const {
	id,
	min = 0,
	max = 100,
	step = 1,
	tooltipMerge = -1,
	tooltipPosition = 'bottom',
	suffix = '',
	mask = integerMask,
	errors = [],
} = defineProps<Props>();

const modelValue = defineModel<number | [number, number]>();

function calculateValue(value: number, position?: Position) {
	if (value > max)
		return max;
	if (value < min)
		return min;
	if (Array.isArray(modelValue.value) && position === 'min' && value > modelValue.value[1])
		return modelValue.value[1];
	return value;
}

function onChangeValue(position: Position = 'min', event: Event) {
	const value = +(event.target as HTMLInputElement).value.replace(/\D/g, '');

	if (Array.isArray(modelValue.value)) {
		if (position === 'min')
			modelValue.value = [calculateValue(value, 'min'), modelValue.value[1]];
		else
			modelValue.value = [modelValue.value[0], calculateValue(value)];
	}
	else {
		modelValue.value = value;
	}
}

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :id="inputId" :label :errors :required tag="span">
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<div
			class="input-range"
			:title
			:class="[{ error: errors.length }, { disabled }, { readonly }]"
		>
			<div class="input-range__wrapper">
				<template v-if="Array.isArray(modelValue)">
					<label class="input-range__input-wrapper">
						<input
							:id="`${inputId}-1`"
							v-mask="mask"
							:value="modelValue[0]"
							:disabled
							:readonly
							inputmode="numeric"
							:name
							type="text"
							class="input-range__input input-range__input--left"
							@change="onChangeValue('min', $event)"
						>
						<span v-if="suffix" class="input-range__suffix">{{ suffix }}</span>
					</label>
					<label class="input-range__input-wrapper">
						<input
							:id="`${inputId}-2`"
							v-mask="mask"
							:value="modelValue[1]"
							:disabled
							:readonly
							inputmode="numeric"
							:name
							type="text"
							class="input-range__input input-range__input--right"
							@change="onChangeValue('max', $event)"
						>
						<span v-if="suffix" class="input-range__suffix">{{ suffix }}</span>
					</label>
				</template>
				<label v-else class="input-range__input-wrapper">
					<input
						:id="inputId"
						v-mask="mask"
						:value="modelValue"
						:disabled
						:readonly
						inputmode="numeric"
						:name
						type="text"
						class="input-range__input input-range__input--single"
					>
					<span v-if="suffix" class="input-range__suffix">{{ suffix }}</span>
				</label>
			</div>
			<Slider
				v-model="modelValue"
				:min="min"
				:max="max"
				:tooltips="disabled ? false : tooltips"
				:step="step"
				:format="tooltipFormat"
				:merge="tooltipMerge"
				:tooltip-position="tooltipPosition"
				:disabled="disabled || readonly"
				class="input-range__slider"
			/>
		</div>

		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>
	</InputTemplate>
</template>

<style lang="sass">
@import '@vueform/slider/themes/default.css'

.input-range
	position: relative
	display: flex
	flex: 1 1 auto
	flex-direction: column
	gap: var(--gap-xs)

	&:focus-within
		& .input-range
			&__wrapper
				border-color: var(--di-border-color-active)

				@include media('max', 'mobile-xl')
					font-size: 16px

	@include hover
		& .input-range
			&__wrapper
				border-color: var(--di-border-color-hover)

	&.disabled
		& .input-range
			&__wrapper
				border-color: var(--di-border-color-disabled)
				background-color: var(--di-bg-disabled)
				color: var(--di-color-disabled)
				cursor: not-allowed
				pointer-events: none

			&__suffix,
			&__prefix
				color: var(--di-color-disabled)

			&__slider
				--slider-handle-border: 6px solid var(--di-border-color-disabled)

	&.readonly:not(.disabled)
		& .input-range
			&__wrapper
				cursor: help

			&__slider
				--slider-handle-border: 6px solid var(--di-border-color-disabled)
				--slider-connect-bg-disabled: var(--color-primary-500)

	&.error
		& .input-range
			&__wrapper
				border-color: var(--di-border-color-error)
				color: var(--di-color-error)

	&__wrapper
		display: flex
		align-items: center
		justify-content: stretch
		gap: var(--gap-xs)
		width: 100%
		min-height: var(--di-min-height)

	&__input-wrapper
		display: flex
		flex: 1 1 auto
		align-items: center
		justify-content: space-between
		gap: calc(var(--di-px) / 2)

		padding: var(--di-py) var(--di-px)

		border-width: var(--di-border-width)
		border-style: solid
		border-radius: var(--di-border-radius)
		border-color: var(--di-border-color)

		background-color: var(--color-neutral-100)

		color: var(--di-color)

		font-size: var(--di-font-size)
		line-height: var(--di-line-height)

		transition: border-color 0.3s ease, background-color 0.3s ease

		cursor: inherit

	&__input
		width: 100%
		min-width: 30px
		max-width: 75px
		margin: 0
		padding: 0

		overflow: hidden

		border: none
		border-radius: 0
		outline: none

		background-color: transparent

		text-overflow: ellipsis
		white-space: nowrap

		cursor: inherit

		&--single
			max-width: 125px

	&__prefix
		flex: 1 0 25%

		max-width: max-content

		overflow: hidden

		color: currentcolor

		font-size: var(--di-font-size)
		line-height: var(--di-line-height)

		text-overflow: ellipsis
		white-space: nowrap

	&__suffix
		flex-shrink: 0
		color: currentcolor
		font-size: var(--di-font-size)
		line-height: var(--di-line-height)
		white-space: nowrap

	&__slider
		--slider-bg: var(--color-neutral-300)
		--slider-connect-bg: var(--color-primary-500)
		--slider-connect-bg-disabled: var(--di-border-color-disabled)
		--slider-height: 4px
		--slider-vertical-height: 300px
		--slider-radius: var(--radius-xl)
		--slider-handle-bg: var(--color-primary-600)
		--slider-handle-border: 4px solid var(--color-primary-500)
		--slider-handle-width: 20px
		--slider-handle-height: 20px
		--slider-handle-radius: 50%
		--slider-handle-shadow: none
		--slider-handle-shadow-active: none
		--slider-handle-ring-width: 0
		--slider-handle-ring-color: transparent
		--slider-tooltip-font-size: var(--font-size-text-s)
		--slider-tooltip-line-height: var(--line-height-s)
		--slider-tooltip-font-weight: 400
		--slider-tooltip-min-width: 20px
		--slider-tooltip-bg: transparent
		--slider-tooltip-bg-disabled: var(--di-border-color-disabled)
		--slider-tooltip-color: var(--color-neutral-500)
		--slider-tooltip-radius: var(--radius-m)
		--slider-tooltip-py: 2px
		--slider-tooltip-px: 6px
		--slider-tooltip-arrow-size: 6px
		--slider-tooltip-distance: -10px

		margin: var(--gap-xs) 0
</style>
