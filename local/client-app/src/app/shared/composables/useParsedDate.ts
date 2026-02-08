export function useParsedDate() {
	function getParsedDate(date: string | undefined) {
		if (!date)
			return;

		const dateArray = date.split('.').reverse();
		const currentDate = {
			year: +dateArray[0],
			month: +dateArray[1] - 1,
			day: +dateArray[2],
		};

		return new Date(currentDate.year, currentDate.month, currentDate.day);
	}

	return { getParsedDate };
}
