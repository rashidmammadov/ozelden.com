import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { first } from 'rxjs/operators';
import { LectureType } from '../../interfaces/lecture-type';
import { TutorLectureType } from '../../interfaces/tutor-lecture-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { LectureService } from '../../services/lecture/lecture.service';
import { UtilityService } from '../../services/utility/utility.service';
import { loaded, loading } from '../../store/actions/progress.action';

@Component({
    selector: 'app-lectures',
    templateUrl: './lectures.component.html',
    styleUrls: ['./lectures.component.scss']
})
export class LecturesComponent implements OnInit {

    experience: number;
    price: number;
    lectures: LectureType[] = [];
    lectureArea: LectureType;
    lectureTheme: {lecture_theme: string, average_try: number};
    tutorLectures: TutorLectureType[];

    constructor(private store: Store<{lectures: LectureType[], progress: boolean}>, private lectureService: LectureService) { }

    async ngOnInit() {
        await this.getLectures();
        await this.getTutorLectures();
    }

    async addLecture() {
        this.store.select(loading);
        const params = {
            lecture_area: this.lectureArea.lecture_area,
            lecture_theme: this.lectureTheme.lecture_theme,
            experience: this.experience,
            price: this.price
        };
        const result = await this.lectureService.addTutorLecture(params);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.tutorLectures.push(response.data);
        });
        this.store.select(loaded);
    }

    async getLectures() {
        this.lectures = await this.store.select('lectures').pipe(first()).toPromise();
        if (this.lectures) {
            this.lectureArea = this.lectures[0];
            this.changeLectureArea();
        }
    }

    async getTutorLectures() {
        this.store.select(loading);
        const result = await this.lectureService.getTutorLectures();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.tutorLectures = response.data;
        });
        this.store.select(loaded);
    }

    public changeLectureArea() {
        if (this.lectureArea) {
            this.lectureTheme = this.lectureArea.lecture_themes[1];
        }
    }

}
