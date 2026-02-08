import type { Component } from 'vue';

import useModalsStore from '@/app/shared/stores/modals/modals.store';
import appComponentsGlobal from '@app/app.components.global';
import { useCopyToClipboard } from '@app/shared/composables/useCopyToClipboards';
import ToastOptions from '@app/shared/consts/toastOptions';
import useDirectives from '@app/shared/directives';
import { createPinia } from 'pinia';
import { createApp } from 'vue';
import Toast from 'vue-toastification';
import { createYmaps } from 'vue-yandex-maps';
import '@styles/shared/libs/toast.sass';

const APP_DATA: Component = {
	setup() {
		const { openModal, openGalleryModal } = useModalsStore();
		const { copyToClipboard } = useCopyToClipboard();

		return { copyToClipboard, openModal, openGalleryModal };
	},
};

const APP = createApp(APP_DATA);

APP
	.use(appComponentsGlobal)
	.use(createPinia())
	.use(Toast, ToastOptions)
	.use(createYmaps({
		apikey: '6b8be81f-ca63-4224-8d79-3d07ef92a833',
	}))
;
useDirectives(APP);

export default APP;
