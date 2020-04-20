import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { OfferService } from '../../../services/offer/offer.service';
import { PaidService } from '../../../services/paid/paid.service';
import { UtilityService } from '../../../services/utility/utility.service';
import { ToastService } from '../../../services/toast/toast.service';
import { OfferType } from '../../../interfaces/offer-type';
import { UserType } from '../../../interfaces/user-type';
import { first } from 'rxjs/operators';
import { TYPES } from '../../../constants/types.constant';
import { loaded, loading } from '../../../store/actions/progress.action';
import { setOffersCount } from '../../../store/actions/offers-count.action';

@Component({
    selector: 'app-decide-offer-dialog',
    templateUrl: './decide-offer-dialog.component.html',
    styleUrls: ['./decide-offer-dialog.component.scss']
})
export class DecideOfferDialogComponent implements OnInit {

    user: UserType;
    remainingBids: number = 0;
    TYPES = TYPES;

    constructor(public dialogRef: MatDialogRef<DecideOfferDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: OfferType,
                private store: Store<{offersCount: number, progress: boolean, user: UserType}>,
                private offerService: OfferService, private paidService: PaidService) { }

    async ngOnInit() {
        await this.fetchPaidServices();
        await this.getUser();
    }

    makeDecideForOffer = async (decision: number) => {
        let offersCount = await this.store.select('offersCount').pipe(first()).toPromise();
        this.store.dispatch(loading());
        const result = await this.offerService.updateOfferStatus(this.data.offer_id, { status: decision });
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.store.dispatch(setOffersCount({ offersCount: (offersCount - 1) }));
            ToastService.show(response.message);
            this.dialogRef.close(decision);
        });
        this.store.dispatch(loaded());
    };

    private fetchPaidServices = async () => {
        this.store.dispatch(loading());
        const result = await this.paidService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            if (response.data && response.data.bid) {
                this.remainingBids = response.data.bid;
            }
        });
        this.store.dispatch(loaded());
    };

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

}
