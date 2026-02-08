import type { FormFeedbackPayload } from '@/app/modules/forms/api/forms.api.types';
import type { BaseResponse } from '@/app/shared/api/api.types';
import { objectToFormData } from '@/app/shared/utils/objectToFormData';
import { api } from '@app/shared/api/api';

export enum FormsActions {
	SEND_FEEDBACK = 'requestSendFeedbackForm',
}

class FormsApi {
	protected endpoint = 'form';

	async requestSendFeedbackForm(data: FormFeedbackPayload, page: 'faq' | 'contacts' = 'faq'): Promise<BaseResponse<null>> {
		const body = objectToFormData(data);
		const response = await api.post(`${this.endpoint}/feedback/${page}/`, body, {
			captcha: true,
			csrf: true,
		});
		return response.data;
	}
}

export const formsApi = new FormsApi();
