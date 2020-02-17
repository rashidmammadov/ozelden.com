import { Component, OnInit } from '@angular/core';
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
import { DATE_TIME } from '../../constants/date-time.constant';
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
        {value: TYPES.TUTOR, name: 'Ders Vermek İstiyorum (Öğretmen)'},
        {value: TYPES.TUTORED, name: 'Ders Almak İstiyorum (Öğrenci)'}
    ];
    public currentDate = new Date();
    public days: number[] = [];
    public months: number[] = [];
    public years: number[] = [];
    public selectedDay: number = 1;
    public selectedMonth: number = 1;
    public selectedYear: number = this.currentDate.getFullYear() - 18;
    public MONTHS_MAP = DATE_TIME.MONTHS_MAP;
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
        address: new FormControl('', [Validators.required])
    });

    constructor(private dataService: DataService, private store: Store<{progress: boolean, user: UserType}>,
                private authService: AuthService, private router: Router) {
        for (let i = 1; i <= 31; i++) this.days.push(i);
        for (let i = 1; i <= 12; i++) this.months.push(i);
        for (let i = this.currentDate.getFullYear(); i >= this.currentDate.getFullYear() - 70; i--) this.years.push(i);
    }

    ngOnInit() {
        setTimeout(() => this.getCities());
    }

    public setBirthday() {
        const birthday = new Date(this.selectedYear, this.selectedMonth - 1, this.selectedDay);
        this.registerForm.controls.birthday.setValue(birthday.getTime().toString());
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
            'name': form.name.value,
            'surname': form.surname.value,
            'email': form.email.value,
            'phone' : form.phone.value,
            'type': form.type.value,
            'password': form.password.value,
            'password_confirmation': form.password_confirmation.value,
            'identity_number': form.identity_number.value,
            'sex': form.sex.value,
            'birthday': form.birthday.value,
            'city': form.city.value?.city_name,
            'district': form.district.value,
            'address': form.address.value
        }
    }

}
