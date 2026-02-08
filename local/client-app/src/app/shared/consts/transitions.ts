import { defineAsyncComponent } from 'vue';

const transitions = {
	FadeTransition: defineAsyncComponent(
		() => import('@/app/shared/components/transitions/FadeTransition.vue'),
	),
	ScaleTransition: defineAsyncComponent(
		() => import('@/app/shared/components/transitions/ScaleTransition.vue'),
	),
};

export default transitions;
