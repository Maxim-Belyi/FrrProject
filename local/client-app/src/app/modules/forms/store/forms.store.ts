import type { FormFeedbackPayload } from '@/app/modules/forms/api/forms.api.types';

import { FormsActions, formsApi } from '@/app/modules/forms/api/forms.api';
import useBaseStore from '@/app/shared/stores/base/base.store';
import { defineStore } from 'pinia';

const useFormStore = defineStore('form', {
	actions: {
		async requestSendFeedbackForm(data: FormFeedbackPayload, page: 'faq' | 'contacts' = 'faq') {
			const { startLoading, stopLoading } = useBaseStore();
			startLoading(FormsActions.SEND_FEEDBACK);

			const response = await formsApi.requestSendFeedbackForm(data, page);

			stopLoading(FormsActions.SEND_FEEDBACK);
			return response;
		},
	},
});

export default useFormStore;
