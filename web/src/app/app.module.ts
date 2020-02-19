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
import { HomeComponent } from './components/home/home.component';
import { LecturesComponent } from './components/lectures/lectures.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { SuitabilityComponent } from './components/suitability/suitability.component';
import { TableComponent } from './components/shared/table/table.component';

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
        HomeComponent,
        LecturesComponent,
        LoginComponent,
        RegisterComponent,
        SuitabilityComponent,
        TableComponent
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
