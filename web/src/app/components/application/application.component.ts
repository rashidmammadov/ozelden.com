import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { UserType } from '../../interfaces/user-type';
import { TYPES } from '../../constants/types.constant';
import { first } from 'rxjs/operators';

@Component({
    selector: 'app-application',
    templateUrl: './application.component.html',
    styleUrls: ['./application.component.scss']
})
export class ApplicationComponent implements OnInit {

    user: UserType;
    buttons = [];

    constructor(private store: Store<{user: UserType}>) { }

    async ngOnInit() {
        await this.getUser();
        this.prepareButtons();
    }

    private getUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private prepareButtons() {
        const home = {routerLink: '/app/home', icon: 'home', title: 'Ana Sayfa'};
        const offers = {routerLink: '/app/offers', icon: 'offers', title: 'Teklifler'};
        const tutoredStudents = {routerLink: '/app/tutored-students', icon: 'students', title: 'Öğrencilerim'};
        const paidService = {routerLink: '/app/paid-service', icon: 'star', title: 'Hizmetler'};
        const lectures = {routerLink: '/app/lectures', icon: 'paperclip', title: 'Derslerim'};
        const suitability = {routerLink: '/app/suitability', icon: 'sliders', title: 'Uygunluk'};
        if (this.user.type === TYPES.TUTOR) {
            this.buttons = [home, offers, paidService, lectures, suitability];
        } else if (this.user.type === TYPES.TUTORED) {
            this.buttons = [home, offers, tutoredStudents];
        }
    }

}
