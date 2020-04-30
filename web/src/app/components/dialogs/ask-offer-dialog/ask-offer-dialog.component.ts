import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { StudentService } from '../../../services/student/student.service';
import { OfferService } from '../../../services/offer/offer.service';
import { UtilityService } from '../../../services/utility/utility.service';
import { ToastService } from '../../../services/toast/toast.service';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { InfoType } from '../../../interfaces/info-type';
import { StudentType } from '../../../interfaces/student-type';
import { TutorLectureType } from '../../../interfaces/tutor-lecture-type';
import { UserType } from '../../../interfaces/user-type';
import { loaded, loading } from '../../../store/actions/progress.action';
import { first } from 'rxjs/operators';
import {GoogleAnalyticsService} from "../../../services/google-analytics/google-analytics.service";

@Component({
    selector: 'app-ask-offer-dialog',
    templateUrl: './ask-offer-dialog.component.html',
    styleUrls: ['./ask-offer-dialog.component.scss']
})
export class AskOfferDialogComponent implements OnInit {

    user: UserType;
    students: StudentType[] = [];
    studentSelected: boolean = false;
    offerForm = new FormGroup({
        student_id: new FormControl(''),
        receiver_id: new FormControl('', [Validators.required]),
        tutor_lecture_id: new FormControl('', [Validators.required]),
        offer: new FormControl('', [Validators.required]),
        description: new FormControl()
    });

    constructor(public dialogRef: MatDialogRef<AskOfferDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: InfoType,
                private studentService: StudentService, private offerService: OfferService,
                private store: Store<{progress: boolean, user: UserType}>) { }

    async ngOnInit() {
        await this.getUser();
        await this.fetchStudents();
        this.prepareForm();
    }

    askOffer = async () => {
        this.store.dispatch(loading());
        GoogleAnalyticsService.askOffer(this.setOfferRequestParams());
        const result = await this.offerService.send(this.setOfferRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            this.dialogRef.close(true);
        });
        this.store.dispatch(loaded());
    };

    setStudentSelected(selected?: boolean) {
        this.studentSelected = !!selected;
    }

    setOffer(lecture: TutorLectureType) {
        if (lecture) {
            this.offerForm.controls.offer.setValue(lecture.price);
        }
    }

    private fetchStudents = async () => {
        this.store.dispatch(loading());
        const result = await this.studentService.getTutoredStudents();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.students = response.data;
        });
        this.store.dispatch(loaded());
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private prepareForm() {
        if (this.data) {
            this.offerForm.controls.student_id.setValue(this.students.length ? this.students[0].student_id : this.user.id);
            this.offerForm.controls.receiver_id.setValue(this.data.id);
            this.offerForm.controls.tutor_lecture_id.setValue(this.data.lectures[0]?.tutor_lecture_id);
            this.setStudentSelected(!!this.students.length);
            this.setOffer(this.data.lectures[0]);
        }
    }

    private setOfferRequestParams() {
        const controls = this.offerForm.controls;
        return {
            'receiver_id': controls.receiver_id.value,
            'student_id': this.studentSelected ? controls.student_id.value : null,
            'tutor_lecture_id': controls.tutor_lecture_id.value,
            'offer': controls.offer.value,
            'description': controls.description.value
        }
    }

}
