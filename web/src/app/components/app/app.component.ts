import { Component } from '@angular/core';
import { UserType } from 'src/app/interfaces/user-type';
import { DomSanitizer } from '@angular/platform-browser';
import { MatIconRegistry } from '@angular/material/icon';
import { Router } from '@angular/router';
import { select, Store } from '@ngrx/store';
import { AuthService } from '../../services/auth/auth.service';
import { Cookie } from '../../services/cookie/cookie.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ToastService } from '../../services/toast/toast.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { set } from '../../store/actions/user.action';
import { APP } from '../../constants/app.constant';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {
    progress: boolean;
    user: UserType;

    constructor(private domSanitizer: DomSanitizer, public matIconRegistry: MatIconRegistry,
                private store: Store<{progress: boolean, user: UserType}>, private authService: AuthService,
                private router: Router) {
        store.pipe(select('user')).subscribe(data => {
            setTimeout(() => this.user = data, 0);
        });
        store.pipe(select('progress')).subscribe(data => {
            setTimeout(() => this.progress = data, 0);
        });
        this.setSvgIcons();
    }

    logout = async () => {
        this.progress = true;
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
            'bad',
            'female',
            'free-trial',
            'good',
            'group-discount',
            'home',
            'info',
            'left-quote',
            'log-out',
            'male',
            'normal',
            'package-discount',
            'paperclip',
            'right-quote',
            'sliders',
            'star',
            'students',
            'trash',
            'user-plus',
            'x-circle'
        ];
        let path: string = 'assets/icons/';
        svgArray.forEach((svg: string) => {
            this.matIconRegistry.addSvgIcon(svg, this.domSanitizer.bypassSecurityTrustResourceUrl(path + svg + '.svg'));
        });
    }
}
