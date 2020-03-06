import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ErrorResponse } from '../../models/error-response';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UtilityService } from '../utility/utility.service';
import { ENDPOINTS } from '../../constants/endpoints.constant';

@Injectable({
    providedIn: 'root'
})
export class StudentService {

    constructor(private http: HttpClient) { }

    addTutoredStudent(student): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.post<IHttpResponse>(ENDPOINTS.STUDENTS(), student));
    }

    getTutoredStudents(): Promise<ErrorResponse | IHttpResponse> {
        return UtilityService.pipeHttpResponse(this.http.get<IHttpResponse>(ENDPOINTS.STUDENTS()));
    }
}
