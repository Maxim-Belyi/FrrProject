import type { User } from '@app/modules/user/user.types';

export interface UsersState {
	isLoggedIn: boolean;
	userData: User | null;
}

export enum UserActions {
	USER = 'requestUser',
	AUTH_USER = 'requestAuthUser',
}
