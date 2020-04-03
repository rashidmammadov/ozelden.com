import { Component, OnInit } from '@angular/core';
import { select, Store } from '@ngrx/store';
import { OfferService } from '../../services/offer/offer.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { UserType } from '../../interfaces/user-type';
import { TYPES } from '../../constants/types.constant';
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
        this.prepareButtons();
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

    private prepareButtons() {
        const home = {routerLink: '/app/home', icon: 'home', title: 'Ana Sayfa'};
        const offers = {routerLink: '/app/offers', icon: 'offers', title: 'Teklifler', badgeOffersCount: true};
        const tutoredStudents = {routerLink: '/app/tutored-students', icon: 'students', title: 'Öğrencilerim'};
        const paidService = {routerLink: '/app/paid-service', icon: 'star', title: 'Hizmetler'};
        const lectures = {routerLink: '/app/lectures', icon: 'paperclip', title: 'Derslerim'};
        const suitability = {routerLink: '/app/suitability', icon: 'sliders', title: 'Uygunluk'};
        const settings = {routerLink: '/app/settings', icon: 'settings', title: 'Ayarlar'};
        if (this.user.type === TYPES.TUTOR) {
            this.buttons = [home, offers, paidService, lectures, suitability, settings];
        } else if (this.user.type === TYPES.TUTORED) {
            this.buttons = [home, offers, tutoredStudents, settings];
        }
    }

}
