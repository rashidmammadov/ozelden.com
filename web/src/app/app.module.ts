import { NgModule, Injector } from '@angular/core';
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
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';

import { CookieService } from 'ngx-cookie-service';
import { Cookie } from './services/cookie/cookie.service';
import { UtilityService } from './services/utility/utility.service';
import { ToastService } from './services/toast/toast.service';

import { progressReducer } from './store/reducers/progress.reducer';
import { userReducer } from './store/reducers/user.reducer';

import { TokenInterceptor } from './interceptors/token.interceptor';

@NgModule({
    declarations: [
        AppComponent,
        ApplicationComponent,
        HomeComponent,
        LoginComponent,
        RegisterComponent
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
        StoreModule.forRoot({ progress: progressReducer, user: userReducer }),
    ],
    providers: [
        CookieService,
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
