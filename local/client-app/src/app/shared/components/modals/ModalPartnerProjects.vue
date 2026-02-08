<script setup lang="ts">
import type { PartnerPayload } from '@app/modules/partners/api/partners.api.types';
import IconSVG from '@/app/shared/components/IconSVG.vue';
import ModalTemplate from '@/app/shared/components/templates/ModalTemplate.vue';
import PartnersProjects from '@app/modules/partners/components/PartnersProjects.vue';
import useModalsStore from '@app/shared/stores/modals/modals.store';
import { computed } from 'vue';

const { getModalData } = useModalsStore();

const modalData = computed(() => getModalData<PartnerPayload>('modal-partner-projects'));
</script>

<template>
	<teleport defer to="#modals-container">
		<ModalTemplate id="modal-partner-projects" animation="fade-in-right">
			<template #default="{ close }">
				<section class="modal modal--side">
					<button class="modal__close btn btn--color-tertiary btn--inverse" data-dialog-close aria-label="закрыть" @click="close">
						<IconSVG class="modal__close-icon" name="close" />
					</button>
					<div class="modal__wrapper">
						<div class="modal__content">
							<PartnersProjects v-if="modalData" :data="modalData" />
						</div>
					</div>
				</section>
			</template>
		</ModalTemplate>
	</teleport>
</template>
