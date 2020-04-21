import { Component, OnInit } from '@angular/core';
import { select, Store } from '@ngrx/store';
import { OfferService } from '../../services/offer/offer.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UserType } from '../../interfaces/user-type';
import { setOffersCount } from '../../store/actions/offers-count.action';
import { first } from 'rxjs/operators';

@Component({
    selector: 'app-application',
    templateUrl: './application.component.html',
    styleUrls: ['./application.component.scss']
})
export class ApplicationComponent implements OnInit {

    user: UserType;
    offersCount: number = 0;
    buttons = [];

    constructor(private store: Store<{offersCount: number, user: UserType}>,
                private offerService: OfferService) {
        store.pipe(select('offersCount')).subscribe(data => {
            setTimeout(() => this.offersCount = data, 0);
        });
    }

    async ngOnInit() {
        await this.getUser();
        this.buttons = UtilityService.prepareNavButtons(this.user);
        await this.fetchReceivedOffersCount();
    }

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private fetchReceivedOffersCount = async () => {
        const result = await this.offerService.getReceivedOffersCount();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.store.dispatch(setOffersCount({ offersCount: response.data }));
        });
    };

}
