import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { AskOfferDialogComponent } from '../dialogs/ask-offer-dialog/ask-offer-dialog.component';
import { MakeOfferDialogComponent } from '../dialogs/make-offer-dialog/make-offer-dialog.component';
import { ActivatedRoute } from '@angular/router';
import { UtilityService } from '../../services/utility/utility.service';
import { first } from 'rxjs/operators';
import { UserProfileType } from '../../interfaces/user-profile-type';
import { UserType } from '../../interfaces/user-type';
import { DATE_TIME } from '../../constants/date-time.constant';
import { TYPES } from '../../constants/types.constant';
import {InfoType} from "../../interfaces/info-type";

@Component({
    selector: 'app-profile',
    templateUrl: './profile.component.html',
    styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

    profile: UserProfileType;
    course_types: [];
    facilities: [];
    locations: [];
    user: UserType;
    TYPES = TYPES;

    constructor(private store: Store<{progress: boolean, user: UserType}>, private activatedRoute: ActivatedRoute,
                private dialog: MatDialog) { }

    async ngOnInit() {
        await this.getUser();
        await this.fetchProfile();
    }

    public openAskOfferDialog() {
        this.dialog.open(AskOfferDialogComponent, {
            width: '500px',
            disableClose: true,
            data: this.prepareOfferData()
        });
    }

    public openMakeOfferDialog() {
        this.dialog.open(MakeOfferDialogComponent, {
            width: '500px',
            disableClose: true,
            data: this.prepareOfferData()
        });
    }

    private fetchProfile = async () => {
        const result = await this.activatedRoute.data.pipe(first()).toPromise();
        if (result.profile && result.profile.data) {
            this.profile = result.profile.data;
            this.prepareAge();
            this.prepareStatisticsRegisterDate();
            this.prepareTutorSuitabilities();
        }
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private prepareAge() {
        this.profile.birthday &&
            (this.profile.age = UtilityService.millisecondsToDate(this.profile.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
    }

    private prepareOfferData() {
        return {
            'id': this.profile.id,
            'name': this.profile.name,
            'surname': this.profile.surname,
            'lectures': this.profile.tutor_lectures
        } as InfoType;
    }

    private prepareStatisticsRegisterDate() {
        this.profile.tutor_statistics && this.profile.tutor_statistics.register_date &&
            (this.profile.tutor_statistics.register_date_readable =
            UtilityService.millisecondsToDate(this.profile.tutor_statistics.register_date, DATE_TIME.FORMAT.DATE));
    }

    private prepareTutorSuitabilities() {
        const parseSuitability = (value: string, type: string, variable) => {
            if (this.profile.tutor_suitability[value]) {
                this[variable] = [];
                Object.keys(this.profile.tutor_suitability[value]).forEach((key: never) => {
                    !!this.profile.tutor_suitability[value][key] && this[variable].push(TYPES.SUITABILITIES[type][key])
                });
            }
        };

        if (this.profile.tutor_suitability) {
            parseSuitability('course_type', 'COURSE_TYPE', 'course_types');
            parseSuitability('facility', 'FACILITY', 'facilities');
            parseSuitability('location', 'LOCATION', 'locations');
        }
    }

}
