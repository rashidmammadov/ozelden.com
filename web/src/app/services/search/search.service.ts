import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { ENDPOINTS } from '../../constants/endpoints.constant';

@Injectable({
    providedIn: 'root'
})
export class SearchService {

    constructor(private http: HttpClient) { }

    get(page, params): Promise<ErrorResponse | IHttpResponse> {
        let queryParams = [`page=${page}`];
        Object.keys(params).forEach((key: string) => {
            !!params[key] && queryParams.push(`${key}=${params[key]}`);
        });
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.SEARCH(encodeURI(queryParams.join('&')))));
    }
}
