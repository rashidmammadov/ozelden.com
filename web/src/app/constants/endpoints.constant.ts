import { environment } from '../../environments/environment';

const PREFIX = environment.api;

export const ENDPOINTS = {

    AUTH: () => `${PREFIX}auth`
};
