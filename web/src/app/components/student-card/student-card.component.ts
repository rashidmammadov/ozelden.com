import { Component, Input, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { EditStudentDialogComponent } from '../dialogs/edit-student-dialog/edit-student-dialog.component';
import { UtilityService } from '../../services/utility/utility.service';
import { StudentType } from '../../interfaces/student-type';
import { DATE_TIME } from '../../constants/date-time.constant';

@Component({
    selector: 'app-student-card',
    templateUrl: './student-card.component.html',
    styleUrls: ['./student-card.component.scss']
})
export class StudentCardComponent implements OnInit {
    @Input() public data: StudentType;

    constructor(private dialog: MatDialog) { }

    ngOnInit(): void {
        this.data && this.data.birthday && (this.data.age = UtilityService.millisecondsToDate(this.data.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
    }

    public openEditDialog() {
      this.dialog
          .open(EditStudentDialogComponent, { width: '500px', disableClose: true, data: this.data})
          .afterClosed().toPromise()
          .then(result => {
              // !!result && this.addNewStudent(result);
          });
    }

}
