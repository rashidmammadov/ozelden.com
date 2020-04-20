import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { first } from 'rxjs/operators';
import { ConfirmDialogComponent } from '../dialogs/confirm-dialog/confirm-dialog.component';
import { ConfirmDialogType } from '../../interfaces/confirm-dialog-type';
import { LectureType } from '../../interfaces/lecture-type';
import { MissingFieldsType } from '../../interfaces/missing-fields-type';
import { TableColumnType } from '../../interfaces/table-column-type';
import { TutorLectureType } from '../../interfaces/tutor-lecture-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { LectureService } from '../../services/lecture/lecture.service';
import { UserService } from '../../services/user/user.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ToastService } from '../../services/toast/toast.service';
import { loaded, loading } from '../../store/actions/progress.action';
import { MESSAGES } from '../../constants/messages.constant';

@Component({
    selector: 'app-lectures',
    templateUrl: './lectures.component.html',
    styleUrls: ['./lectures.component.scss']
})
export class LecturesComponent implements OnInit {

    displayedColumns: TableColumnType[];
    lectures: LectureType[] = [];
    lectureForm = new FormGroup({
        lecture_area: new FormControl('', [Validators.required]),
        lecture_theme: new FormControl('', [Validators.required]),
        experience: new FormControl('', [Validators.required]),
        price: new FormControl('', [Validators.required])
    });
    tutorLectures: TutorLectureType[];
    missingFields: MissingFieldsType;

    constructor(private store: Store<{missingFields: MissingFieldsType, lectures: LectureType[], progress: boolean}>,
                private lectureService: LectureService, private userService: UserService,
                private dialog: MatDialog) { }

    async ngOnInit() {
        this.displayedColumns = [{
            header: 'Ders Alanı',
            value: 'lecture_area'
        }, {
            header: 'Ders Konusu',
            value: 'lecture_theme'
        }, {
            header: 'Tecrübe',
            value: 'experience',
            additional: 'yıl'
        }, {
            header: 'Fiyat',
            value: 'price',
            additional: '₺'
        }, {
            header: 'Ortalama Farkı',
            value: 'price_difference',
            icon: 'price_pleasure'
        }, {
            header: 'İşlemler',
            value: 'operations',
            icon_button: 'trash',
            click: (data) => this.deleteLectureConfirmDialog(data)
        }];
        await this.getLectures();
        await this.getTutorLectures();
    }

    async addLecture() {
        if (this.lectureForm.valid) {
            let lecturesList: TutorLectureType[] = [...this.tutorLectures];
            this.store.dispatch(loading());
            const result = await this.lectureService.addTutorLecture(this.setLectureRequestParams());
            UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
                lecturesList.push(response.data);
                this.userService.updateMissingFields('lecture', false);
            });
            this.store.dispatch(loaded());
            this.tutorLectures = lecturesList;
        } else {
            ToastService.show(MESSAGES.ERROR.INVALID_FORM)
        }
    }

    async getLectures() {
        this.lectures = await this.store.select('lectures').pipe(first()).toPromise();
        if (this.lectures) {
            this.lectureForm.controls.lecture_area.setValue(this.lectures[0]);
            this.changeLectureArea();
        }
    }

    async getTutorLectures() {
        this.store.dispatch(loading());
        const result = await this.lectureService.getTutorLectures();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            response.data.forEach((d: TutorLectureType) => {
                d.price_difference = !!d.average_try ? (d.price - d.average_try) : '...';
            });
            this.tutorLectures = response.data;
        });
        this.store.dispatch(loaded());
    }

    public changeLectureArea() {
        if (this.lectureForm.controls.lecture_area.valid) {
            this.lectureForm.controls.lecture_theme.setValue(this.lectureForm.controls.lecture_area.value?.lecture_themes[1]);
        }
    }

    public deleteLectureConfirmDialog(lecture: TutorLectureType) {
        const dialogData: ConfirmDialogType = {
            title: 'Ders Silme',
            message: `Bu işlemi onaylarsanız (${lecture.lecture_area} - ${lecture.lecture_theme}) dersi listenizden kaldırılacak`
        };
        this.dialog
            .open(ConfirmDialogComponent, { width: '500px', data: dialogData })
            .afterClosed().toPromise()
            .then(result => { result && this.deleteLecture(lecture.tutor_lecture_id) });
    }

    public deleteLecture = async (tutor_lecture_id: number) => {
        let lecturesList: TutorLectureType[] = [...this.tutorLectures];
        this.store.dispatch(loading());
        const result = await this.lectureService.deleteTutorLecture(tutor_lecture_id);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            lecturesList = lecturesList.filter(lecture => lecture.tutor_lecture_id !== tutor_lecture_id);
            this.userService.updateMissingFields('lecture', true);
        });
        this.store.dispatch(loaded());
        this.tutorLectures = lecturesList;
    };

    private setLectureRequestParams() {
        const form = this.lectureForm.controls;
        return {
            'lecture_area': form.lecture_area.value?.lecture_area,
            'lecture_theme': form.lecture_theme.value?.lecture_theme,
            'experience': form.experience.value,
            'price': form.price.value
        }
    }

}
