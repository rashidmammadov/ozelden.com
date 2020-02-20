import { environment } from '../../environments/environment';

const PREFIX = environment.api;

export const ENDPOINTS = {

    AUTH: () => `${PREFIX}auth`,
    DATA: (type: string) => `${PREFIX}data/${type}`,
    SUITABILITIES: (type?: string) => `${PREFIX}suitabilities` + (type ? `/${type}` : ''),
    TUTOR_LECTURES: (id?: number) => `${PREFIX}tutor_lectures` + (id ? `/${id}` : '')

};
