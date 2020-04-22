import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { AuthService } from '../services/auth/auth.service';
import { Cookie } from '../services/cookie/cookie.service';
import { UtilityService } from '../services/utility/utility.service';
import { IHttpResponse } from '../interfaces/i-http-response';
import { UserType } from '../interfaces/user-type';
import { APP } from '../constants/app.constant';
import { set } from '../store/actions/user.action';
import {GoogleAnalyticsService} from "../services/google-analytics/google-analytics.service";

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {

    constructor(private authService: AuthService, private router: Router, private store: Store<{user: UserType}>) { }

    canActivate = async (
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot) => {
        const accessToken = Cookie.get(APP.COOKIE_KEY);
        const securedState = state.url.indexOf('app') !== -1;
        const activateState = state.url.indexOf('activate') !== -1;
        if (accessToken) {
            const result = await this.authService.check();
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                let user: UserType = response.data;
                GoogleAnalyticsService.setEventAction(`${user.email} (${user.name} ${user.surname} - ${user.type})`);
                if (accessToken !== user.remember_token) {
                    Cookie.delete(APP.COOKIE_KEY);
                    Cookie.set(APP.COOKIE_KEY, user.remember_token);
                }
                this.store.dispatch(set({user: user}));
                if (accessToken && Number(user.state) === 1 && !securedState) {
                    this.router.navigateByUrl('app/home');
                    return false;
                } else if (accessToken && Number(user.state) === 0 && !activateState) {
                    this.router.navigateByUrl('activate');
                    return false;
                }
            });
        } else if (!accessToken && (securedState || activateState)) {
            Cookie.delete(APP.COOKIE_KEY);
            this.router.navigateByUrl('login');
            return false;
        }
        return true;
    }

}
