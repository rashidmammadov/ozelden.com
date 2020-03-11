import { Component, Input, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { AskOfferDialogComponent } from '../dialogs/ask-offer-dialog/ask-offer-dialog.component';
import { UtilityService } from '../../services/utility/utility.service';
import { InfoType } from '../../interfaces/info-type';
import { DATE_TIME } from '../../constants/date-time.constant';

@Component({
    selector: 'app-info-card',
    templateUrl: './info-card.component.html',
    styleUrls: ['./info-card.component.scss']
})
export class InfoCardComponent implements OnInit {

    @Input() public data: InfoType;

    constructor(private dialog: MatDialog) { }

    ngOnInit(): void {
        this.data && this.data.birthday &&
            (this.data.age = UtilityService.millisecondsToDate(this.data.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
    }

    public openAskOfferDialog() {
        this.dialog.open(AskOfferDialogComponent, {
            width: '500px',
            disableClose: true,
            data: this.data
        });
    }

}
