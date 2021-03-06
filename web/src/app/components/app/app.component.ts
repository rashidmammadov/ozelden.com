import { Component } from '@angular/core';
import { UserType } from 'src/app/interfaces/user-type';
import { DomSanitizer } from '@angular/platform-browser';
import { MatIconRegistry } from '@angular/material/icon';
import { NavigationEnd, Router } from '@angular/router';
import { select, Store } from '@ngrx/store';
import { AuthService } from '../../services/auth/auth.service';
import { Cookie } from '../../services/cookie/cookie.service';
import { GoogleAnalyticsService } from '../../services/google-analytics/google-analytics.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ToastService } from '../../services/toast/toast.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { set } from '../../store/actions/user.action';
import { APP } from '../../constants/app.constant';
import { environment } from '../../../environments/environment';

declare let ga: Function;
@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {

    progress: boolean;
    user: UserType;
    buttons = [];
    offersCount: number = 0;

    constructor(private domSanitizer: DomSanitizer, public matIconRegistry: MatIconRegistry,
                private store: Store<{progress: boolean, offersCount: number, user: UserType}>,
                private authService: AuthService,
                private router: Router) {
        store.pipe(select('user')).subscribe(data => {
            setTimeout(() => {
                this.user = data;
                this.buttons = UtilityService.prepareNavButtons(this.user);
            }, 0);
        });
        store.pipe(select('progress')).subscribe(data => {
            setTimeout(() => this.progress = data, 0);
        });
        store.pipe(select('offersCount')).subscribe(data => {
            setTimeout(() => this.offersCount = data, 0);
        });
        router.events.subscribe(event => {
            if (environment.production && event instanceof NavigationEnd) {
                ga('set', 'page', event.urlAfterRedirects);
                ga('send', 'pageview');
            }
        });
        this.setSvgIcons();
    }

    logout = async () => {
        this.progress = true;
        GoogleAnalyticsService.logout();
        GoogleAnalyticsService.setEventAction('Anonymous');
        const result = await this.authService.logout();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            Cookie.delete(APP.COOKIE_KEY);
            this.store.dispatch(set({user: null}));
            ToastService.show(response.message);
            this.router.navigateByUrl('login');
        });
        this.progress = false;
    };

    private setSvgIcons() {
        const svgArray: string[] = [
            'alert-triangle',
            'announcement',
            'bad',
            'bell',
            'calendar',
            'dislike',
            'experience-tag',
            'facebook',
            'female',
            'filter',
            'free-trial',
            'good',
            'group-discount',
            'hangouts',
            'heart',
            'home',
            'hyperlink',
            'info',
            'instagram',
            'left-chevron',
            'left-circle',
            'left-quote',
            'like',
            'log-out',
            'mail',
            'male',
            'map-pin',
            'menu',
            'normal',
            'offer-in',
            'offer-out',
            'offer-tag',
            'offers',
            'package-discount',
            'paperclip',
            'phone',
            'pie-chart',
            'price-tag',
            'ranking-tag',
            'recommend',
            'right-chevron',
            'right-circle',
            'right-quote',
            'search',
            'settings',
            'shield',
            'skype',
            'sliders',
            'star',
            'student-tag',
            'students',
            'target',
            'trash',
            'trending-up',
            'twitter',
            'user-plus',
            'x',
            'x-circle',
            'youtube',
            'zoom'
        ];
        let path: string = 'assets/icons/';
        svgArray.forEach((svg: string) => {
            this.matIconRegistry.addSvgIcon(svg, this.domSanitizer.bypassSecurityTrustResourceUrl(path + svg + '.svg'));
        });
    }
}
