import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { Router } from '@angular/router';
import { AuthService} from "../../services/auth/auth.service";
import { Cookie } from '../../services/cookie/cookie.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UserService } from '../../services/user/user.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ToastService } from '../../services/toast/toast.service';
import { ForgotPasswordDialogComponent } from '../dialogs/forgot-password-dialog/forgot-password-dialog.component';
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

    constructor(private authService: AuthService, private router: Router, private dialog: MatDialog,
                private store: Store<{progress: boolean, user: UserType}>, private userService: UserService) { }

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

    public resetPasswordDialog() {
        this.dialog.open(ForgotPasswordDialogComponent, {
            width: '500px',
            data: this.loginForm.controls.email.value
        }).afterClosed().toPromise().then((email) => {
            !!email && this.resetPassword(email);
        });
    }

    private resetPassword = async (email: string) => {
        this.store.dispatch(loading());
        const result = await this.userService.resetPassword({email: email});
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
        });
        this.store.dispatch(loaded());
    };

    private setLoginRequestParams() {
        const form = this.loginForm.controls;
        return {
            'email': form.email.value,
            'password': form.password.value
        }
    }

}
