<script setup lang="ts">
import useBaseStore from '@/app/shared/stores/base/base.store';
import { useCookies } from '@vueuse/integrations/useCookies';
import { onMounted, ref, useTemplateRef } from 'vue';

const { isCookieConfirmed } = useBaseStore();

const cookieToast = useTemplateRef<HTMLDialogElement>('cookieToast');
const cookieShown = ref(false);

onMounted(() => {
	checkCookie();
});

function checkCookie() {
	if (!isCookieConfirmed) {
		setTimeout(() => {
			cookieShown.value = true;
		}, 2000);
	}
}

// https://vueuse.org/integrations/useCookies/
const { set: setCookie } = useCookies();
function onConfirmCookie() {
	setCookie('cookieConfirmed', 'Y', {
		path: '/',
		expires: new Date(Date.now() + 2592000000),
	});
	cookieShown.value = false;
}
</script>

<template>
	<teleport to="#modals-container" defer>
		<transition name="cookie" :duration="450" appear>
			<dialog v-if="cookieShown" ref="cookieToast" :open="cookieShown" class="cookie-toast-wrapper wrapper">
				<div class="cookie-toast">
					<div class="cookie-toast__body">
						<span class="cookie-toast__text">Мы используем куки, чтобы сделать сайт удобнее для вас</span>
						<button class="cookie-toast__btn btn btn--color-secondary btn--size-small" @click="onConfirmCookie">
							<span class="btn__text">Хорошо</span>
						</button>
					</div>
				</div>
			</dialog>
		</transition>
	</teleport>
</template>

<style scoped lang="sass">
.cookie-toast-wrapper
	position: fixed
	right: 0
	bottom: 40px
	left: 0
	z-index: var(--z-index-modal)

	display: flex
	justify-content: center

	padding: 0 var(--wrapper-padding)

	border: none
	background: none

	pointer-events: none

	@include media('max', 'tablet')
		bottom: 20px

	@include media('max', 'mobile-xl')
		width: 100%

.cookie-toast
	padding: 14px
	border: none
	border-radius: var(--radius-m)

	background-color: var(--color-neutral-100)
	pointer-events: all

	@include media('max', 'mobile-xl')
		width: 100%

	&__body
		display: flex
		align-items: center
		justify-content: space-between
		gap: 14px

	&__text
		font-size: var(--font-size-text-m)
		line-height: var(--line-height-m)

.cookie-enter-active,
.cookie-leave-active
	transition: opacity 0.3s ease, transform 0.3s ease

.cookie-enter-from,
.cookie-leave-to
	opacity: 0
	transform: translateY(8px)
</style>
