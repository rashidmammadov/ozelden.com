import { Component, OnDestroy, OnInit } from '@angular/core';
import { UtilityService } from '../../services/utility/utility.service';
import { SearchService } from '../../services/search/search.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { InfoType } from '../../interfaces/info-type';

@Component({
    selector: 'app-landing',
    templateUrl: './landing.component.html',
    styleUrls: ['./landing.component.scss']
})
export class LandingComponent implements OnInit, OnDestroy {

    lectures: string[];
    index: number = 0;
    interval;
    recommends: InfoType[] = [];

    constructor(private searchService: SearchService) { }

    async ngOnInit() {
        this.lectures = ['Bilgisayar', 'Matematik', 'Yabancı Dil', 'Sanat', 'Türkçe', 'Spor'];
        this.interval = setInterval(() => this.slideLectures(), 2000);
        await this.fetchRecommended();
    }

    ngOnDestroy(): void {
        clearInterval(this.interval);
    }

    private fetchRecommended = async () => {
        const result = await this.searchService.recommended();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.recommends = response.data;
        });
    };

    private slideLectures() {
        document.getElementsByClassName('lecture')[0].classList.remove('slide-in');
        document.getElementsByClassName('lecture')[0].classList.add('slide-out');
        this.index === (this.lectures.length - 1) ? (this.index = 0) : this.index++;
        setTimeout(() => {
            document.getElementsByClassName('lecture')[0].classList.remove('slide-out');
            document.getElementsByClassName('lecture')[0].innerHTML = this.lectures[this.index];
            document.getElementsByClassName('lecture')[0].classList.add('slide-in');
        }, 300);
    }

}
