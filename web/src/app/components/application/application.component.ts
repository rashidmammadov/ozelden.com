import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { select, Store } from '@ngrx/store';
import { GoogleAnalyticsService } from '../../services/google-analytics/google-analytics.service';
import { OfferService } from '../../services/offer/offer.service';
import { OneSignalService } from '../../services/one-signal/one-signal.service';
import { MetaService } from '../../services/meta/meta.service';
import { UtilityService } from '../../services/utility/utility.service';
import { ToastService } from '../../services/toast/toast.service';
import { OneSignalDialogComponent } from '../dialogs/one-signal-dialog/one-signal-dialog.component';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { OneSignalType } from '../../interfaces/one-signal-type';
import { UserType } from '../../interfaces/user-type';
import { setOffersCount } from '../../store/actions/offers-count.action';
import { first } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

@Component({
    selector: 'app-application',
    templateUrl: './application.component.html',
    styleUrls: ['./application.component.scss']
})
export class ApplicationComponent implements OnInit {

    user: UserType;
    offersCount: number = 0;
    buttons = [];
    private static oneSignal: OneSignalType = {} as OneSignalType;
    private static oneSignalDialog;
    private static oneSignalService;

    constructor(private store: Store<{offersCount: number, user: UserType}>, private dialog: MatDialog,
                private offerService: OfferService, private metaService: MetaService,
                private oneSignalService: OneSignalService) {
        ApplicationComponent.oneSignalDialog = dialog;
        ApplicationComponent.oneSignalService = oneSignalService;
        metaService.updateOgMetaTags();
        store.pipe(select('offersCount')).subscribe(data => {
            setTimeout(() => this.offersCount = data, 0);
        });
        this.initOneSignalNotification();
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

    private initOneSignalNotification() {
        // @ts-ignore
        let OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: environment.oneSignalAppId,
                notifyButton: {
                    enable: true,
                    size: 'medium',
                    theme: 'default',
                    position: 'bottom-left',
                    prenotify: true,
                    showCredit: false,
                    text: {
                        'tip.state.unsubscribed': 'Bildirimleri aç',
                        'tip.state.subscribed': 'Bildirimler açık',
                        'tip.state.blocked': 'Bildirimler engellendi',
                        'message.prenotify': 'Bildirimleri açmak için tıklayın',
                        'message.action.subscribed': 'Bildirimleri takip ediliyor',
                        'message.action.resubscribed': 'Bildirimler açık',
                        'message.action.unsubscribed': 'Artık bildirim almayacaksınız',
                        'dialog.main.title': 'Bildirimleri kontrol et',
                        'dialog.main.button.subscribe': 'Bildirimleri aç',
                        'dialog.main.button.unsubscribe': 'Bildirimleri kapat',
                        'dialog.blocked.title': 'Bildirimlerden bloku kaldırın',
                        'dialog.blocked.message': 'Bildirimler için bu adımları takip edin'
                    },
                    colors: {
                        'circle.background': '#722947',
                        'circle.foreground': 'white',
                        'badge.background': '#722947',
                        'badge.foreground': 'white',
                        'badge.bordercolor': 'white',
                        'pulse.color': 'white',
                        'dialog.button.background.hovering': '#722947',
                        'dialog.button.background.active': '#722947',
                        'dialog.button.background': '#722947',
                        'dialog.button.foreground': 'white'
                    },
                    unsubscribeEnabled: false,
                    displayPredicate: function() {
                        return OneSignal.isPushNotificationsEnabled()
                            .then(function(isPushEnabled) {
                                return !isPushEnabled;
                            });
                    }
                },
                subdomainName: 'ozelden'
            });
            OneSignal.isPushNotificationsEnabled(function(subscribing) {
                if (!subscribing) {
                    ApplicationComponent.oneSignalDialog.open(OneSignalDialogComponent, { width: '500px', disableClose: false });
                }
            });

            OneSignal.on('subscriptionChange', function (isSubscribed) {
                if (isSubscribed) {
                    OneSignal.push(function () {
                        OneSignal.getUserId(function (userId) {
                            ApplicationComponent.oneSignal.one_signal_device_id = userId;
                            ApplicationComponent.oneSignal.device_type = navigator.userAgent;
                            ApplicationComponent.addToSubscribers();
                        });
                    });
                }
            });
        });
    }

    private static addToSubscribers = async () => {
        const result = await ApplicationComponent.oneSignalService.add(ApplicationComponent.oneSignal);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            GoogleAnalyticsService.subscribeNotifications();
            ToastService.show(response.message);
        });
    }

}
