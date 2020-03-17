import { Component, OnInit } from '@angular/core';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { OfferService } from '../../services/offer/offer.service';
import { OfferType } from '../../interfaces/offer-type';
import { UtilityService } from '../../services/utility/utility.service';
import { TableColumnType } from '../../interfaces/table-column-type';
import { TutorLectureType } from '../../interfaces/tutor-lecture-type';
import { UserType } from '../../interfaces/user-type';
import { StudentType } from '../../interfaces/student-type';

@Component({
    selector: 'app-offers',
    templateUrl: './offers.component.html',
    styleUrls: ['./offers.component.scss']
})
export class OffersComponent implements OnInit {

    columns: TableColumnType[] = [{
        header: 'Teklif Tipi',
        value: 'sender_type'
    }, {
        header: 'Teklif Gönderen',
        value: 'sender',
        calc: (d: UserType) => d && (d.name || d.surname) ? `${d.name} ${d.surname}` : 'Ben'
    }, {
        header: 'Öğrenci',
        value: 'student',
        calc: (d: StudentType) => d && (d.name || d.surname) ? `${d.name} ${d.surname}` : 'Kendi İçin'
    }, {
        header: 'Teklif Gönderilen',
        value: 'receiver',
        calc: (d: UserType) => d && (d.name || d.surname) ? `${d.name} ${d.surname}` : 'Ben'
    }, {
        header: 'Teklif Verilen Ders',
        value: 'tutor_lecture',
        calc: (d: TutorLectureType) => `${d.lecture_theme} (${d.lecture_area})`
    }, {
        header: 'Teklif Miktarı',
        value: 'offer',
        additional: '₺'
    }, {
        header: 'Durum',
        value: 'status'
    }];
    offers: OfferType[] = [];

    constructor(private offerService: OfferService) { }

    async ngOnInit() {
        await this.fetchOffers();
    }

    private fetchOffers = async () => {
        const result = await this.offerService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.offers = response.data;
        });
    }

}
