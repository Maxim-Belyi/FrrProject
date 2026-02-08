import type { Directive } from 'vue';
import ymReachGoal from '@/app/shared/utils/ya-metrika';

interface YmTargetElement extends HTMLElement {
	_ymHandler?: (event: MouseEvent) => void;
}

/**
 * @description
 * Директива для обработки клика по цели
 * @example
 * <div v-ym-goal="'goalName'"></div>
 * @param {HTMLElement} el
 * @param {object} binding
 * @param {string} binding.value - Название цели
 */
const ymGoal: Directive<YmTargetElement, string> = {
	mounted: (el, binding) => {
		const element = el;
		const goal = binding.value;

		element._ymHandler = () => ymReachGoal(goal);
		element.addEventListener('click', element._ymHandler, false);
	},
	unmounted: (el) => {
		const element = el;
		if (!element._ymHandler)
			return;
		element.removeEventListener('click', element._ymHandler, false);
		delete element._ymHandler;
	},
};

export default ymGoal;
