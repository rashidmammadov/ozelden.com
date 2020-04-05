import { Injectable } from '@angular/core';
import { Store } from '@ngrx/store';
import { HttpClient } from '@angular/common/http';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { MissingFieldsType } from '../../interfaces/missing-fields-type';
import { ENDPOINTS } from '../../constants/endpoints.constant';
import {Router} from "@angular/router";
import {first} from "rxjs/operators";
import {setMissingFields} from "../../store/actions/missing-field.action";

@Injectable({
    providedIn: 'root'
})
export class UserService {

    constructor(private http: HttpClient, private store: Store<{missingFields: MissingFieldsType}>) { }

    getMissingFields(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.MISSING_FIELDS()));
    }

    update(user): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.put<IHttpResponse>(ENDPOINTS.USER(),
            UtilityService.setHttpParams(user)));
    }

    updateMissingFields = async (type: string, value: boolean) => {
        let missingFields = await this.store.select('missingFields').pipe(first()).toPromise();
        missingFields[type] = value;
        this.store.dispatch(setMissingFields({ missingFields: missingFields }));
    }
}
