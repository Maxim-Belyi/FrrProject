import type { User } from '@app/modules/user/user.types';

export type RegisterPayload = Pick<User, 'name' | 'phone'>;

export type UpdatePayload = Omit<User, 'id' | 'active'>;

export type AuthPayload = Pick<User, 'phone'>;
