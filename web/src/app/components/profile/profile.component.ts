import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { ActivatedRoute } from '@angular/router';
import { UtilityService } from '../../services/utility/utility.service';
import { first } from 'rxjs/operators';
import { UserProfileType } from '../../interfaces/user-profile-type';
import { DATE_TIME } from '../../constants/date-time.constant';

@Component({
    selector: 'app-profile',
    templateUrl: './profile.component.html',
    styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {

    profile: UserProfileType;

    constructor(private store: Store<{progress: boolean}>, private activatedRoute: ActivatedRoute) { }

    async ngOnInit() {
        await this.fetchProfile();
    }

    private fetchProfile = async () => {
        const result = await this.activatedRoute.data.pipe(first()).toPromise();
        if (result.profile && result.profile.data) {
            this.profile = result.profile.data;
            this.profile.birthday &&
                (this.profile.age = UtilityService.millisecondsToDate(this.profile.birthday, DATE_TIME.FORMAT.TOTAL_YEARS));
        }
    }

}
