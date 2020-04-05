import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { first } from 'rxjs/operators';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { SuitabilityService} from '../../services/suitability/suitability.service';
import { ToastService } from '../../services/toast/toast.service';
import { UserService } from '../../services/user/user.service';
import { UtilityService } from '../../services/utility/utility.service';
import { CityType } from '../../interfaces/city-type';
import { CourseType } from '../../interfaces/course-type';
import { FacilityType } from '../../interfaces/facility-type';
import { LocationType } from '../../interfaces/location-type';
import { RegionType } from '../../interfaces/region-type';
import { loaded, loading } from '../../store/actions/progress.action';

@Component({
    selector: 'app-suitability',
    templateUrl: './suitability.component.html',
    styleUrls: ['./suitability.component.scss']
})
export class SuitabilityComponent implements OnInit {

    public cities: CityType[] = [];
    public city: CityType;
    public district: string;
    public course_type: CourseType;
    public facility: FacilityType;
    public location: LocationType;
    public regions: RegionType[];
    public changeList = {
        course_type: false,
        facility: false,
        location: false,
        regions: false
    };

    constructor(private suitabilityService: SuitabilityService, private userService: UserService,
                private store: Store<{cities: CityType[], progress: boolean}>) { }

    async ngOnInit() {
        await this.getCities();
        await this.getSuitability();
    }

    async updateSuitability(type: string) {
        let params = {};
        params[type] = this[type];
        this.store.select(loading);
        const result = await this.suitabilityService.update(type, params);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            this.watchingSuitableChange(type, false);
            if (type === 'regions') {
                this.userService.updateMissingFields('region', !(this.regions && this.regions.length));
            }
        });
        this.store.select(loaded);
    }

    public addRegion() {
        if (this.city && this.district) {
            let region: RegionType = {
                city: this.city.city_name,
                district: this.district
            };
            if (!this.regions.some(r => r.city === region.city && r.district === region.district)) {
                this.regions.push(region);
                this.watchingSuitableChange('regions', true);
            }
        }
    }

    public removeRegion(region: RegionType) {
        if (region) {
            this.regions = this.regions.filter(r => r.city !== region.city || r.district !== region.district);
            this.watchingSuitableChange('regions', true);
        }
    }

    public changeCity() {
        if (this.city) {
            this.district = this.city.districts[0];
        }
    }

    public watchingSuitableChange(type: string, status: boolean = true) {
        this.changeList[type] = status;
    }

    private getCities = async () => {
        this.cities = await this.store.select('cities').pipe(first()).toPromise();
        if (this.cities) {
            this.city = this.cities[33];
            this.changeCity();
        }
    };

    private getSuitability = async () => {
        this.store.select(loading);
        const result = await this.suitabilityService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.course_type = response.data.course_type;
            this.facility = response.data.facility;
            this.location = response.data.location;
            this.regions = response.data.regions;
        });
        this.store.select(loaded);
    }

}
