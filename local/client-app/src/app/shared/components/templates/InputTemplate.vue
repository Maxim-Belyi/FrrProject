//TODO: сделать, чтобы не появлялся блок с ошибками при ошибке, если нет текста ошибки
<script setup lang="ts">
import type { ErrorObject } from '@vuelidate/core';

interface Props {
	label?: string;
	tag?: 'label' | 'span';
	id?: string;
	errors?: string[] | ErrorObject[];
	required?: boolean;
	loading?: boolean;
}

const {
	tag = 'label',
	errors = [],
} = defineProps<Props>();
</script>

<template>
	<div class="default-input" :class="{ required, loading }">
		<Component :is="tag" v-if="label" :for="id" class="default-input__label">
			<slot name="label" :label-value="label">
				<span class="default-input__label-text">{{ label }}</span>
			</slot>
		</Component>
		<div class="default-input__input">
			<slot />
			<slot name="action" />
		</div>
		<ul v-if="errors.length" class="default-input__errors">
			<li class="default-input__error">
				{{ typeof errors[0] === 'string' ? errors[0] : errors[0].$message }}
			</li>
		</ul>
		<div v-if="$slots.underInput" class="default-input__bottom">
			<slot name="underInput" />
		</div>
	</div>
</template>
