import { environment } from '../../environments/environment';

const PREFIX = environment.api;

export const ENDPOINTS = {

    ANNOUNCEMENTS: () => `${PREFIX}announcements`,
    AUTH: () => `${PREFIX}auth`,
    DATA: (type: string) => `${PREFIX}data/${type}`,
    PAID: () => `${PREFIX}paid`,
    PICTURE: () => `${PREFIX}picture`,
    PROFILE: (id?: number) => `${PREFIX}profile` + (id ? `/${id}` : ''),
    MISSING_FIELDS: () => `${PREFIX}missing_fields`,
    RECEIVED_OFFERS_COUNT: () => `${PREFIX}received_offers_count`,
    RECOMMENDED: (queryParams?: string) => `${PREFIX}recommended?${queryParams}`,
    REPORTS: (type?: string, queryParams?: string) => `${PREFIX}reports` + (type ? `/${type}` : '') +
          (queryParams ? `?${queryParams}` : ''),
    RESET_PASSWORD: () => `${PREFIX}reset_password`,
    SEARCH: (queryParams?: string) => `${PREFIX}search?${queryParams}`,
    STUDENTS: (studentId?: number) => `${PREFIX}students` + (studentId ? `/${studentId}` : ''),
    SUITABILITIES: (type?: string) => `${PREFIX}suitabilities` + (type ? `/${type}` : ''),
    TUTOR_LECTURES: (id?: number) => `${PREFIX}tutor_lectures` + (id ? `/${id}` : ''),
    OFFERS: (page?: number, offer_id?: number) => `${PREFIX}offers` + (page ? `?page=${page}` : '') + (offer_id ? `/${offer_id}` : ''),
    ONE_SIGNAL: () => `${PREFIX}one_signal`,
    UPDATE_PASSWORD: () => `${PREFIX}update_password`,
    USER: () => `${PREFIX}user`,

};
