<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import InputTemplate from '@/app/shared/components/templates/InputTemplate.vue';
import { useId } from 'vue';

interface Props {
	id?: string;
	label?: string;
	text?: string;
	readonly?: boolean;
	disabled?: boolean;
	name?: string;
	required?: boolean;
	title?: string;
	errors?: string[] | ErrorObject[];
}

const { id } = defineProps<Props>();

const modelValue = defineModel<boolean>();

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :label :errors :required tag="span">
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<label
			class="input-check input-check--checkbox"
			:title
			:class="[
				{ active: modelValue },
				{ error: errors?.length },
				{ disabled },
				{ readonly },
			]"
		>
			<input
				:id="inputId"
				v-model="modelValue"
				type="checkbox"
				:disabled="disabled || readonly"
				:name
				:readonly
				class="input-check__input"
			>
			<span class="input-check__checkmark input-check__checkmark--checkbox" />
			<span class="input-check__value">
				{{ text }}
			</span>
		</label>

		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>
	</InputTemplate>
</template>
