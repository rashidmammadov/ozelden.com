import { environment } from '../../environments/environment';

const PREFIX = environment.api;

export const ENDPOINTS = {

    AUTH: () => `${PREFIX}auth`,
    DATA: (type: string) => `${PREFIX}data/${type}`,
    SUITABILITY: (type?: string) => `${PREFIX}suitability` + (type ? `/${type}` : '')

};
