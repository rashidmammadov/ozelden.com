import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { Router } from '@angular/router';
import { AuthService} from "../../services/auth/auth.service";
import { Cookie } from '../../services/cookie/cookie.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../../services/utility/utility.service';
import { UserType } from '../../interfaces/user-type';
import { loading, loaded } from '../../store/actions/progress.action';
import { set } from '../../store/actions/user.action';
import { APP } from '../../constants/app.constant';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss']
})
export class LoginComponent {

    private user: UserType;

    loginForm = new FormGroup({
        email: new FormControl('', [Validators.required, Validators.email]),
        password: new FormControl('', [Validators.required])
    });

    constructor(private authService: AuthService, private router: Router,
                private store: Store<{progress: boolean, user: UserType}>) { }

    public login = async () => {
        if (this.loginForm.valid) {
            this.store.dispatch(loading());
            const result = await this.authService.login(this.setLoginRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                this.user = response.data;
                Cookie.set(APP.COOKIE_KEY, this.user.remember_token);
                this.store.dispatch(set({user: this.user}));
                this.router.navigateByUrl('app/home');
            });
            this.store.dispatch(loaded());
        }
    };

    private setLoginRequestParams() {
        const form = this.loginForm.controls;
        return {
            'email': form.email.value,
            'password': form.password.value
        }
    }

}
