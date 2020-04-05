import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { ProfileService } from '../../services/profile/profile.service';
import { UtilityService } from '../../services/utility/utility.service';
import { UserService } from '../../services/user/user.service';
import { ToastService } from '../../services/toast/toast.service';
import { CityType } from '../../interfaces/city-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { ProfileType } from '../../interfaces/profile-type';
import { UserType } from '../../interfaces/user-type';
import { loaded, loading } from '../../store/actions/progress.action';
import { first } from 'rxjs/operators';
import { set } from '../../store/actions/user.action';

@Component({
    selector: 'app-settings',
    templateUrl: './settings.component.html',
    styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {

    cities: CityType[] = [];
    profile: ProfileType;
    user: UserType;

    userForm = new FormGroup({
        name: new FormControl('', [Validators.required]),
        surname: new FormControl('', [Validators.required]),
        email: new FormControl('', [Validators.required, Validators.email]),
        identity_number: new FormControl('', [Validators.required]),
        sex: new FormControl('', [Validators.required]),
        birthday: new FormControl('', [Validators.required]),
    });

    profileForm = new FormGroup({
        profession: new FormControl(''),
        phone: new FormControl('', [Validators.required]),
        city: new FormControl('', [Validators.required]),
        district: new FormControl('', [Validators.required]),
        address: new FormControl('', [Validators.required]),
        description: new FormControl('')
    });

    constructor(private store: Store<{cities: CityType[], progress: boolean, user: UserType}>,
                private profileService: ProfileService, private userService: UserService) { }

    async ngOnInit() {
        await this.getUser();
        await this.getCities();
        await this.fetchProfile();
        this.prepareUserForm();
        this.prepareProfileForm();
    }

    uploadProfilePicture = async (file) => {
        this.store.select(loading);
        const result = await this.profileService.uploadPicture(UtilityService.parseBase64(file, 'image'));
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            this.profile.picture = response.data;
            this.userService.updateMissingFields('picture', false);
        });
        this.store.select(loaded);
    };

    updateSettings = async () => {
        this.store.select(loading);
        this.updateProfile();
        this.updateUser();
        this.store.select(loaded);
    };

    setBirthday(date) {
        if (date) {
            this.userForm.controls.birthday.setValue(date);
        }
    }

    private fetchProfile = async () => {
        this.store.select(loading);
        const result = await this.profileService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.profile = response.data;
        });
        this.store.select(loaded);
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private getCities = async () => {
        this.cities = await this.store.select('cities').pipe(first()).toPromise();
    };

    private prepareUserForm() {
        if (this.user) {
            let controls = this.userForm.controls;
            controls.name.setValue(this.user.name);
            controls.surname.setValue(this.user.surname);
            controls.email.setValue(this.user.email);
            controls.identity_number.setValue(this.user.identity_number);
            controls.sex.setValue(this.user.sex);
            controls.birthday.setValue(this.user.birthday);
        }
    }

    private prepareProfileForm() {
        if (this.profile) {
            let controls = this.profileForm.controls;
            controls.profession.setValue(this.profile.profession);
            controls.phone.setValue(this.profile.phone);
            if (this.cities) {
                const city = this.cities.find((city: CityType) => city.city_name === this.profile.city);
                if (city) {
                    controls.city.setValue(city);
                    controls.district.setValue(this.profile.district);
                }
            }
            controls.address.setValue(this.profile.address);
            controls.description.setValue(this.profile.description);
        }
    }

    private isProfileFormChanged() {
        const controls = this.profileForm.controls;
        return !(controls.description.value === this.profile.description &&
            controls.profession.value === this.profile.profession &&
            controls.phone.value === this.profile.phone &&
            controls.city.value?.city_name === this.profile.city &&
            controls.district.value === this.profile.district &&
            controls.address.value === this.profile.address);
    }

    private isUserFormChanged() {
        const controls = this.userForm.controls;
        return !(controls.name.value === this.user.name &&
            controls.surname.value === this.user.surname &&
            controls.email.value === this.user.email &&
            controls.identity_number.value === this.user.identity_number &&
            controls.sex.value === this.user.sex &&
            controls.birthday.value === this.user.birthday);
    }

    private setProfileRequestParams() {
        const controls = this.profileForm.controls;
        return {
            'description': controls.description.value,
            'profession': controls.profession.value,
            'phone': controls.phone.value,
            'city': controls.city.value?.city_name,
            'district': controls.district.value,
            'address': controls.address.value
        }
    }

    private setUserRequestParams() {
        const controls = this.userForm.controls;
        return {
            'name': controls.name.value,
            'surname': controls.surname.value,
            'email': controls.email.value,
            'identity_number': controls.identity_number.value,
            'sex': controls.sex.value,
            'birthday': controls.birthday.value
        }
    }

    private updateProfile = async () => {
        if (this.profileForm.valid && this.isProfileFormChanged()) {
            const result = await this.profileService.update(this.setProfileRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                ToastService.show(response.message);
            });
        }
    };

    private updateUser = async () => {
        if (this.userForm.valid && this.isUserFormChanged()) {
            const result = await this.userService.update(this.setUserRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                const controls = this.userForm.controls;
                this.user.name = controls.name.value;
                this.user.surname = controls.surname.value;
                this.user.email = controls.email.value;
                this.user.identity_number = controls.identity_number.value;
                this.user.sex = controls.sex.value;
                this.user.birthday = controls.birthday.value;
                this.store.dispatch(set({user: this.user}));
                ToastService.show(response.message);
            });
        }
    };

}
