import {Component, OnInit} from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { UserType } from '../../interfaces/user-type';
import { AuthService } from '../../services/auth/auth.service';
import { Cookie } from '../../services/cookie/cookie.service';
import { DataService } from '../../services/data/data.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../../services/utility/utility.service';
import { set } from '../../store/actions/user.action';
import { loaded, loading } from '../../store/actions/progress.action';
import { APP } from '../../constants/app.constant';
import { REGEX } from '../../constants/regex.constant';
import { TYPES } from '../../constants/types.constant';

@Component({
    selector: 'app-register',
    templateUrl: './register.component.html',
    styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

    public cities = [];
    public userTypes: object[] = [
        {value: TYPES.TUTOR, name: 'Ders Vermek İstiyorum'},
        {value: TYPES.TUTORED, name: 'Ders Almak İstiyorum'}
    ];
    private user: UserType;

    registerForm = new FormGroup({
        email: new FormControl('', [Validators.required, Validators.email]),
        type: new FormControl(TYPES.TUTORED, [Validators.required]),
        password: new FormControl('', [Validators.required, Validators.pattern(REGEX.PASSWORD)]),
        passwordConfirmation: new FormControl('', [Validators.required, Validators.pattern(REGEX.PASSWORD)]),
        city: new FormControl('', [Validators.required]),
        district: new FormControl('', [Validators.required])
    });

    constructor(private dataService: DataService, private store: Store<{progress: boolean, user: UserType}>,
                private authService: AuthService, private router: Router) { }

    ngOnInit() {
        setTimeout(() => this.getCities());
    }

    public register = async () => {
        if (this.registerForm.valid) {
            this.store.dispatch(loading());
            const result = await this.authService.register(this.setRegisterRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                this.user = response.data;
                Cookie.set(APP.COOKIE_KEY, this.user.remember_token);
                this.store.dispatch(set({user: this.user}));
                this.router.navigateByUrl('app/home');
            });
            this.store.dispatch(loaded());
        }
    };

    private getCities = async () => {
        this.store.dispatch(loading());
        const result = await this.dataService.cities();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.cities = response.data;
            this.registerForm.controls.city.setValue(this.cities[33]);
        });
        this.store.dispatch(loaded());
    };

    private setRegisterRequestParams() {
        const form = this.registerForm.controls;
        return {
            'email': form.email.value,
            'type': form.type.value,
            'password': form.password.value,
            'passwordConfirmation': form.passwordConfirmation.value,
            'name': form.name.value,
            'surname': form.surname.value,
            'birthday': form.birthday.value,
            'sex': form.sex.value,
            'identity_number': form.identity_number.value,
            'city': form.city.value,
            'district': form.district.value,
            'address': form.address.value
        }
    }

}
