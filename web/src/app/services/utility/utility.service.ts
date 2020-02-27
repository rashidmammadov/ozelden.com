import { Injectable, Injector } from '@angular/core';
import { HttpParams } from '@angular/common/http';
import { Router } from '@angular/router';
import { Cookie } from '../cookie/cookie.service';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { ToastService } from '../toast/toast.service';
import { Observable, of } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { APP } from '../../constants/app.constant';
import { DATE_TIME } from '../../constants/date-time.constant';
import { MESSAGES } from '../../constants/messages.constant';

@Injectable({
    providedIn: 'root'
})
export class UtilityService {

    public static injector: Injector;

    public static handleResponseFromService(result: IHttpResponse | ErrorResponse, successCallback: (result) => void): void {
        if (!navigator.onLine) {
            ToastService.show(MESSAGES.ERROR.ERR_INTERNET_DISCONNECTED);
        } else if ((result as IHttpResponse).status === 'success') {
            successCallback(result);
        } else if ((result as IHttpResponse).status === 'error') {
            ToastService.show((result as IHttpResponse).message)
        } else if (result instanceof ErrorResponse) {
            ToastService.show(result.message);
            if (result.status_code === 401) {
                Cookie.delete(APP.COOKIE_KEY);
                UtilityService.injector.get(Router).navigateByUrl('login');
            }
        }
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
        }
        return date;
    }

}
