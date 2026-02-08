<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import InputTemplate from '@/app/shared/components/templates/InputTemplate.vue';
import IconSVG from '@app/shared/components/IconSVG.vue';
import { ref, useId } from 'vue';

export type PassAutoComplete =
	'on' |
	'new-password' |
	'current-password' |
	'off'
;

interface Props {
	id?: string;
	label?: string;
	placeholder?: string;
	readonly?: boolean;
	disabled?: boolean;
	required?: boolean;
	autocomplete?: PassAutoComplete;
	title?: string;
	name?: string;
	errors?: string[] | ErrorObject[];
}

const {
	id,
	autocomplete = 'current-password',
} = defineProps<Props>();

const modelValue = defineModel<string | number>();

const passShown = ref(false);

function togglePassShown(): void {
	passShown.value = !passShown.value;
}

const inputId = id ?? useId();
</script>

<template>
	<InputTemplate :id="inputId" :label :errors :required>
		<template v-if="$slots.label" #label="{ labelValue }">
			<slot name="label" :label-value />
		</template>

		<div class="input">
			<input
				:id="inputId"
				v-model="modelValue"
				:placeholder
				:disabled="disabled || readonly"
				:readonly
				:autocomplete
				:title
				:name
				:type="passShown ? 'text' : 'password'"
				class="input__input input__input--password"
				:class="[
					{ error: errors?.length },
				]"
			>
			<span aria-hidden="true" tabindex="0" role="button" class="input__icon input__icon--pass" :class="{ active: passShown }" @click="togglePassShown" @keydown.enter="togglePassShown">
				<IconSVG class="input__icon-image" :name="passShown ? 'pass-hide' : 'pass-show'" />
			</span>
		</div>

		<template v-if="$slots.action" #action>
			<slot name="action" />
		</template>

		<template v-if="$slots.underInput" #underInput>
			<slot name="underInput" />
		</template>
	</InputTemplate>
</template>
