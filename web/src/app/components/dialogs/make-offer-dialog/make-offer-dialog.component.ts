import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Store } from '@ngrx/store';
import { GoogleAnalyticsService } from '../../../services/google-analytics/google-analytics.service';
import { OfferService } from '../../../services/offer/offer.service';
import { LectureService } from '../../../services/lecture/lecture.service';
import { PaidService } from '../../../services/paid/paid.service';
import { UtilityService } from '../../../services/utility/utility.service';
import { ToastService } from '../../../services/toast/toast.service';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { InfoType } from '../../../interfaces/info-type';
import { TutorLectureType } from '../../../interfaces/tutor-lecture-type';
import { loaded, loading } from '../../../store/actions/progress.action';

@Component({
    selector: 'app-make-offer-dialog',
    templateUrl: './make-offer-dialog.component.html',
    styleUrls: ['./make-offer-dialog.component.scss']
})
export class MakeOfferDialogComponent implements OnInit {

    lectures: TutorLectureType[];
    remainingBids: number = 0;
    offerForm = new FormGroup({
        student_id: new FormControl(''),
        receiver_id: new FormControl('', [Validators.required]),
        tutor_lecture_id: new FormControl('', [Validators.required]),
        offer: new FormControl('', [Validators.required]),
        description: new FormControl()
    });

    constructor(public dialogRef: MatDialogRef<MakeOfferDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: InfoType,
                private lectureService: LectureService, private paidService: PaidService,
                private store: Store<{progress: boolean}>, private offerService: OfferService) { }

    async ngOnInit() {
        await this.fetchLectures();
        await this.fetchPaidServices();
    }

    sendOffer = async () => {
        this.store.dispatch(loading());
        GoogleAnalyticsService.sendOffer(this.setOfferRequestParams());
        const result = await this.offerService.send(this.setOfferRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            this.dialogRef.close(true);
        });
        this.store.dispatch(loaded());
    };

    setOffer(lecture: TutorLectureType) {
        if (lecture) {
            this.offerForm.controls.offer.setValue(lecture.price);
        }
    }

    private fetchLectures = async () => {
        this.store.dispatch(loading());
        const result = await this.lectureService.getTutorLectures();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.lectures = response.data;
            this.prepareForm();
        });
        this.store.dispatch(loaded());
    };

    private fetchPaidServices = async () => {
        this.store.dispatch(loading());
        const result = await this.paidService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            if (response.data && response.data.bid) {
                this.remainingBids = response.data.bid;
            }
        });
        this.store.dispatch(loaded());
    };

    private prepareForm() {
        if (this.data) {
            this.offerForm.controls.student_id.setValue(this.data.student ? this.data.student.student_id : null);
            this.offerForm.controls.receiver_id.setValue(this.data.id);
            this.offerForm.controls.tutor_lecture_id.setValue(this.lectures[0]?.tutor_lecture_id);
            this.setOffer(this.lectures[0]);
        }
    }

    private setOfferRequestParams() {
        const controls = this.offerForm.controls;
        return {
            'receiver_id': controls.receiver_id.value,
            'student_id': controls.student_id.value,
            'tutor_lecture_id': controls.tutor_lecture_id.value,
            'offer': controls.offer.value,
            'description': controls.description.value
        }
    }

}
