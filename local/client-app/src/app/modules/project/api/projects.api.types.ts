export interface ProjectPayload {
	id: number;
}

export interface TeamMember {
	id: string;
	name: string;
	description: string;
}
export interface Pictures {
	id: number;
	src: string;
}

export interface ProjectResponse {
	id: number;
	label: string;
	address: string;
	status: {
		name: string;
		xmlId: string;
	};
	location: {
		lat: string;
		lon: string;
	};
	description: string;
	pictures: null | Pictures[];
	team: TeamMember[];
}
