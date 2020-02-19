import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Store } from '@ngrx/store';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { CityType } from '../../interfaces/city-type';
import { LectureType } from '../../interfaces/lecture-type';
import { ENDPOINTS } from '../../constants/endpoints.constant';
import { setCities } from '../../store/actions/cities.action';
import { setLectures } from "../../store/actions/lectures.action";

@Injectable({
    providedIn: 'root'
})
export class DataService {

    constructor(private http: HttpClient, private store: Store<{cities: CityType[], lectures: LectureType[]}>) { }

    cities(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.DATA('cities')));
    }

    lectures(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.DATA('lectures')));
    }

    saveOnStore = async () => {
        await this.fetchAndSaveCities();
        await this.fetchAndSaveLectures();
    };

    private fetchAndSaveCities = async () => {
        const citiesResponse = await this.cities();
        UtilityService.handleResponseFromService(citiesResponse, (response: IHttpResponse) => {
            this.store.dispatch(setCities({cities: response.data}));
        });
    };

    private fetchAndSaveLectures = async () => {
        const lecturesResponse = await this.lectures();
        UtilityService.handleResponseFromService(lecturesResponse, (response: IHttpResponse) => {
            this.store.dispatch(setLectures({lectures: response.data}));
        });
    };

}
