import type { PluginOptions } from 'vue-toastification';
import { POSITION, TYPE } from 'vue-toastification';

const toastOptions: PluginOptions = {
	timeout: 5000,
	position: POSITION.BOTTOM_CENTER,
	hideProgressBar: true,
	draggablePercent: 0.3,
	draggable: false,
	closeOnClick: false,
	pauseOnHover: false,
	closeButton: 'span',
	toastClassName: 'toast',
	bodyClassName: 'toast__body',
	closeButtonClassName: 'toast__close',
	containerClassName: 'toast-container',
	icon: {
		iconClass: 'toast__icon',
	},
	toastDefaults: {
		[TYPE.ERROR]: {
			toastClassName: 'toast toast--error',
			icon: {
				iconClass: 'toast__icon toast__icon--error',
			},
		},
		[TYPE.SUCCESS]: {
			toastClassName: 'toast toast--success',
			icon: {
				iconClass: 'toast__icon toast__icon--success',
			},
		},
		[TYPE.INFO]: {
			toastClassName: 'toast toast--info',
			icon: {
				iconClass: 'toast__icon toast__icon--info',
			},
		},
		[TYPE.WARNING]: {
			toastClassName: 'toast toast--warning',
			icon: {
				iconClass: 'toast__icon toast__icon--warning',
			},
		},
	},
};

export default toastOptions;
