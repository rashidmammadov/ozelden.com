import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Store } from '@ngrx/store';
import { DecideOfferDialogComponent } from '../dialogs/decide-offer-dialog/decide-offer-dialog.component';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { OfferService } from '../../services/offer/offer.service';
import { OfferType } from '../../interfaces/offer-type';
import { PaginationType } from '../../interfaces/pagination-type';
import { UtilityService } from '../../services/utility/utility.service';
import { TableColumnType } from '../../interfaces/table-column-type';
import { TutorLectureType } from '../../interfaces/tutor-lecture-type';
import { UserType } from '../../interfaces/user-type';
import { StudentType } from '../../interfaces/student-type';
import { DATE_TIME } from '../../constants/date-time.constant';
import { TYPES } from '../../constants/types.constant';
import { loaded, loading } from '../../store/actions/progress.action';
import { first } from 'rxjs/operators';
import {Router} from "@angular/router";

@Component({
    selector: 'app-offers',
    templateUrl: './offers.component.html',
    styleUrls: ['./offers.component.scss']
})
export class OffersComponent implements OnInit {

    user: UserType;
    columns: TableColumnType[] = [{
        header: 'Teklif Tipi',
        value: 'offer_type',
        icon: 'offer_type',
        calc: d => TYPES.OFFER_TYPE[d]
    }, {
        header: 'Teklif Gönderen',
        value: 'sender',
        calc: (d: UserType) => d && Number(d.id) !== Number(this.user.id) ? `${d.name} ${d.surname}` : 'Ben',
        click: (d) => this.router.navigateByUrl(`/app/profile/${d.sender_id}`)
    }, {
        header: 'Öğrenci',
        value: 'student',
        calc: (d: StudentType) => d && (d.name || d.surname) ? `${d.name} ${d.surname}` : '-'
    }, {
        header: 'Teklif Alan',
        value: 'receiver',
        calc: (d: UserType) => d && Number(d.id) !== Number(this.user.id) ? `${d.name} ${d.surname}` : 'Ben',
        click: (d) => this.router.navigateByUrl(`/app/profile/${d.receiver_id}`)
    }, {
        header: 'Teklif Verilen Ders',
        value: 'tutor_lecture',
        calc: (d: TutorLectureType) => `${d.lecture_theme} (${d.lecture_area})`
    }, {
        header: 'Teklif Miktarı',
        value: 'offer',
        additional: '₺'
    }, {
        header: 'Tarih',
        value: 'updated_at',
        calc: d => UtilityService.millisecondsToDate(d, DATE_TIME.FORMAT.DATE)
    }, {
        header: 'Durum',
        value: 'status',
        button: true,
        class: (d) => d === 1 ? 'button-green' : (d === 99 ? 'button-red' : ''),
        click: (d) => this.openDecideOfferDialog(d),
        calc: d => TYPES.OFFER_STATUS[d]
    }];
    pagination: PaginationType = {} as PaginationType;
    page: number = 1;
    offers: OfferType[] = [];

    constructor(private offerService: OfferService, private dialog: MatDialog,
                private store: Store<{progress: boolean, user: UserType}>, private router: Router) { }

    async ngOnInit() {
        await this.fetchUser();
        await this.fetchOffers(this.page);
    }

    async changePage(page) {
        await this.fetchOffers(page);
    }

    private fetchOffers = async (page: number) => {
        this.store.select(loading);
        const result = await this.offerService.get(page);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.offers = response.data;
            this.pagination.current_page = response.current_page;
            this.pagination.limit = response.limit;
            this.pagination.total_data = response.total_data;
            this.pagination.total_page = response.total_page;
        });
        this.store.select(loaded);
    };

    private fetchUser = async () => {
        this.user = await this.store.select('user').pipe(first()).toPromise();
    };

    private openDecideOfferDialog(offer: OfferType) {
        this.dialog.open(DecideOfferDialogComponent, {
              width: '500px',
              disableClose: true,
              data: offer
          })
          .afterClosed()
          .toPromise()
          .then((result: number) => { result && (offer.status = result); });
    }

}
