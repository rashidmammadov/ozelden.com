import { Component, Inject, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { UtilityService } from '../../../services/utility/utility.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { StudentType } from '../../../interfaces/student-type';
import { DATE_TIME } from '../../../constants/date-time.constant';

@Component({
    selector: 'app-edit-student-dialog',
    templateUrl: './edit-student-dialog.component.html',
    styleUrls: ['./edit-student-dialog.component.scss']
})
export class EditStudentDialogComponent implements OnInit {

    public currentDate = new Date();
    public days: number[] = [];
    public months: number[] = [];
    public years: number[] = [];
    public selectedDay: number = 1;
    public selectedMonth: number = 1;
    public selectedYear: number = this.currentDate.getFullYear() - 18;
    public pictureChanged: boolean = false;
    public MONTHS_MAP = DATE_TIME.MONTHS_MAP;

    public studentForm = new FormGroup({
        picture: new FormControl(''),
        name: new FormControl('', [Validators.required]),
        surname: new FormControl('', [Validators.required]),
        sex: new FormControl('', [Validators.required]),
        birthday: new FormControl('', [Validators.required]),
    });

    constructor(public dialogRef: MatDialogRef<EditStudentDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: StudentType) {
        for (let i = 1; i <= 31; i++) this.days.push(i);
        for (let i = 1; i <= 12; i++) this.months.push(i);
        for (let i = this.currentDate.getFullYear(); i >= this.currentDate.getFullYear() - 70; i--) this.years.push(i);
    }

    ngOnInit(): void {
        let controls = this.studentForm.controls;
        if (this.data) {
            controls.picture.setValue(this.data.picture);
            controls.name.setValue(this.data.name);
            controls.surname.setValue(this.data.surname);
            controls.sex.setValue(this.data.sex);
            controls.birthday.setValue(this.data.birthday);
            const bd = new Date(Number(this.data.birthday));
            this.selectedDay = bd.getDate();
            this.selectedMonth = bd.getMonth() + 1;
            this.selectedYear = bd.getFullYear();
        }
    }

    onClose() {
        this.dialogRef.close(this.studentFormResult());
    }

    public setBirthday() {
        const birthday = new Date(this.selectedYear, this.selectedMonth - 1, this.selectedDay);
        this.studentForm.controls.birthday.setValue(birthday.getTime().toString());
    }

    public setPicture(value: File) {
        this.studentForm.controls.picture.setValue(value);
        this.pictureChanged = true;
    }

    private studentFormResult() {
        const form = this.studentForm.controls;
        return {
            'student_id': this.data?.student_id,
            'name': form.name.value,
            'surname': form.surname.value,
            'sex': form.sex.value,
            'birthday': form.birthday.value,
            'file': form.picture.value?.includes('base64') && this.pictureChanged ?
                  UtilityService.parseBase64(form.picture.value, 'image') : null
        } as StudentType;
    }

}
