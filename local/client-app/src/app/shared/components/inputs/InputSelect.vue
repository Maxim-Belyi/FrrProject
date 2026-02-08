<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';
import SelectTemplate from '@app/shared/components/templates/SelectTemplate.vue';
import Multiselect from '@vueform/multiselect';
import { useId } from 'vue';

type MultiselectProps = InstanceType<typeof Multiselect>['$props'];

interface Props {
	id?: string;
	label?: string;
	errors?: string[] | ErrorObject[];
	required?: boolean;
	readonly?: boolean;
	placeholder?: MultiselectProps['placeholder'];
	options?: MultiselectProps['options'];
	mode?: MultiselectProps['mode'];
	groups?: MultiselectProps['groups'];
	searchable?: MultiselectProps['searchable'];
	disabled?: MultiselectProps['disabled'];
	loadingSelect?: MultiselectProps['loading'];
	loading?: boolean;
	labelProp?: MultiselectProps['label'];
	valueProp?: MultiselectProps['valueProp'];
	canClear?: MultiselectProps['canClear'];
	canDeselect?: MultiselectProps['canDeselect'];
	delay?: MultiselectProps['delay'];
	filterResults?: MultiselectProps['filterResults'];
	resolveOnLoad?: MultiselectProps['resolveOnLoad'];
}

const {
	id,
	searchable = false,
	loadingSelect = false,
	mode = 'single',
	groups = false,
} = defineProps<Props>();

const emit = defineEmits(['change']);

const modelValue = defineModel<number | string | string[]>();

const inputId = id ?? useId();
</script>

<template>
	<SelectTemplate :id="inputId" :label :errors :required :loading>
		<Multiselect
			:id="inputId"
			v-model="modelValue"
			class="input-select"
			:class="[{ 'is-error': errors?.length }, { 'is-readonly': readonly }]"
			:placeholder
			:options
			:mode
			:groups
			:hide-selected="false"
			:caret="!loadingSelect"
			:searchable
			:disabled="disabled || readonly"
			:loading="loadingSelect"
			:label="labelProp"
			:value-prop="valueProp"
			:can-clear
			:can-deselect
			:delay
			:filter-results
			:resolve-on-load
			no-results-text="Ничего не найдено"
			no-options-text="Нет вариантов для выбора"
			@select="emit('change')"
			@deselect="emit('change')"
		/>
	</SelectTemplate>
</template>
