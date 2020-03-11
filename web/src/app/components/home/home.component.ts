import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { UtilityService } from '../../services/utility/utility.service';
import { SearchService } from '../../services/search/search.service';
import { CityType } from '../../interfaces/city-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { InfoType } from '../../interfaces/info-type';
import { LectureType } from '../../interfaces/lecture-type';
import { first } from 'rxjs/operators';
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
    searchForm = new FormGroup({
        city: new FormControl('', [Validators.required]),
        district: new FormControl('', [Validators.required]),
        lecture_area: new FormControl('', [Validators.required]),
        lecture_theme: new FormControl('', [Validators.required])
    });

    constructor(private store: Store<{cities: CityType[], lectures: LectureType[], progress: boolean}>,
                private searchService: SearchService) { }

    async ngOnInit() {
        await this.getCities();
        await this.getLectures();
        await this.search();
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

    public search = async () => {
        this.store.select(loading);
        const result = await this.searchService.get(this.setSearchRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.searchResult = response.data;
        });
        this.store.select(loaded);
    };

    private setSearchRequestParams() {
        const form = this.searchForm.controls;
        return {
            'city': form.city.value?.city_name,
            'district': form.district.value?.toLowerCase() !== 'hepsi' ? form.district.value : null,
            'lecture_area': form.lecture_area.value?.lecture_area,
            'lecture_theme': form.lecture_theme.value?.lecture_theme.toLowerCase() !== 'tüm konular' ?
                form.lecture_theme.value?.lecture_theme : null
        }
    }

}
