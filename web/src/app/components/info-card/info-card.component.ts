import { Component, Input, OnChanges, OnInit, SimpleChanges } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { AskOfferDialogComponent } from '../dialogs/ask-offer-dialog/ask-offer-dialog.component';
import { MakeOfferDialogComponent } from '../dialogs/make-offer-dialog/make-offer-dialog.component';
import { Store } from '@ngrx/store';
import { UtilityService } from '../../services/utility/utility.service';
import { InfoType } from '../../interfaces/info-type';
import { UserType } from '../../interfaces/user-type';
import { DATE_TIME } from '../../constants/date-time.constant';
import { first } from 'rxjs/operators';
import { TYPES } from '../../constants/types.constant';

@Component({
    selector: 'app-info-card',
    templateUrl: './info-card.component.html',
    styleUrls: ['./info-card.component.scss']
})
export class InfoCardComponent implements OnChanges {

    @Input() public data: InfoType;
    user: UserType;
    TYPES = TYPES;

    constructor(private dialog: MatDialog, private store: Store<{user: UserType}>) { }

    async ngOnChanges(changes: SimpleChanges) {
        if (changes.data && changes.data.currentValue) {
            await this.getUser();
            if (this.data) {
                this.data.birthday &&
                    (this.data.age = UtilityService.millisecondsToDate(this.data.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
                this.data.student && this.data.student.birthday &&
                    (this.data.student.age = UtilityService.millisecondsToDate(this.data.student.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
            }
        }
    }

    public openAskOfferDialog() {
        this.dialog.open(AskOfferDialogComponent, {
            width: '500px',
            disableClose: true,
            data: this.data
        });
    }

    public openMakeOfferDialog() {
        this.dialog.open(MakeOfferDialogComponent, {
            width: '500px',
            disableClose: true,
            data: this.data
        });
    }

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

}
