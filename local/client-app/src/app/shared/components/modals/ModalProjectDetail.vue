<script setup lang="ts">
import IconSVG from '@/app/shared/components/IconSVG.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import ProjectView from '@app/modules/project/components/ProjectView.vue';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed } from 'vue';

const { getModalData } = useModalsStore();
const modalData = computed(() => getModalData<number>('modal-project-detail'));
</script>

<template>
	<teleport to="#modals-container">
		<ModalTemplate id="modal-project-detail" animation="fade-in-right">
			<template #default="{ close }">
				<section class="modal modal--side">
					<button class="modal__close btn btn--color-tertiary" data-dialog-close aria-label="закрыть" @click="close">
						<IconSVG class="modal__close-icon" name="close" />
					</button>
					<div class="modal__wrapper">
						<ProjectView v-if="modalData" :id="modalData" />
					</div>
				</section>
			</template>
		</ModalTemplate>
	</teleport>
</template>
