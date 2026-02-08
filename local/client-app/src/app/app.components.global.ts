import type { App } from 'vue';
import RootComponent from '@app/modules/RootComponent.vue';
import { defineAsyncComponent } from 'vue';

const components = [
	{ name: 'RootComponent', component: RootComponent },

	// #region FORMS
	{ name: 'FormFeedback', component: defineAsyncComponent(() => import('@app/modules/forms/components/FormFeedback.vue')) },
	{ name: 'FormFeedbackView', component: defineAsyncComponent(() => import('@app/modules/forms/components/FormFeedbackView.vue')) },
	// #endregion

	// #region TEMPLATES
	{ name: 'ModalTemplate', component: defineAsyncComponent(() => import('@app/shared/components/templates/ModalTemplate.vue')) },
	{ name: 'TabsTemplate', component: defineAsyncComponent(() => import('@app/shared/components/templates/TabsTemplate.vue')) },
	{ name: 'AccordionTemplate', component: defineAsyncComponent(() => import('@app/shared/components/templates/AccordionTemplate.vue')) },
	{ name: 'DropdownTemplate', component: defineAsyncComponent(() => import('@app/shared/components/templates/DropdownTemplate.vue')) },
	{ name: 'ShowMoreTemplate', component: defineAsyncComponent(() => import('@app/shared/components/templates/ShowMoreTemplate.vue')) },
	// #endregion

	// #region MODALS
	{ name: 'ModalGallery', component: defineAsyncComponent(() => import('@app/shared/components/modals/ModalGallery.vue')) },
	// #endregion

	// #region SLIDERS
	{ name: 'ContentSlider', component: defineAsyncComponent(() => import('@app/shared/components/sliders/ContentSlider.vue')) },
	{ name: 'SectionSlider', component: defineAsyncComponent(() => import('@app/shared/components/sliders/SectionSlider.vue')) },
	{ name: 'HeroSlider', component: defineAsyncComponent(() => import('@app/shared/components/sliders/HeroSlider.vue')) },
	// #endregion

	// #region CONTACTS
	{ name: 'ContactsView', component: defineAsyncComponent(() => import('@app/modules/contacts/components/ContactsView.vue')) },
	// #endregion

	// #region MAP
	{ name: 'MapView', component: defineAsyncComponent(() => import('@app/modules/map/components/MapView.vue')) },
	// #endregion

	// #region UI
	{ name: 'BaseLoader', component: defineAsyncComponent(() => import('@app/shared/components/ui/BaseLoader.vue')) },
	{ name: 'BasePagination', component: defineAsyncComponent(() => import('@app/shared/components/ui/BasePagination.vue')) },
	{ name: 'BaseVideo', component: defineAsyncComponent(() => import('@app/shared/components/ui/BaseVideo.vue')) },
	// #endregion

	// #region OTHER
	{ name: 'IconSVG', component: defineAsyncComponent(() => import('@app/shared/components/IconSVG.vue')) },
	{ name: 'UiPreview', component: defineAsyncComponent(() => import('@app/shared/components/UiPreview.vue')) },
	{ name: 'HeaderDropdown', component: defineAsyncComponent(() => import('@app/shared/components/HeaderDropdown.vue')) },
	{ name: 'PageSelect', component: defineAsyncComponent(() => import('@app/shared/components/PageSelect.vue')) },
	// #endregion
];

export default {
	install(app: App<Element>) {
		components.forEach(({ name, component }) => {
			app.component(name, component);
		});
	},
};
