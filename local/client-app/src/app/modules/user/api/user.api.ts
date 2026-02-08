import type { AuthPayload } from '@/app/modules/user/api/user.api.types';
import type { BaseResponse } from '@/app/shared/api/api.types';
import type { User } from '@app/modules/user/user.types';
import { objectToFormData } from '@/app/shared/utils/objectToFormData';
import { api } from '@app/shared/api/api';

class UserApi {
	protected endpoint = 'user';

	async requestUser(): Promise<BaseResponse<User>> {
		const response = await api.get(`${this.endpoint}/data/`);
		return response.data;
	}

	async requestAuthUser(data: AuthPayload): Promise<BaseResponse<User>> {
		const body = objectToFormData(data);
		const response = await api.post(`${this.endpoint}/auth/`, body);
		return response.data;
	}
}

export const userApi = new UserApi();
