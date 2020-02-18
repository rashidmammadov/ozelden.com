import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { SuitabilityService} from '../../services/suitability/suitability.service';
import { ToastService } from '../../services/toast/toast.service';
import { UtilityService } from '../../services/utility/utility.service';
import { CourseType } from '../../interfaces/course-type';
import { FacilityType } from '../../interfaces/facility-type';
import { LocationType } from '../../interfaces/location-type';
import { loaded, loading } from '../../store/actions/progress.action';

@Component({
    selector: 'app-suitability',
    templateUrl: './suitability.component.html',
    styleUrls: ['./suitability.component.scss']
})
export class SuitabilityComponent implements OnInit {

    public course_type: CourseType;
    public facility: FacilityType;
    public location: LocationType;

    constructor(private suitabilityService: SuitabilityService, private store: Store<{progress: boolean}>) { }

    async ngOnInit() {
        await this.getSuitability();
    }

    async updateSuitability(type: string) {
        this.store.select(loading);
        Object.keys(this[type]).forEach((key: string) => this[type][key] = this[type][key] ? 1 : 0);
        const result = await this.suitabilityService.update(type, this[type]);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
        });
        this.store.select(loaded);
    }

    private getSuitability = async () => {
        this.store.select(loading);
        const result = await this.suitabilityService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.course_type = response.data.course_type;
            this.facility = response.data.facility;
            this.location = response.data.location;
        });
        this.store.select(loaded);
    }

}
