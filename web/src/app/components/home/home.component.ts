import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatBottomSheet } from '@angular/material/bottom-sheet';
import { Store } from '@ngrx/store';
import { UtilityService } from '../../services/utility/utility.service';
import { SearchService } from '../../services/search/search.service';
import { AddAnnouncementComponentBottomSheet } from '../sheets/add-announcement-bottom-sheet/add-announcement-component-bottom-sheet.component';
import { CityType } from '../../interfaces/city-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { InfoType } from '../../interfaces/info-type';
import { LectureType } from '../../interfaces/lecture-type';
import { UserType } from '../../interfaces/user-type';
import { first } from 'rxjs/operators';
import { SELECTORS } from '../../constants/selectors.constant';
import { TYPES } from '../../constants/types.constant';
import { loading, loaded } from '../../store/actions/progress.action';

@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

    cities: CityType[] = [];
    lectures: LectureType[] = [];
    searchResult: InfoType[];
    genders = SELECTORS.GENDERS;
    orders = SELECTORS.ORDERS;
    gendersMap = {};
    ordersMap = {};
    changeMode: boolean = true;
    user: UserType;
    TYPES = TYPES;
    searchForm = new FormGroup({
        city: new FormControl('', [Validators.required]),
        district: new FormControl('', [Validators.required]),
        lecture_area: new FormControl('', [Validators.required]),
        lecture_theme: new FormControl('', [Validators.required]),
        min_price: new FormControl('', [Validators.min(0), Validators.max(9999)]),
        max_price: new FormControl('', [Validators.min(0), Validators.max(9999)]),
        sex: new FormControl(),
        order: new FormControl()
    });

    constructor(private store: Store<{cities: CityType[], lectures: LectureType[], progress: boolean, user: UserType}>,
                private searchService: SearchService, private bottomSheet: MatBottomSheet) {
        SELECTORS.GENDERS.forEach(gender => { this.gendersMap[gender.value] = gender.name; });
        SELECTORS.ORDERS.forEach(gender => { this.ordersMap[gender.value] = gender.name; });
    }

    async ngOnInit() {
        await this.getUser();
        await this.getCities();
        await this.getLectures();
        await this.search(true);
    }

    async getCities() {
        this.cities = await this.store.select('cities').pipe(first()).toPromise();
        if (this.cities) {
            this.searchForm.controls.city.setValue(this.cities[34]);
            this.changeCity();
        }
    }

    async getLectures() {
        this.lectures = await this.store.select('lectures').pipe(first()).toPromise();
        if (this.lectures) {
            this.searchForm.controls.lecture_area.setValue(this.lectures[0]);
            this.changeLectureArea();
        }
    }

    public changeCity() {
        if (this.searchForm.controls.city.valid) {
            this.searchForm.controls.district.setValue(this.searchForm.controls.city.value?.districts[0]);
        }
    }

    public changeLectureArea() {
        if (this.searchForm.controls.lecture_area.valid) {
            this.searchForm.controls.lecture_theme.setValue(this.searchForm.controls.lecture_area.value?.lecture_themes[0]);
        }
    }

    public openAddAnnouncementBottomSheet() {
        this.bottomSheet.open(AddAnnouncementComponentBottomSheet, {data: this.setSearchRequestParams()});
    }

    public search = async (changeMode?: boolean) => {
        this.store.select(loading);
        const result = await this.searchService.get(this.setSearchRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.searchResult = response.data;
            this.changeMode = changeMode;
        });
        this.store.select(loaded);
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private setSearchRequestParams() {
        const form = this.searchForm.controls;
        return {
            'search_type': TYPES.TUTOR,
            'city': form.city.value?.city_name,
            'district': form.district.value?.toLowerCase() !== 'hepsi' ? form.district.value : null,
            'lecture_area': form.lecture_area.value?.lecture_area,
            'lecture_theme': form.lecture_theme.value?.lecture_theme.toLowerCase() !== 'tüm konular' ?
                form.lecture_theme.value?.lecture_theme : null,
            'min_price': form.min_price.value,
            'max_price': form.max_price.value,
            'sex': form.sex.value,
            'order': form.order.value
        }
    }

}
