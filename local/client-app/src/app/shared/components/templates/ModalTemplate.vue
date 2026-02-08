<script setup lang="ts">
import useModalsStore from '@/app/shared/stores/modals/modals.store';
import { computed, onMounted, onUnmounted, useTemplateRef } from 'vue';

type Animations = 'fade-in-up' | 'fade-in-right';

interface Props {
	id: string;
	className?: string;
	animation?: Animations;
}

const { id, animation = 'fade-in-up' } = defineProps<Props>();
const { closeModal, registerModal, unregisterModal, isModalOpened } = useModalsStore();

const modal = useTemplateRef<HTMLDialogElement>('modal');
const modalShown = computed(() => isModalOpened(id));

onMounted(() => {
	registerModal(id);
});

onUnmounted(() => {
	unregisterModal(id);
});

function hideModal() {
	closeModal(id);
}
</script>

<template>
	<teleport to="#modals-container">
		<Transition :name="`modal-${animation}`" :duration="450" appear>
			<dialog
				v-if="modalShown"
				ref="modal"
				class="modal-window"
				:class="className"
				:open="modalShown"
				@keydown.esc="hideModal"
			>
				<div class="modal-window__bg" />

				<div class="modal-window__body" @mousedown.self="hideModal">
					<slot :close="hideModal" />
				</div>
			</dialog>
		</Transition>
	</teleport>
</template>

<style lang="sass">
.modal-window
	position: fixed
	top: 0
	left: 0
	z-index: var(--z-index-modal)

	width: 100%
	height: 100%
	padding: 0

	border: none

	background: transparent

	color: var(--body-color)

	&__body
		position: relative
		z-index: var(--z-index-above)

		display: flex
		flex-direction: column
		align-items: center

		width: 100%
		height: 100%

		overflow-x: hidden
		overflow-y: auto

		@include v-scroll

	&__bg
		position: absolute
		inset: 0
		z-index: var(--z-index-below)
		width: 100%
		height: 100%
		background: rgba(0, 0, 0, 0.5)
		pointer-events: none

// modal-fade-in-up
.modal-fade-in-up-enter-active
	& .modal-window
		&__bg
			transition: opacity 0.3s ease

		&__body
			transition: transform 0.3s ease 0.15s, opacity 0.3s ease 0.15s

.modal-fade-in-up-leave-active
	& .modal-window
		&__bg
			transition: opacity 0.3s ease 0.15s

		&__body
			transition: transform 0.3s ease, opacity 0.3s ease

.modal-fade-in-up-enter-from, .modal-fade-in-up-leave-to
	& .modal-window
		&__bg
			opacity: 0

		&__body
			opacity: 0
			transform: translateY(25%)

// modal-fade-in-right
.modal-fade-in-right-enter-active
	& .modal-window
		&__bg
			transition: opacity 0.3s ease

		&__body
			transition: transform 0.3s ease 0.15s, opacity 0.3s ease 0.15s

.modal-fade-in-right-leave-active
	& .modal-window
		&__bg
			transition: opacity 0.3s ease 0.15s

		&__body
			transition: transform 0.3s ease, opacity 0.3s ease

.modal-fade-in-right-enter-from, .modal-fade-in-right-leave-to
	& .modal-window
		&__bg
			opacity: 0

		&__body
			opacity: 0
			transform: translateX(25%)
</style>
