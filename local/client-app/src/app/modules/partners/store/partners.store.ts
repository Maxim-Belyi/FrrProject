import type { PartnerPayload } from '@app/modules/partners/api/partners.api.types';
import useBaseStore from '@/app/shared/stores/base/base.store';
import { partnersApi } from '@app/modules/partners/api/partners.api';
import { ProjectActions } from '@app/modules/project/api/projects.api';
import { defineStore } from 'pinia';

const usePartnerStore = defineStore('project', {
	actions: {
		async requestPartnerProjects(data: PartnerPayload) {
			const { startLoading, stopLoading } = useBaseStore();
			startLoading(ProjectActions.GET_PROJECT);

			const response = await partnersApi.requestPartnerProjects(data);

			stopLoading(ProjectActions.GET_PROJECT);
			return response;
		},
	},
});

export default usePartnerStore;
