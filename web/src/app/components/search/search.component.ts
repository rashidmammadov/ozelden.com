import { Component, HostListener, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { GoogleAnalyticsService} from '../../services/google-analytics/google-analytics.service';
import { SearchService } from '../../services/search/search.service';
import { UtilityService } from '../../services/utility/utility.service';
import { MetaService } from '../../services/meta/meta.service';
import { CityType } from '../../interfaces/city-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { InfoType } from '../../interfaces/info-type';
import { LectureType } from '../../interfaces/lecture-type';
import { first } from 'rxjs/operators';
import { TYPES } from '../../constants/types.constant';
import { loaded, loading} from '../../store/actions/progress.action';

@Component({
    selector: 'app-search',
    templateUrl: './search.component.html',
    styleUrls: ['./search.component.scss']
})
export class SearchComponent implements OnInit {

    cities: CityType[] = [];
    lectures: LectureType[] = [];
    searchResult: InfoType[] = [];
    maxPosition = 0;
    page: number = 1;
    loaded: number = 0;
    total: number = 0;
    total_page: number = 0;
    searchForm = new FormGroup({
        city: new FormControl('', [Validators.required]),
        lecture_area: new FormControl('', [Validators.required]),
        lecture_theme: new FormControl('', [Validators.required])
    });

    constructor(private store: Store<{cities: CityType[], lectures: LectureType[], progress: boolean}>,
                private activatedRoute: ActivatedRoute, private metaService: MetaService,
                private searchService: SearchService) { }

    async ngOnInit() {
        await this.getCities();
        this.setLectures();
        await this.search(true);
    }

    public search = async (clear?: boolean) => {
        if (clear) {
            this.page = 1;
            this.searchResult = [];
            this.updateMetaTag();
        }
        this.store.dispatch(loading());
        GoogleAnalyticsService.search(this.setSearchRequestParams());
        const result = await this.searchService.get(this.page, this.setSearchRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.page = response.current_page;
            this.loaded = (response.current_page * response.limit) <= response.total_data ?
                (response.current_page * response.limit) : response.total_data;
            this.total = response.total_data;
            this.total_page = response.total_page;
            if (response.data && response.data.length) {
                this.page === 1 ? (this.searchResult = response.data) : (this.searchResult = this.searchResult.concat(response.data));
            }
        });
        this.store.dispatch(loaded());
    };

    async getCities() {
        this.cities = await this.store.select('cities').pipe(first()).toPromise();
        if (this.cities) {
            this.searchForm.controls.city.setValue(this.cities[33]);
        }
    }

    private setLectures() {
        if (this.activatedRoute && this.activatedRoute.snapshot && this.activatedRoute.snapshot.params) {
            const params = this.activatedRoute.snapshot.params;
            this.searchForm.controls.lecture_area.setValue(params.lecture_area);
            this.searchForm.controls.lecture_theme.setValue(params.lecture_theme);
        }
    }

    private setSearchRequestParams() {
        const controls = this.searchForm.controls;
        return {
            'search_type': TYPES.TUTOR,
            'city': controls.city.value?.city_name,
            'lecture_area': controls.lecture_area.value,
            'lecture_theme': controls.lecture_theme.value?.toLowerCase() !== 'tüm konular' ? controls.lecture_theme.value : null
        }
    }

    private updateMetaTag() {
        const controls = this.searchForm.controls;
        const title = `${controls.city.value.city_name} ${controls.lecture_area.value || ''} ${controls.lecture_theme.value || ''} özel ders verenler`;
        this.metaService.updateOgMetaTags(title);
    }

    private async loadMore(maxPosition: number, customHeight: number = 128) {
        const pos = (document.documentElement.scrollTop || document.body.scrollTop) + document.documentElement.offsetHeight;
        pos > maxPosition && (maxPosition = pos);
        const max = document.documentElement.scrollHeight;
        if ((maxPosition + customHeight) > max) {
            this.page++;
            await this.search(false);
        }
    }

    @HostListener('window:scroll', ['$event'])
    async onWindowScroll() {
        const onProgress = await this.store.select('progress').pipe(first()).toPromise();
        !onProgress && (this.loaded < this.total) && (this.page < this.total_page) && this.loadMore(this.maxPosition);
    }

}
