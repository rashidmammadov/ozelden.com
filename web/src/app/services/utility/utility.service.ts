import { Injectable, Injector } from '@angular/core';
import { HttpParams } from '@angular/common/http';
import { Router } from '@angular/router';
import { Cookie } from '../cookie/cookie.service';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { GoogleAnalyticsService } from '../google-analytics/google-analytics.service';
import { ToastService } from '../toast/toast.service';
import { Observable, of } from 'rxjs';
import { UserType } from '../../interfaces/user-type';
import { catchError } from 'rxjs/operators';
import { APP } from '../../constants/app.constant';
import { DATE_TIME } from '../../constants/date-time.constant';
import { MESSAGES } from '../../constants/messages.constant';
import { TYPES } from '../../constants/types.constant';

@Injectable({
    providedIn: 'root'
})
export class UtilityService {

    public static injector: Injector;

    public static dataAsGridView(data, column: number) {
        let groups = [];
        if (data && data.length) {
            const groupCount = Math.ceil(data.length / column);
            let start = 0;
            let end = column;
            for (let i = 0; i < groupCount; i++) {
                let groupItems = data.slice(start, end);
                groups.push(groupItems);
                start += column;
                end += column;
            }
        }
        return groups;
    }

    public static handleResponseFromService(result: IHttpResponse | ErrorResponse, successCallback: (result) => void): void {
        if (!navigator.onLine) {
            ToastService.show(MESSAGES.ERROR.ERR_INTERNET_DISCONNECTED);
        } else if ((result as IHttpResponse).status === 'success') {
            successCallback(result);
        } else if ((result as IHttpResponse).status === 'error') {
            ToastService.show((result as IHttpResponse).message);
            GoogleAnalyticsService.errorResponse((result as IHttpResponse).message)
        } else if (result instanceof ErrorResponse) {
            ToastService.show(result.message || MESSAGES.ERROR.INTERNAL_ERROR);
            GoogleAnalyticsService.errorResponse(result.message || MESSAGES.ERROR.INTERNAL_ERROR);
            if (result.status_code === 401) {
                Cookie.delete(APP.COOKIE_KEY);
                UtilityService.injector.get(Router).navigateByUrl('login');
            }
        }
    }

    public static isValid(value) {
        return value !== null && value !== undefined;
    }

    public static parseBase64(base64: string, type?: string) {
        let result = {base64: null, file_type: null};
        const additional = type ? type.length + 1 : 0;
        const split = base64.split(',');
        if (split && split.length) {
            result.file_type = base64.slice(split[0].indexOf('data:') + 5 + additional, split[0].indexOf(';'));
            result.base64 = base64;
        }
        return result;
    }

    public static prepareNavButtons(user: UserType) {
        let buttons = [];
        const home = {routerLink: '/app/home', icon: 'home', title: 'Ana Sayfa'};
        const offers = {routerLink: '/app/offers', icon: 'offers', title: 'Teklifler', badgeOffersCount: true};
        const tutoredStudents = {routerLink: '/app/tutored-students', icon: 'students', title: 'Öğrencilerim'};
        const paidService = {routerLink: '/app/paid-service', icon: 'star', title: 'Hizmetler'};
        const lectures = {routerLink: '/app/lectures', icon: 'paperclip', title: 'Derslerim'};
        const suitability = {routerLink: '/app/suitability', icon: 'sliders', title: 'Uygunluk'};
        const settings = {routerLink: '/app/settings', icon: 'settings', title: 'Ayarlar'};
        if (user && user.type === TYPES.TUTOR) {
            buttons = [home, offers, paidService, lectures, suitability, settings];
        } else if (user && user.type === TYPES.TUTORED) {
            buttons = [home, offers, tutoredStudents, settings];
        }
        return buttons;
    }

    public static pipeHttpResponse(response: Observable<IHttpResponse>): Promise<ErrorResponse | IHttpResponse> {
        return response.pipe(catchError((err) => of(new ErrorResponse(err.error)))).toPromise();
    }

    public static setHttpParams(params) {
        let body = new HttpParams();
        Object.keys(params).forEach((key: string) => {
            body = body.set(key, params[key])
        });
        return body;
    }

    public static millisecondsToDate(milliseconds: number | string | Date, format = null): number | string | Date {
        if (milliseconds) {
            if (typeof milliseconds === 'string' || typeof milliseconds === 'number') {
                let date: any = new Date(Number(milliseconds));
                return UtilityService.convertToFormat(date, format);
            } else if (format) {
                return UtilityService.convertToFormat(milliseconds, format);
            } else {
                return milliseconds;
            }
        } else {
            return '-';
        }
    }

    private static convertToFormat(date, format: string = null) {
        const day = date.getDate();
        const monthIndex = date.getMonth();
        const year = date.getFullYear();
        const hour = date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
        const minute = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
        const second = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();
        if (format === DATE_TIME.FORMAT.DATE) {
            date = `${day} ${DATE_TIME.MONTHS_MAP[monthIndex]} ${year}`;
        } else if (format === DATE_TIME.FORMAT.DATE_TIME) {
            date = `${day} ${DATE_TIME.MONTHS_MAP[monthIndex]} ${year} ${hour}:${minute}:${second}`;
        } else if (format === DATE_TIME.FORMAT.TIME) {
            date = `${hour}:${minute}:${second}`;
        } else if (format === DATE_TIME.FORMAT.TOTAL_YEARS) {
            const currentYear = new Date().getFullYear();
            const currentMonthIndex = new Date().getMonth();
            date = currentYear - year - ((monthIndex - currentMonthIndex) >= 0 ? 1 : 0);
        }
        return date;
    }

}
