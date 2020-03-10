import { Component, Input, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { MatDialog } from '@angular/material/dialog';
import { ConfirmDialogComponent } from '../dialogs/confirm-dialog/confirm-dialog.component';
import { EditStudentDialogComponent } from '../dialogs/edit-student-dialog/edit-student-dialog.component';
import { StudentService } from '../../services/student/student.service';
import { ToastService } from '../../services/toast/toast.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ConfirmDialogType } from '../../interfaces/confirm-dialog-type';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { StudentType } from '../../interfaces/student-type';
import { loaded, loading } from '../../store/actions/progress.action';
import { DATE_TIME } from '../../constants/date-time.constant';

@Component({
    selector: 'app-student-card',
    templateUrl: './student-card.component.html',
    styleUrls: ['./student-card.component.scss']
})
export class StudentCardComponent implements OnInit {
    @Input() public data: StudentType;

    constructor(private dialog: MatDialog, private store: Store<{progress: boolean}>,
                private studentService: StudentService) { }

    ngOnInit(): void {
        this.data && this.data.birthday && (this.data.age = UtilityService.millisecondsToDate(this.data.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
    }

    public openDeleteDialog() {
        const dialogData: ConfirmDialogType = {
            message: `${this.data.name}  ${this.data.surname} isimli öğrenci listeden kaldırılacak`,
            title: 'Öğrenci Silme'
        };
        this.dialog
            .open(ConfirmDialogComponent, { width: '500px', disableClose: true, data: dialogData })
            .afterClosed().toPromise()
            .then(result => {
                !!result && this.deleteStudent();
            });
    }

    public openEditDialog() {
        this.dialog
            .open(EditStudentDialogComponent, { width: '500px', disableClose: true, data: this.data })
            .afterClosed().toPromise()
            .then(result => {
                !!result && this.updateStudent(result);
            });
    }

    private deleteStudent = async () => {
        this.store.select(loading);
        const result = await this.studentService.deleteTutoredStudent(this.data.student_id);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.data = null;
            ToastService.show(response.message);
        });
        this.store.select(loaded);
    };

    private updateStudent = async (student: StudentType) => {
        this.store.select(loading);
        const result = await this.studentService.updateTutoredStudent(student);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            student.file && student.file.base64 && (this.data.picture = student.file.base64);
            this.data.name = student.name;
            this.data.surname = student.surname;
            this.data.sex = student.sex;
            this.data.birthday = student.birthday;
            this.data.age = UtilityService.millisecondsToDate(student.birthday, DATE_TIME.FORMAT.TOTAL_YEARS);
            ToastService.show(response.message);
        });
        this.store.select(loaded);
    };

}
