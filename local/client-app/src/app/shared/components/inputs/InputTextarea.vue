<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import InputTemplate from '@/app/shared/components/templates/InputTemplate.vue';
import { useId } from 'vue';

interface Props {
	id?: string;
	label?: string;
	placeholder?: string;
	readonly?: boolean;
	disabled?: boolean;
	required?: boolean;
	title?: string;
	name?: string;
	errors?: string[] | ErrorObject[];
}

const { id } = defineProps<Props>();

const modelValue = defineModel<string>();

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :id="inputId" :label :errors :required>
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<div class="input">
			<textarea
				:id="inputId"
				v-model="modelValue"
				:placeholder
				:disabled
				:readonly
				:title
				:name
				class="input__input input__input--textarea"
				:class="{ error: errors?.length }"
			/>
		</div>

		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>
	</InputTemplate>
</template>
