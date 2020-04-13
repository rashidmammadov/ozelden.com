import { Injectable} from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs/internal/Observable';
import { ErrorResponse } from '../models/error-response';
import { IHttpResponse } from '../interfaces/i-http-response';
import { ProfileService } from '../services/profile/profile.service';

@Injectable()
export class ProfileResolver implements Resolve<any> {

    constructor(private profileService: ProfileService) { }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ErrorResponse | IHttpResponse> | Promise<any> | any {
        const id: number = Number(route.params.id);
        return this.profileService.get(id);
    }

}
