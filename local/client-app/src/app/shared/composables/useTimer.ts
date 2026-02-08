import type { MaybeRefOrGetter } from 'vue';
import { computed, onUnmounted, ref, toValue } from 'vue';

export interface UseTimerOptions {
	action?: 'increase' | 'decrease';
	threshold?: MaybeRefOrGetter<number>;
	step?: number;
}

export function useTimer(options: UseTimerOptions = {}) {
	const {
		action = 'decrease',
		threshold = 0,
		step = 1,
	} = options;

	let instance: ReturnType<typeof setInterval> | null = null;

	const time = ref(0);

	function start(timeout: MaybeRefOrGetter<number>) {
		if (instance)
			stop();

		time.value = toValue(timeout);
		instance = setInterval(() => {
			switch (action) {
				case 'decrease':
					time.value -= step;
					if (time.value <= toValue(threshold))
						stop();
					break;
				case 'increase':
					time.value += step;
					if (time.value >= toValue(threshold))
						stop();
					break;
				default:
					throw new Error(`Неизвестное значение action: ${action}`);
			}
		}, step * 1000);
	}

	function stop() {
		if (instance) {
			clearInterval(instance);
			instance = null;
		}
	}

	onUnmounted(stop);

	const formattedTime = computed(() => {
		const minutes = Math.floor(time.value / 60).toString().padStart(2, '0');
		const seconds = (time.value % 60).toString().padStart(2, '0');
		return `${minutes}:${seconds}`;
	});

	return {
		start,
		stop,
		time,
		formattedTime,
	};
}
