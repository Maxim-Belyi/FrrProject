declare global {
	interface Window {
		ymId: number;
		ym: (counterId: number, action: string, target: string) => void;
	}
}

export default function ymReachGoal(goalName: string): void {
	if (window.ymId)
		window.ym(window.ymId, 'reachGoal', goalName);
}
