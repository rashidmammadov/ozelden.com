import { Component, Inject, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { UtilityService } from '../../../services/utility/utility.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { StudentType } from '../../../interfaces/student-type';

@Component({
    selector: 'app-edit-student-dialog',
    templateUrl: './edit-student-dialog.component.html',
    styleUrls: ['./edit-student-dialog.component.scss']
})
export class EditStudentDialogComponent implements OnInit {

    public pictureChanged: boolean = false;

    public studentForm = new FormGroup({
        picture: new FormControl(''),
        name: new FormControl('', [Validators.required]),
        surname: new FormControl('', [Validators.required]),
        sex: new FormControl('', [Validators.required]),
        birthday: new FormControl('', [Validators.required]),
    });

    constructor(public dialogRef: MatDialogRef<EditStudentDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: StudentType) { }

    ngOnInit(): void {
        let controls = this.studentForm.controls;
        if (this.data) {
            controls.picture.setValue(this.data.picture);
            controls.name.setValue(this.data.name);
            controls.surname.setValue(this.data.surname);
            controls.sex.setValue(this.data.sex);
            controls.birthday.setValue(this.data.birthday);
        }
    }

    onClose() {
        this.dialogRef.close(this.studentFormResult());
    }

    setBirthday(date) {
        if (date) {
            this.studentForm.controls.birthday.setValue(date);
        }
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
