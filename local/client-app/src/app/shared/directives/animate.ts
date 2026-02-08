import type {
	AnimatedHTMLElement,
	AnimateOptions,
} from '@/app/shared/utils/animationController';
import type { Directive } from 'vue';
import AnimationController from '@/app/shared/utils/animationController';

const animate: Directive<AnimatedHTMLElement, AnimateOptions | string> = {
	beforeMount: (el, binding) => {
		const animationClass
			= typeof binding.value === 'string' ? binding.value : binding.value.animationClass;
		el.classList.add(animationClass);

		setTimeout(() => {
			AnimationController.add(el, {
				...(typeof binding.value === 'string' ? { animationClass: binding.value } : binding.value),
			});
		}, 250);
	},
	unmounted: (el) => {
		if (Object.hasOwn(el, 'animationParams'))
			AnimationController.remove(el);
	},
};

export default animate;
