import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { select, Store } from '@ngrx/store';
import { OfferService } from '../../services/offer/offer.service';
import { MetaService } from '../../services/meta/meta.service';
import { UtilityService } from '../../services/utility/utility.service';
import { OneSignalDialogComponent } from '../dialogs/one-signal-dialog/one-signal-dialog.component';
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
    private static oneSignalDialog;

    constructor(private store: Store<{offersCount: number, user: UserType}>, private dialog: MatDialog,
                private offerService: OfferService, private metaService: MetaService) {
        ApplicationComponent.oneSignalDialog = dialog;
        metaService.updateOgMetaTags();
        store.pipe(select('offersCount')).subscribe(data => {
            setTimeout(() => this.offersCount = data, 0);
        });
        this.openOneSignalDialog();
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

    private openOneSignalDialog() {
        // @ts-ignore
        let OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: '34a6f634-b8ae-4caa-9871-881eaea3c68c',
                subdomainName: 'ozelden',
                promptOptions: {
                    customlink: {
                        enabled: true,
                        style: 'button',
                        size: 'medium',
                        color: { button: '#722947', text: '#ffffff' },
                        text: { subscribe: 'Bildirimleri Aç', unsubscribe: 'Bildirimleri Kapat' },
                        unsubscribeEnabled: true,
                    }
                }
            });
            ApplicationComponent.oneSignalDialog.open(OneSignalDialogComponent, { width: '500px', disableClose: true });
        });
    }

}
