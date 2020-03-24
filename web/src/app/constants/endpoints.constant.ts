import { environment } from '../../environments/environment';

const PREFIX = environment.api;

export const ENDPOINTS = {

    ANNOUNCEMENTS: () => `${PREFIX}announcements`,
    AUTH: () => `${PREFIX}auth`,
    DATA: (type: string) => `${PREFIX}data/${type}`,
    PAID: () => `${PREFIX}paid`,
    SEARCH: (queryParams?: string) => `${PREFIX}search?${queryParams}`,
    STUDENTS: (studentId?: number) => `${PREFIX}students` + (studentId ? `/${studentId}` : ''),
    SUITABILITIES: (type?: string) => `${PREFIX}suitabilities` + (type ? `/${type}` : ''),
    TUTOR_LECTURES: (id?: number) => `${PREFIX}tutor_lectures` + (id ? `/${id}` : ''),
    OFFERS: () => `${PREFIX}offers`,

};
