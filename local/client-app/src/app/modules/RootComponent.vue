<script setup lang="ts">
import type { Locale } from '@app/shared/composables/useI18n/useI18n';
import CookieToast from '@app/shared/components/CookieToast.vue';
import ModalFeedback from '@app/shared/components/modals/ModalFeedback.vue';
import ModalPartnerProjects from '@app/shared/components/modals/ModalPartnerProjects.vue';
import ModalVideo from '@app/shared/components/modals/ModalVideo.vue';
import { useCaptcha } from '@app/shared/composables/useCaptcha';
import { initBurgerMenu } from '@app/shared/scripts/burgerMenu';
import calcAppProperties from '@app/shared/scripts/calcProperties';
import initMediaAutoWrapper from '@app/shared/scripts/mediaAutoWrapper';
import useBaseStore from '@app/shared/stores/base/base.store';
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';

interface Doc {
	code: string;
	link: string;
}

interface Props {
	csrfToken: string;
	captchaKey: string;
	locale?: Locale;
	docs: Doc[];
}
const props = defineProps<Props>();
const { initCaptcha } = useCaptcha();
const { csrfToken, captchaKey, docs } = storeToRefs(useBaseStore());

csrfToken.value = props.csrfToken;
captchaKey.value = props.captchaKey;

function fillDocs() {
	props.docs?.forEach((doc) => {
		if (doc.code === 'user-agreement') {
			docs.value.userAgreement = doc.link;
		}
		if (doc.code === 'privacy-policy') {
			docs.value.privacyPolicy = doc.link;
		}
	});
}

onMounted(() => {
	fillDocs();
	// SCRIPTS
	initMediaAutoWrapper();
	calcAppProperties();
	initBurgerMenu();
	initCaptcha();
});
</script>

<template>
	<slot />
	<CookieToast />
	<ModalVideo />
	<ModalPartnerProjects />
	<ModalFeedback />
</template>
