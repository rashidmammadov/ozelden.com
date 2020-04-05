import { Component, OnInit } from '@angular/core';
import { select, Store } from '@ngrx/store';
import { MissingFieldsType } from '../../../interfaces/missing-fields-type';
import { UtilityService } from '../../../services/utility/utility.service';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { setMissingFields } from '../../../store/actions/missing-field.action';
import { UserService } from '../../../services/user/user.service';

@Component({
    selector: 'app-missing-fields',
    templateUrl: './missing-fields.component.html',
    styleUrls: ['./missing-fields.component.scss']
})
export class MissingFieldsComponent implements OnInit {

    missingFields: MissingFieldsType;

    constructor(private store: Store<{missingFields: MissingFieldsType}>, private userService: UserService) { }

    async ngOnInit() {
        this.store.pipe(select('missingFields')).subscribe(data => {
            setTimeout(() => this.missingFields = data, 0);
        });
        await this.getMissingFields();
    }

    private getMissingFields = async () => {
        const result = await this.userService.getMissingFields();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.store.dispatch(setMissingFields({ missingFields: response.data }));
        });
    };

}
