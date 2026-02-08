import type { App } from 'vue';
import animate from '@/app/shared/directives/animate';
import initialScroll from '@app/shared/directives/initial-scroll';
import toggleHeader from '@app/shared/directives/toggleHeader';
import { vMaska } from 'maska/vue';

export default function useDirectives(AppInstance: App) {
	AppInstance
		.directive('mask', vMaska)
		.directive('animate', animate)
		.directive('initialScroll', initialScroll)
		.directive('toggle-header', toggleHeader)
		// .directive('fixed-header', fixedHeader)
		// .directive('ym-goal', ymGoal)
	;
	return AppInstance;
}
