<script setup lang="ts">
import { FormsActions } from '@app/modules/forms/api/forms.api';
import useFormStore from '@app/modules/forms/store/forms.store';
import InputText from '@app/shared/components/inputs/InputText.vue';
import { phoneMask } from '@app/shared/consts/masks';

import useBaseStore from '@app/shared/stores/base/base.store';
import { getExternalResults } from '@app/shared/utils/getExternalResults';
import { getValidationRules } from '@app/shared/utils/getValidationRules';
import useVuelidate from '@vuelidate/core';
import { computed, reactive, ref } from 'vue';

const initialData = {
	city_id: 0,
	name: '',
	phone: '',
	email: '',
	comment: '',
};
const formData = reactive({ ...initialData });
const $externalResults = ref({});

const rules = computed(() => ({
	name: getValidationRules('string', true),
	phone: getValidationRules('phone', true),
}));
const v$ = useVuelidate(rules, formData, { $autoDirty: true, $externalResults });

const { isActionPending } = useBaseStore();
const isLoading = computed(() => isActionPending(FormsActions.SEND_FEEDBACK));

const { requestSendFeedbackForm } = useFormStore();
async function onSubmit() {
	await v$.value.$validate();
	const validation = await v$.value.$validate();

	if (!validation)
		return;

	const response = await requestSendFeedbackForm(formData);

	if (response.errors?.length) {
		$externalResults.value = getExternalResults(response.errors);
	}
	if (!response.data)
		return;

	v$.value.formData.$reset();
	Object.assign(formData, initialData);
}
</script>

<template>
	<form class="form" :inert="isLoading" @submit.prevent="onSubmit">
		<div class="form__items form__items--column-2">
			<InputText
				id="first-name-input"
				v-model="formData.name"
				:errors="v$.name.$errors"
				:is-password="true"
				autocomplete="given-name"
				class="form__item"
				label="Имя"
				placeholder=""
				required
			/>
			<InputText
				id="phone-input"
				v-model="formData.phone"
				v-mask="phoneMask"
				:errors="v$.phone.$errors"
				autocomplete="tel"
				class="form__item"
				label="Телефон"
				placeholder="+7 (___) ___ __ __"
				required
				type="tel"
			/>
		</div>

		<div class="form__bottom">
			<div class="form__actions">
				<button
					class="form__submit btn"
					type="submit"
					:disabled="v$.$error && v$.$dirty"
					:loading="isLoading"
				>
					<span class="btn__text">
						Отправить
					</span>
				</button>
			</div>
		</div>
	</form>
</template>
