export enum RouteNames {
	MAIN_PAGE = 'MainView',
	AUTH_PAGE = 'AuthView',
	REGISTER_PAGE = 'RegisterView',
}

export enum LayoutNames {
	MAIN_LAYOUT = 'MainLayout',
	AUTH_LAYOUT = 'AuthLayout',
}

export type BaseLayouts = (typeof LayoutNames)[keyof typeof LayoutNames];
