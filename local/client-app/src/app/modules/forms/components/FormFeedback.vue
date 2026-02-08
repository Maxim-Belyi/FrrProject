<script setup lang="ts">
import type { City } from '@app/modules/map/map.types';
import useFormStore from '@/app/modules/forms/store/forms.store';
import { FormsActions } from '@app/modules/forms/api/forms.api';
import InputSelect from '@app/shared/components/inputs/InputSelect.vue';
import InputText from '@app/shared/components/inputs/InputText.vue';
import InputTextarea from '@app/shared/components/inputs/InputTextarea.vue';
import ImgTemplate from '@app/shared/components/templates/ImgTemplate.vue';
import useBaseStore from '@app/shared/stores/base/base.store';
import { getValidationRules } from '@app/shared/utils/getValidationRules';
import useVuelidate from '@vuelidate/core';
import { storeToRefs } from 'pinia';
import { computed, reactive, ref } from 'vue';

interface Props {
	cities: City[];
}

defineProps<Props>();
const emit = defineEmits(['onSuccess']);

const { docs } = storeToRefs(useBaseStore());

const externalResults = reactive({});
const { requestSendFeedbackForm } = useFormStore();
const isFinished = ref(false);
const initialData = {
	city_id: undefined,
	name: '',
	email: '',
	comment: '',
	page: window.location.pathname,
};
const formData = reactive({ ...initialData });

const rules = computed(() => ({
	formData: {
		city_id: getValidationRules(),
		name: getValidationRules('string', true),
		email: getValidationRules('email', true),
		comment: getValidationRules('text', true),
	},
}));

const v$ = useVuelidate(rules, { formData, $externalResults: externalResults });

const { isActionPending } = useBaseStore();
const isLoading = computed(() => isActionPending(FormsActions.SEND_FEEDBACK));
const { BASE_URL } = import.meta.env;

async function onSubmit() {
	await v$.value.$validate();
	const validation = await v$.value.$validate();

	if (!validation)
		return;

	const response = await requestSendFeedbackForm(formData, 'contacts');

	if (!response.success)
		return;

	emit('onSuccess');
	v$.value.formData.$reset();
	Object.assign(formData, initialData);
	isFinished.value = true;
}
</script>

<template>
	<form v-show="!isFinished" class="form" :inert="isLoading" @submit.prevent="onSubmit">
		<div class="form__items form__items--column-2">
			<InputSelect
				id="city"
				v-model="formData.city_id"
				:options="cities"
				label-prop="name"
				value-prop="id"
				class="form__item form__item--full"
				label="Город"
				placeholder="Выбрать"
				:errors="v$.formData.city_id.$errors"
			/>
			<InputText
				id="first-name-input"
				v-model="formData.name"
				:errors="v$.formData.name.$errors"
				autocomplete="given-name"
				class="form__item"
				label="Ваше имя"
				placeholder="Укажите ваше имя"
				type="text"
			/>
			<InputText
				id="email-input"
				v-model="formData.email"
				:errors="v$.formData.email.$errors"
				autocomplete="email"
				class="form__item"
				label="E-mail"
				placeholder="Укажите ваш e-mail"
				type="email"
			/>
			<InputTextarea
				id="desc-input"
				v-model="formData.comment"
				:errors="v$.formData.comment.$errors"
				class="form__item form__item--full"
				label="Предложение по улучшению"
				placeholder="Ваше предложение по улучшению"
			/>
		</div>

		<div class="form__bottom">
			<div class="form__actions">
				<button
					class="form__submit btn btn--color-primary"
					type="submit"
					:loading="isLoading"
				>
					<span class="btn__text">
						Отправить заявку
					</span>
				</button>
			</div>
			<div class="form__agree">
				Нажимая кнопку “Отправить заявку”, Вы даете информированное <a target="_blank" :href="docs.userAgreement">согласие на обработку своих персональных данных</a>
			</div>
		</div>
	</form>

	<div v-show="isFinished" class="form-send">
		<div class="form-send__info">
			<ImgTemplate class-name="form-send__bg" :src="`${BASE_URL}img/success.svg`" />
		</div>
	</div>
</template>
