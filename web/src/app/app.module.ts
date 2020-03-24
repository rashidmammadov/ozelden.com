import { NgModule, Injector, APP_INITIALIZER } from '@angular/core';
import { AppRoutingModule } from './modules/app-routing.module';
import { AngularMaterialModule } from './modules/angular-material.module';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FlexLayoutModule } from '@angular/flex-layout';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { StoreModule } from '@ngrx/store';

import { AppComponent } from './components/app/app.component';
import { ApplicationComponent } from './components/application/application.component';
import { AddAnnouncementComponentBottomSheet } from './components/sheets/add-announcement-bottom-sheet/add-announcement-component-bottom-sheet.component';
import { AskOfferDialogComponent } from './components/dialogs/ask-offer-dialog/ask-offer-dialog.component';
import { ConfirmDialogComponent } from './components/dialogs/confirm-dialog/confirm-dialog.component';
import { DepositDialogComponent } from './components/dialogs/deposit-dialog/deposit-dialog.component';
import { EditStudentDialogComponent } from './components/dialogs/edit-student-dialog/edit-student-dialog.component';
import { InfoCardComponent } from './components/info-card/info-card.component';
import { HomeComponent } from './components/home/home.component';
import { GridListComponent } from './components/shared/grid-list/grid-list.component';
import { OffersComponent } from './components/offers/offers.component';
import { PaidServiceComponent } from './components/paid-service/paid-service.component';
import { LecturesComponent } from './components/lectures/lectures.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { SuitabilityComponent } from './components/suitability/suitability.component';
import { StudentCardComponent } from './components/student-card/student-card.component';
import { TableComponent } from './components/shared/table/table.component';
import { ThreedsDialogComponent } from './components/dialogs/threeds-dialog/threeds-dialog.component';
import { TutoredStudentsComponent } from './components/tutored-students/tutored-students.component';
import { UploadFileComponent } from './components/shared/upload-file/upload-file.component';

import { CookieService } from 'ngx-cookie-service';
import { Cookie } from './services/cookie/cookie.service';
import { DataService } from './services/data/data.service';
import { ToastService } from './services/toast/toast.service';
import { UtilityService } from './services/utility/utility.service';

import { citiesReducer } from './store/reducers/cities.reducer';
import { lecturesReducer } from './store/reducers/lectures.reducer';
import { progressReducer } from './store/reducers/progress.reducer';
import { userReducer } from './store/reducers/user.reducer';

import { TokenInterceptor } from './interceptors/token.interceptor';

export function fetchStaticData(dataService: DataService) {
    return () => dataService.saveOnStore();
}

@NgModule({
    declarations: [
        AppComponent,
        ApplicationComponent,
        AddAnnouncementComponentBottomSheet,
        AskOfferDialogComponent,
        ConfirmDialogComponent,
        DepositDialogComponent,
        EditStudentDialogComponent,
        InfoCardComponent,
        HomeComponent,
        GridListComponent,
        OffersComponent,
        PaidServiceComponent,
        LecturesComponent,
        LoginComponent,
        RegisterComponent,
        SuitabilityComponent,
        StudentCardComponent,
        TableComponent,
        ThreedsDialogComponent,
        TutoredStudentsComponent,
        UploadFileComponent,
        AddAnnouncementComponentBottomSheet
    ],
    imports: [
        AppRoutingModule,
        AngularMaterialModule,
        BrowserAnimationsModule,
        BrowserModule,
        FlexLayoutModule,
        FormsModule,
        HttpClientModule,
        ReactiveFormsModule,
        StoreModule.forRoot({ cities: citiesReducer, lectures: lecturesReducer, progress: progressReducer, user: userReducer }),
    ],
    entryComponents: [
        AddAnnouncementComponentBottomSheet,
        AskOfferDialogComponent,
        ConfirmDialogComponent,
        DepositDialogComponent,
        EditStudentDialogComponent,
        ThreedsDialogComponent
    ],
    providers: [
        CookieService,
        { provide: APP_INITIALIZER, useFactory: fetchStaticData, deps: [DataService], multi: true },
        { provide: HTTP_INTERCEPTORS, useClass: TokenInterceptor, multi: true },
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
    constructor(private injector: Injector) {
        Cookie.injector = injector;
        UtilityService.injector = injector;
        ToastService.injector = injector;
    }
}
