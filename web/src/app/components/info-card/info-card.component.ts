import { Component, Input, OnInit } from '@angular/core';
import { InfoType } from '../../interfaces/info-type';
import { UtilityService } from '../../services/utility/utility.service';
import { DATE_TIME } from '../../constants/date-time.constant';

@Component({
    selector: 'app-info-card',
    templateUrl: './info-card.component.html',
    styleUrls: ['./info-card.component.scss']
})
export class InfoCardComponent implements OnInit {

    @Input() public data: InfoType;

    constructor() { }

    ngOnInit(): void {
        this.data.birthday && (this.data.age = UtilityService.millisecondsToDate(this.data.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
    }

}
