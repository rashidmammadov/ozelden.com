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

}
