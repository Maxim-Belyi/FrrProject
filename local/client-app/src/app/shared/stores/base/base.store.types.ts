export interface Docs {
	userAgreement: string;
	privacyPolicy: string;
}

export interface BaseState {
	loadingPool: Set<string>;
	cookieConfirmed: string;
	csrfToken: string;
	captchaKey: string;
	docs: Docs;
}
