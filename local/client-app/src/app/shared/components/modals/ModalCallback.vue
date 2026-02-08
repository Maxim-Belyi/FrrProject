<script setup lang="ts">
import IconSVG from '@/app/shared/components/IconSVG.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import FormFeedback from '@app/modules/forms/components/FormFeedback.vue';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed } from 'vue';

interface TestData {
	text: string;
}

const { getModalData } = useModalsStore();
const modalData = computed(() => getModalData<TestData>('modal-form'));
</script>

<template>
	<teleport to="#modals-container">
		<ModalTemplate id="modal-form" animation="fade-in-right">
			<template #default="{ close }">
				<section class="modal">
					<button class="modal__close" data-dialog-close aria-label="закрыть" @click="close">
						<IconSVG class="modal__close-icon" name="close" />
					</button>
					<div class="modal__wrapper">
						<h2 class="modal__title title title--h4">
							Заказать звонок
						</h2>
						<p v-if="modalData" class="modal__text">
							{{ modalData.text }}
						</p>
						<div class="modal__content">
							<FormFeedback :cities="[]" />
						</div>
					</div>
				</section>
			</template>
		</ModalTemplate>
	</teleport>
</template>
