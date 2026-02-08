<script setup lang="ts">
import type { City } from '@app/modules/map/map.types';
import FormFeedbackFAQ from '@app/modules/forms/components/FormFeedbackFAQ.vue';
import { ref } from 'vue';

interface Props {
	cities: City[];
}

defineProps<Props>();

const isFinished = ref<boolean>(false);
</script>

<template>
	<div class="faq-form">
		<div v-if="isFinished" class="faq-form__info">
			<div class="faq-form__title">
				Ваша заявка отправлена
			</div>
			<div class="faq-form__caption">
				Наши менеджеры свяжутся с вами <br> для уточнения деталей
			</div>
		</div>
		<div v-else class="faq-form__info">
			<div class="faq-form__title">
				Ответим <br> на ваши вопросы
			</div>
			<div class="faq-form__caption">
				Оставьте заявку. Мы свяжемся с вами в ближайшее время.
			</div>
		</div>
		<div class="faq-form__form">
			<FormFeedbackFAQ :cities @on-success="isFinished = true" />
		</div>
	</div>
</template>
