import { Injectable, Injector } from '@angular/core';
import { ANALYTICS } from '../../constants/analytics.constant';
import { PaidServiceType } from '../../interfaces/paid-service-type';
import { environment } from '../../../environments/environment';

declare let ga: Function;
@Injectable({
    providedIn: 'root'
})
export class GoogleAnalyticsService {

    public static injector: Injector;

    private static _eventAction: string = 'Anonymous';

    public static setEventAction(eventAction: string) {
        GoogleAnalyticsService.eventAction = eventAction;
    }

    public static addLecture(lecture) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.ADD_LECTURE, JSON.stringify(lecture));
    }

    public static addStudent(student) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.ADD_STUDENT, JSON.stringify(student));
    }

    public static askOffer(offer) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.ASK_OFFER, JSON.stringify(offer));
    }

    public static buyPaidServicePackage(data) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.BUY_PAID_SERVICE_PACKAGE, JSON.stringify(data));
    }

    public static checkActivePaidServices(paidService: PaidServiceType) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.CHECK_ACTIVE_PAID_SERVICES, JSON.stringify(paidService));
    }

    public static checkOfferStatus(offerId: number) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.CHECK_OFFER_STATUS, `offer id: ${offerId}`);
    }

    public static confirm3DSSecurity() {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.CONFIRM_3DS_SECURITY);
    }

    public static decideOffer(offerId: number, status: string) {
        const label = { 'offer id': offerId, 'status': status };
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.DECIDE_OFFER, JSON.stringify(label));
    }

    public static deleteLecture(lectureId: number) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.DELETE_LECTURE, `lecture id: ${lectureId}`);
    }

    public static errorResponse(error) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.ERROR_RESPONSE, JSON.stringify(error));
    }

    public static fetchOffers(page: number) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.FETCH_OFFERS, `page: ${page}`);
    }

    public static login(params) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.LOGIN, JSON.stringify(params.email));
    }

    public static logout() {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.LOGOUT);
    }

    public static register(params) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.REGISTER, JSON.stringify(params.email));
    }

    public static search(filters) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.SEARCH, JSON.stringify(filters));
    }

    public static sendOffer(offer) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.SEND_OFFER, JSON.stringify(offer));
    }

    public static subscribeNotifications() {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.SUBSCRIBE);
    }

    public static updatePassword() {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_PASSWORD);
    }

    public static updateProfilePicture() {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_PROFILE_PICTURE);
    }

    public static updateProfileSettings(params) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_PROFILE_SETTINGS, JSON.stringify(params));
    }

    public static updateUserSettings(params) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_USER_SETTINGS, JSON.stringify(params));
    }

    public static updateStudent(student) {
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_STUDENT, JSON.stringify(student));
    }

    public static updateSuitability(type: string, params) {
        const label = { 'type': type, 'params': params };
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.UPDATE_SUITABILITY, JSON.stringify(label));
    }

    public static watchProfile(id: number, type: string, fullName: string) {
        const label = { 'id': id, 'type': type, 'full name': fullName };
        GoogleAnalyticsService.eventTrack(ANALYTICS.CATEGORY.WATCH_PROFILE, JSON.stringify(label));
    }

    public static eventTrack(eventCategory: string, eventLabel: string = null, eventValue: number = null) {
        if (environment.production) {
            ga('send', 'event', {
              eventCategory: eventCategory,
              eventAction: GoogleAnalyticsService.eventAction,
              eventLabel: eventLabel,
              eventValue: eventValue
            });
        }
    }

    static get eventAction(): string {
        return this._eventAction;
    }

    static set eventAction(value: string) {
        this._eventAction = value;
    }

}
