import { Component, Inject, OnInit } from '@angular/core';
import {MAT_BOTTOM_SHEET_DATA, MatBottomSheetRef} from '@angular/material/bottom-sheet';
import { Store } from '@ngrx/store';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { AnnouncementService } from '../../../services/announcement/announcement.service';
import { StudentService } from '../../../services/student/student.service';
import { UtilityService } from '../../../services/utility/utility.service';
import { ToastService } from '../../../services/toast/toast.service';
import { AnnouncementType } from '../../../interfaces/announcement-type';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { StudentType } from '../../../interfaces/student-type';
import { UserType } from '../../../interfaces/user-type';
import { first } from 'rxjs/operators';
import { loaded, loading } from '../../../store/actions/progress.action';
import { SELECTORS } from '../../../constants/selectors.constant';

@Component({
    selector: 'app-add-announcement-bottom-sheet',
    templateUrl: './add-announcement-component-bottom-sheet.component.html',
    styleUrls: ['./add-announcement-component-bottom-sheet.component.scss']
})
export class AddAnnouncementComponentBottomSheet implements OnInit {

    user: UserType;
    gendersMap = {};
    students: StudentType[] = [];
    studentSelected: boolean = false;
    announcementForm = new FormGroup({
        student_id: new FormControl(''),
        lecture_area: new FormControl('', Validators.required),
        lecture_theme: new FormControl(''),
        city: new FormControl('', Validators.required),
        district: new FormControl(''),
        min_price: new FormControl(''),
        max_price: new FormControl(''),
        sex: new FormControl('')
    });

    constructor(@Inject(MAT_BOTTOM_SHEET_DATA) public data: AnnouncementType,
                private bottomSheetRef: MatBottomSheetRef,
                private store: Store<{user: UserType, progress: boolean}>,
                private studentService: StudentService, private announcementService: AnnouncementService) {
        SELECTORS.GENDERS.forEach(gender => { this.gendersMap[gender.value] = gender.name; });
    }

    async ngOnInit() {
        if (this.data) {
            let controls = this.announcementForm.controls;
            controls.lecture_area.setValue(this.data.lecture_area);
            controls.lecture_theme.setValue(this.data.lecture_theme);
            controls.city.setValue(this.data.city);
            controls.district.setValue(this.data.district);
            controls.min_price.setValue(this.data.min_price);
            controls.max_price.setValue(this.data.max_price);
            controls.sex.setValue(this.data.sex);
        }
        await this.getUser();
        await this.fetchStudents();
    }

    addAnnouncement = async () => {
        this.store.select(loading);
        const result = await this.announcementService.send(this.setAnnouncementRequestParams());
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            this.close(undefined as MouseEvent);
        });
        this.store.select(loaded);
    };

    close(event: MouseEvent): void {
        this.bottomSheetRef.dismiss();
        event.preventDefault();
    }

    setStudentSelected(selected?: boolean) {
        this.studentSelected = !!selected;
    }

    private fetchStudents = async () => {
        this.store.select(loading);
        const result = await this.studentService.getTutoredStudents();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.students = response.data;
            this.announcementForm.controls.student_id.setValue(this.students.length ? this.students[0].student_id : this.user.id);
            this.setStudentSelected(!!this.students.length);
        });
        this.store.select(loaded);
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private setAnnouncementRequestParams() {
        const controls = this.announcementForm.controls;
        return {
            'student_id': this.studentSelected ? controls.student_id.value : null,
            'city': controls.city.value,
            'district': controls.district.value,
            'lecture_area': controls.lecture_area.value,
            'lecture_theme': controls.lecture_theme.value,
            'min_price': controls.min_price.value,
            'max_price': controls.max_price.value,
            'sex': controls.sex.value
        }
    }
}
