import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { ENDPOINTS } from '../../constants/endpoints.constant';

@Injectable({
    providedIn: 'root'
})
export class AuthService {

    constructor(private http: HttpClient) { }

    check(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.AUTH()));
    }

    login(params): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.put<IHttpResponse>(ENDPOINTS.AUTH(),
            UtilityService.setHttpParams(params)));
    }

    logout(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.delete<IHttpResponse>(ENDPOINTS.AUTH()));
    }

    register(params): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.post<IHttpResponse>(ENDPOINTS.AUTH(),
            UtilityService.setHttpParams(params)));
    }
}
