import type { ProjectPayload } from '@app/modules/project/api/projects.api.types';
import useBaseStore from '@/app/shared/stores/base/base.store';
import { ProjectActions, projectsApi } from '@app/modules/project/api/projects.api';
import { defineStore } from 'pinia';

const useProjectStore = defineStore('project', {
	actions: {
		async requestProject(data: ProjectPayload) {
			const { startLoading, stopLoading } = useBaseStore();
			startLoading(ProjectActions.GET_PROJECT);

			const response = await projectsApi.requestProject(data);

			stopLoading(ProjectActions.GET_PROJECT);
			return response;
		},
	},
});

export default useProjectStore;
