export interface PartnerPayload {
	id: number;
	idProjects?: number;
}

export interface PartnerPicture {
	id: number;
	src: string;
}

export interface PartnerProjectsResponse {
	id: number;
	label: string;
	address: string;
	pictures: PartnerPicture[];
	link: string;
}
