import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { first } from 'rxjs/operators';
import { CityType } from '../../interfaces/city-type';
import { UserType } from '../../interfaces/user-type';
import { AuthService } from '../../services/auth/auth.service';
import { Cookie } from '../../services/cookie/cookie.service';
import { DataService } from '../../services/data/data.service';
import { GoogleAnalyticsService } from '../../services/google-analytics/google-analytics.service';
import { MetaService } from '../../services/meta/meta.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
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

    public cities: CityType[] = [];
    public userTypes: object[] = [
        {value: TYPES.TUTOR, name: 'Ders Vermek İstiyorum (Öğretmen)'},
        {value: TYPES.TUTORED, name: 'Ders Almak İstiyorum (Öğrenci)'}
    ];
    private user: UserType;

    registerForm = new FormGroup({
        name: new FormControl('', [Validators.required]),
        surname: new FormControl('', [Validators.required]),
        type: new FormControl(TYPES.TUTORED, [Validators.required]),
        email: new FormControl('', [Validators.required, Validators.email]),
        phone: new FormControl('', [Validators.required]),
        password: new FormControl('', [Validators.required, Validators.pattern(REGEX.PASSWORD)]),
        password_confirmation: new FormControl('', [Validators.required, Validators.pattern(REGEX.PASSWORD)]),
        identity_number: new FormControl('', [Validators.required]),
        sex: new FormControl('female', [Validators.required]),
        birthday: new FormControl('', [Validators.required]),
        city: new FormControl('', [Validators.required]),
        district: new FormControl('', [Validators.required]),
        address: new FormControl('', [Validators.required]),
        pdpl: new FormControl(false, [Validators.requiredTrue])
    });

    constructor(private dataService: DataService, private store: Store<{cities: CityType[], progress: boolean, user: UserType}>,
                private authService: AuthService, private router: Router, private metaService: MetaService) {
        metaService.updateOgMetaTags('ozelden.com - Kayıt');
    }

    ngOnInit() {
        setTimeout(() => this.getCities());
    }

    setBirthday(date) {
        if (date) {
            this.registerForm.controls.birthday.setValue(date);
        }
    }

    public register = async () => {
        if (this.registerForm.valid) {
            this.store.dispatch(loading());
            GoogleAnalyticsService.register(this.setRegisterRequestParams());
            const result = await this.authService.register(this.setRegisterRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                this.user = response.data;
                Cookie.set(APP.COOKIE_KEY, this.user.remember_token);
                this.store.dispatch(set({user: this.user}));
                this.router.navigateByUrl('app/settings');
            });
            this.store.dispatch(loaded());
        }
    };

    private getCities = async () => {
        this.cities = await this.store.select('cities').pipe(first()).toPromise();
        this.registerForm.controls.city.setValue(this.cities[33]);
    };

    private setRegisterRequestParams() {
        const controls = this.registerForm.controls;
        return {
            'name': controls.name.value,
            'surname': controls.surname.value,
            'email': controls.email.value,
            'phone' : controls.phone.value,
            'type': controls.type.value,
            'password': controls.password.value,
            'password_confirmation': controls.password_confirmation.value,
            'identity_number': controls.identity_number.value,
            'sex': controls.sex.value,
            'birthday': controls.birthday.value,
            'city': controls.city.value?.city_name,
            'district': controls.district.value,
            'address': controls.address.value
        }
    }

}
