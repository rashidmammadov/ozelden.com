import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { StudentService } from '../../services/student/student.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { StudentType } from '../../interfaces/student-type';
import { UserType } from '../../interfaces/user-type';
import { first } from 'rxjs/operators';
import { loaded, loading } from '../../store/actions/progress.action';

@Component({
    selector: 'app-tutor-students',
    templateUrl: './tutor-students.component.html',
    styleUrls: ['./tutor-students.component.scss']
})
export class TutorStudentsComponent implements OnInit {

    students: StudentType[] = [];

    constructor(private studentService: StudentService, private store: Store<{progress: boolean}>) { }

    async ngOnInit() {
        await this.fetchStudents();
    }

    private fetchStudents = async () => {
        this.store.dispatch(loading());
        const result = await this.studentService.getTutorStudents();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.students = response.data;
        });
        this.store.dispatch(loaded());
    };

}
