<script setup lang="ts">
import type { City } from '@app/modules/map/map.types';
import IconSVG from '@/app/shared/components/IconSVG.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import FormFeedback from '@app/modules/forms/components/FormFeedback.vue';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed, ref } from 'vue';

interface FeedbackData {
	cities: City[];
}

const { getModalData } = useModalsStore();
const modalData = computed(() => getModalData<FeedbackData>('modal-feedback'));

const isFinished = ref<boolean>(false);
</script>

<template>
	<teleport defer to="#modals-container">
		<ModalTemplate id="modal-feedback" animation="fade-in-up">
			<template #default="{ close }">
				<section class="modal modal--feedback">
					<div class="modal__wrapper">
						<div class="modal__top">
							<div v-if="isFinished" class="modal__header">
								<div class="modal__title title title--h3">
									Ваша заявка отправлена
								</div>
								<div class="modal__caption">
									Наши менеджеры свяжутся с вами <br> для уточнения деталей
								</div>
							</div>
							<div v-else class="modal__header">
								<div class="modal__title title title--h3">
									Ответим <br> на ваши вопросы
								</div>
								<div class="modal__caption">
									Оставьте заявку. Мы свяжемся с вами в ближайшее время.
								</div>
							</div>
							<button class="modal__close btn btn--color-secondary" data-dialog-close aria-label="закрыть" @click="close">
								<IconSVG class="modal__close-icon" name="close" />
							</button>
						</div>

						<div class="modal__content">
							<FormFeedback v-if="modalData" :cities="modalData.cities" @on-success="isFinished = true" />
						</div>
					</div>
				</section>
			</template>
		</ModalTemplate>
	</teleport>
</template>
