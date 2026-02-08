import type { ModalsState } from '@/app/shared/stores/modals/modals.store.types';
import { defineStore } from 'pinia';

const useModalsStore = defineStore('modals', {
	state: (): ModalsState => ({
		modalsRegister: new Set(),
		openedModals: new Set(),
		bodyLocked: false,
		currentGallerySlide: 0,
		modalData: new Map<string, unknown>(),
	}),
	actions: {
		registerModal(modalName: string) {
			if (this.modalsRegister.has(modalName))
				throw new Error(`${modalName} : has already been registered`);

			this.modalsRegister.add(modalName);
		},

		unregisterModal(modalName: string) {
			if (!this.modalsRegister.has(modalName))
				throw new Error(`${modalName} : has not been registered`);

			if (this.openedModals.has(modalName))
				this.closeModal(modalName);

			this.modalsRegister.delete(modalName);
		},

		openModal(modalName: string, data?: unknown) {
			if (!this.modalsRegister.has(modalName))
				throw new Error(`${modalName} : has not been registered`);

			if (this.openedModals.has(modalName)) {
				console.error(`${modalName} : has already been opened`);
				return;
			}

			this.openedModals.add(modalName);
			if (data)
				this.modalData.set(modalName, data);

			if (!this.bodyLocked)
				this._lockBody();
		},

		closeModal(modalName: string) {
			if (!this.modalsRegister.has(modalName))
				throw new Error(`${modalName} : has not been registered`);

			if (!this.openedModals.has(modalName)) {
				console.error(`${modalName} : has not been opened`);
				return;
			}

			this.openedModals.delete(modalName);
			this.modalData.delete(modalName);

			if (!this.hasActiveModals && this.bodyLocked) {
				setTimeout(() => {
					this._unlockBody();
				}, 450);
			}
		},

		openGalleryModal(modalName: string, slide: number, data?: unknown) {
			this.currentGallerySlide = slide;
			this.openModal(modalName, data);
		},

		getModalData<T = unknown>(modalName: string): T | undefined {
			return this.modalData.get(modalName) as T | undefined;
		},

		_lockBody() {
			const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
			document.body.style.setProperty('--scrollbar-compensate', `${scrollbarWidth}px`);
			document.body.classList.add('modal-lock');
			this.bodyLocked = true;
		},

		_unlockBody() {
			document.body.style.removeProperty('--scrollbar-compensate');
			document.body.classList.remove('modal-lock');
			this.bodyLocked = false;
		},
	},
	getters: {
		isModalOpened(): (modalName: string) => boolean {
			return modalName => this.openedModals.has(modalName);
		},
		hasActiveModals(): boolean {
			return this.openedModals.size > 0;
		},
	},
});

export default useModalsStore;
