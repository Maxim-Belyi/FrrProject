import type { BaseResponse } from '@/app/shared/api/api.types';
import type { PartnerPayload, PartnerProjectsResponse } from '@app/modules/partners/api/partners.api.types';
import { api } from '@app/shared/api/api';

export enum ProjectActions {
	GET_PARTNER_PROJECTS = 'requestPartnerProjects',
}

class PartnersApi {
	protected endpoint = 'projects';

	async requestPartnerProjects(data: PartnerPayload): Promise<BaseResponse<PartnerProjectsResponse[]>> {
		const response = await api.get(`${this.endpoint}/list/`, {
			params: data,
		});
		return response.data;
	}
}

export const partnersApi = new PartnersApi();
