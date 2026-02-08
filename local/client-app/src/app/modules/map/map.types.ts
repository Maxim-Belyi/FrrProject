export interface CityStatistic {
	id: number;
	previewText: string;
	name: string;
}

export interface Location {
	lat: number;
	lon: number;
}

export interface Status {
	name: string;
	xmlId: string;
}

export interface Point {
	id: number;
	label: string;
	address: string;
	status: Status;
	location: Location;
}

export interface CityAdvanced {
	id: number;
	name: string;
	coordinates: Location;
	statistics: CityStatistic[];
	points: Point[];
	selected?: boolean;
	zoomMap?: number;
}

export interface City {
	id: number;
	name: string;
}
