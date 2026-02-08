export interface AnimateOptions {
	animationClass: string;
	animationFinalClass?: string;
	animationType?: 'transition' | 'animation';
	isReturnable?: boolean;
}

export interface AnimatedHTMLElement extends HTMLElement {
	animated?: boolean;
	animationParams?: AnimateOptions;
}

class AnimationController {
	private static instance: AnimationController;

	protected targets: AnimatedHTMLElement[] = [];

	protected observer: IntersectionObserver | null = null;

	protected observerParams: IntersectionObserverInit = {
		rootMargin: '0px',
		threshold: 0.35,
	};

	protected baseDelay = 0.25;

	protected delayShift = 0.25;

	private constructor() {
		this.init();
	}

	public static getInstance(): AnimationController {
		if (!AnimationController.instance)
			AnimationController.instance = new AnimationController();

		return AnimationController.instance;
	}

	private init() {
		this.observer = new IntersectionObserver(
			entries => this.observerCallback(entries),
			this.observerParams,
		);
	}

	private observerCallback(entries: IntersectionObserverEntry[]) {
		const { delayShift } = this;
		let delay = this.baseDelay;

		entries.forEach((entry) => {
			const target = entry.target as AnimatedHTMLElement;
			const {
				animationClass,
				animationFinalClass,
				animationType,
				isReturnable = false,
			} = target.animationParams as Required<AnimateOptions>;

			if (entry.isIntersecting && !target.animated) {
				target.style.setProperty(`${animationType}-delay`, `${delay}s`);
				delay += delayShift;

				target.classList.add(animationFinalClass);

				if (animationType === 'transition') {
					let transitions = getComputedStyle(target).transitionProperty.split(', ');
					const transitionEndHandler = (e: TransitionEvent) => {
						e.stopImmediatePropagation();
						if (e.target !== target)
							return;
						transitions = transitions.filter(transition => transition !== e.propertyName);
						if (transitions.length === 0) {
							target.removeEventListener('transitionend', transitionEndHandler);
							if (!isReturnable)
								this.remove(target);
						}
					};
					target.addEventListener('transitionend', transitionEndHandler);
				}
				else {
					let animations = getComputedStyle(target).animationName.split(', ');
					const animationEndHandler = (e: AnimationEvent) => {
						e.stopImmediatePropagation();
						if (e.target !== target)
							return;
						animations = animations.filter(animation => animation !== e.animationName);
						if (animations.length === 0) {
							target.removeEventListener('animationend', animationEndHandler);
							if (!isReturnable)
								this.remove(target);
						}
					};
					target.addEventListener('animationend', animationEndHandler);
				}
			}
			else if (isReturnable) {
				target.classList.add(animationClass);
				target.animated = false;
			}
		});
	}

	public add(target: AnimatedHTMLElement, options: AnimateOptions) {
		target.animated = false;
		target.animationParams = {
			...options,
			...(!options.animationFinalClass && { animationFinalClass: 'animate' }),
			...(!options.animationType && { animationType: 'transition' }),
			...(!options.isReturnable && { isReturnable: false }),
		};

		this.targets.push(target);
		this.observer?.observe(target);
	}

	public remove(target: AnimatedHTMLElement) {
		const { animationClass, animationFinalClass, animationType }
			= target.animationParams as Required<AnimateOptions>;

		this.observer?.unobserve(target);

		target.classList.remove(animationClass, animationFinalClass);
		target.style.removeProperty(`${animationType}-delay`);

		delete target.animationParams;
		delete target.animated;

		this.targets = this.targets.filter(t => t !== target);
	}

	public clear() {
		this.observer?.disconnect();
		this.targets.forEach((target) => {
			delete target.animationParams;
			delete target.animated;
		});
		this.targets = [];
	}
}

export default AnimationController.getInstance();
