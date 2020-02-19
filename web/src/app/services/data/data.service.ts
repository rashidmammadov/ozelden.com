import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Store } from '@ngrx/store';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { ENDPOINTS } from '../../constants/endpoints.constant';
import { setCities } from 'src/app/store/actions/cities.action';
import { CityType } from '../../interfaces/city-type';

@Injectable({
    providedIn: 'root'
})
export class DataService {

    constructor(private http: HttpClient, private store: Store<{cities: CityType[]}>) { }

    cities(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.DATA('cities')));
    }

    saveOnStore = async () => {
        const citiesResponse = await this.cities();
        UtilityService.handleResponseFromService(citiesResponse, (response: IHttpResponse) => {
            this.store.dispatch(setCities({cities: response.data}));
        });
    }

}
