import { Component, HostListener, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatBottomSheet } from '@angular/material/bottom-sheet';
import { Store } from '@ngrx/store';
import { UtilityService } from '../../services/utility/utility.service';
import { ReportService } from '../../services/report/report.service';
import { SearchService } from '../../services/search/search.service';
import { AddAnnouncementComponentBottomSheet } from '../sheets/add-announcement-bottom-sheet/add-announcement-component-bottom-sheet.component';
import { CityType } from '../../interfaces/city-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { InfoType } from '../../interfaces/info-type';
import { OverallReportType } from '../../interfaces/overall-report-type';
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
    searchResult: InfoType[] = [];
    recommends: InfoType[] = [];
    reports: OverallReportType = <OverallReportType>{};
    genders = SELECTORS.GENDERS;
    orders = SELECTORS.ORDERS;
    gendersMap = {};
    ordersMap = {};
    changeMode: boolean = true;
    user: UserType;
    maxPosition = 0;
    page: number = 1;
    loaded: number = 0;
    total: number = 0;
    total_page: number = 0;
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
                private searchService: SearchService, private bottomSheet: MatBottomSheet,
                private reportService: ReportService) {
        SELECTORS.GENDERS.forEach(gender => { this.gendersMap[gender.value] = gender.name; });
        SELECTORS.ORDERS.forEach(gender => { this.ordersMap[gender.value] = gender.name; });
    }

    async ngOnInit() {
        await this.getUser();
        await this.getCities();
        await this.getLectures();
        await this.search(true, true);
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

    public search = async (changeMode: boolean, clear?: boolean) => {
        if (clear) {
            this.page = 1;
            this.searchResult = [];
        }
        this.store.select(loading);
        const result = await this.searchService.get(this.page, this.setSearchRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.changeMode = changeMode;
            this.page = response.current_page;
            this.loaded = (response.current_page * response.limit) <= response.total_data ?
                (response.current_page * response.limit) : response.total_data;
            this.total = response.total_data;
            this.total_page = response.total_page;
            if (response.data && response.data.length) {
                this.page === 1 ? (this.searchResult = response.data) : (this.searchResult = this.searchResult.concat(response.data));
            }
        });
        this.store.select(loaded);
        if (clear) {
            this.fetchRecommended();
            this.fetchReports();
        }
    };

    private fetchReports = async () => {
        const result = await this.reportService.get(null, this.setReportRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.reports.total_count = response.data.total_count || 0;
            this.reports.average_price = response.data.average_price || 0;
            this.reports.gender_distribution = response.data.gender_distribution || [];
            this.reports.price_distribution = response.data.price_distribution || [];
        });
    };

    private fetchRecommended = async () => {
        const result = await this.searchService.recommended(this.setRecommendRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.recommends = response.data;
        });
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private setRecommendRequestParams() {
        const form = this.searchForm.controls;
        return {
            'city': form.city.value?.city_name
        }
    }

    private setReportRequestParams() {
        const form = this.searchForm.controls;
        return {
            'city': form.city.value?.city_name,
            'district': form.district.value?.toLowerCase() !== 'hepsi' ? form.district.value : null,
            'lecture_area': form.lecture_area.value?.lecture_area,
            'lecture_theme': form.lecture_theme.value?.lecture_theme.toLowerCase() !== 'tüm konular' ?
                form.lecture_theme.value?.lecture_theme : null
        }
    }

    private setSearchRequestParams() {
        const form = this.searchForm.controls;
        return {
            'search_type': this.user && this.user.type === TYPES.TUTOR ? TYPES.TUTORED : TYPES.TUTOR,
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

    private async loadMore(maxPosition: number, customHeight: number = 128) {
        const pos = (document.documentElement.scrollTop || document.body.scrollTop) + document.documentElement.offsetHeight;
        pos > maxPosition && (maxPosition = pos);
        const max = document.documentElement.scrollHeight;
        if ((maxPosition + customHeight) > max) {
            this.page++;
            await this.search(this.changeMode);
        }
    }

    @HostListener('window:scroll', ['$event'])
    async onWindowScroll() {
        const onProgress = await this.store.select('progress').pipe(first()).toPromise();
        !onProgress && (this.loaded < this.total) && (this.page < this.total_page) && this.loadMore(this.maxPosition);
    }

}
