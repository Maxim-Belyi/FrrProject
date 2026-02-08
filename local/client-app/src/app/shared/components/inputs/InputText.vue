<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import InputTemplate from '@/app/shared/components/templates/InputTemplate.vue';
import { useId } from 'vue';

export type AutoComplete =
	'on' |
	'sex' |
	'honorific-prefix' |
	'given-name' |
	'additional-name' |
	'family-name' |
	'nickname' |
	'email' |
	'username' |
	'new-password' |
	'current-password' |
	'one-time-code' |
	'organization-title' |
	'organization' |
	'street-address' |
	'address-level4' |
	'address-level3' |
	'address-level2' |
	'address-level1' |
	'country' |
	'country-name' |
	'postal-code' |
	'cc-name' |
	'cc-given-name' |
	'cc-additional-name' |
	'cc-family-name' |
	'cc-number' |
	'cc-exp' |
	'cc-exp-month' |
	'cc-exp-year' |
	'cc-csc' |
	'cc-type' |
	'transaction-amount' |
	'tel' |
	'tel-local' |
	'url' |
	'off'
;

interface Props {
	id?: string;
	label?: string;
	placeholder?: string;
	readonly?: boolean;
	disabled?: boolean;
	required?: boolean;
	loading?: boolean;
	unmaskAsNumber?: boolean;
	autocomplete?: AutoComplete;
	title?: string;
	name?: string;
	type?: 'text' | 'email' | 'tel' | 'numeric';
	errors?: string[] | ErrorObject[];
}

const {
	id,
	unmaskAsNumber,
	type = 'text',
	autocomplete = 'off',
} = defineProps<Props>();

const modelValue = defineModel<string | number>({
	set: v => unmaskAsNumber ? Number.parseFloat(String(v)) : v,
});

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :id="inputId" :label :errors :required :loading>
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<div class="input">
			<input
				:id="inputId"
				v-model="modelValue"
				:placeholder
				:disabled
				:readonly
				:inputmode="type"
				:autocomplete
				:title
				:name
				:type
				class="input__input input__input--text"
				:class="[{ error: errors?.length }]"
			>
		</div>
		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>
		<template v-if="$slots.underInput" #underInput>
			<slot name="underInput" />
		</template>
	</InputTemplate>
</template>
