import type { BaseResponse } from '@/app/shared/api/api.types';
import type { ProjectPayload, ProjectResponse } from '@app/modules/project/api/projects.api.types';
import { api } from '@app/shared/api/api';

export enum ProjectActions {
	GET_PROJECT = 'requestProject',
}

class ProjectsApi {
	protected endpoint = 'project';

	async requestProject(data: ProjectPayload): Promise<BaseResponse<ProjectResponse>> {
		const response = await api.get(`${this.endpoint}/get/`, {
			params: data,
		});
		return response.data;
	}
}

export const projectsApi = new ProjectsApi();
