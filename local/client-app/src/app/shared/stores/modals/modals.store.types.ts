export interface ModalsState {
	modalsRegister: Set<string>;
	openedModals: Set<string>;
	bodyLocked: boolean;
	currentGallerySlide: number;
	modalData: Map<string, unknown>;
}
