import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { EditStudentDialogComponent } from '../dialogs/edit-student-dialog/edit-student-dialog.component';
import { StudentService } from '../../services/student/student.service';
import { ToastService } from '../../services/toast/toast.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { StudentType } from '../../interfaces/student-type';
import { loaded, loading } from '../../store/actions/progress.action';

@Component({
    selector: 'app-tutored-students',
    templateUrl: './tutored-students.component.html',
    styleUrls: ['./tutored-students.component.scss']
})
export class TutoredStudentsComponent implements OnInit {

    students: StudentType[] = [];

    constructor(private studentService: StudentService, private dialog: MatDialog,
                private store: Store<{progress: boolean}>) { }

    async ngOnInit() {
        await this.fetchStudents();
    }

    public openNewStudentDialog() {
        this.dialog
            .open(EditStudentDialogComponent, { width: '500px', disableClose: true})
            .afterClosed().toPromise()
            .then(result => {
                !!result && this.addNewStudent(result);
            });
    }

    private addNewStudent = async (student: StudentType) => {
        this.store.select(loading);
        const result = await this.studentService.addTutoredStudent(student);
        let students = [...this.students];
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            students.push(response.data);
            ToastService.show(response.message);
        });
        this.students = students;
        this.store.select(loaded);
    };

    private fetchStudents = async () => {
        this.store.select(loading);
        const result = await this.studentService.getTutoredStudents();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.students = response.data;
        });
        this.store.select(loaded);
    };

}
