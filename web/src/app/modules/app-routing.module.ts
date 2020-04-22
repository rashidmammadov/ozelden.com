import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ApplicationComponent } from '../components/application/application.component';
import { HomeComponent } from '../components/home/home.component';
import { OffersComponent } from '../components/offers/offers.component';
import { PaidServiceComponent } from '../components/paid-service/paid-service.component';
import { PdplComponent } from '../components/pdpl/pdpl.component';
import { ProfileComponent } from '../components/profile/profile.component';
import { LandingComponent } from '../components/landing/landing.component';
import { LecturesComponent } from '../components/lectures/lectures.component';
import { LoginComponent } from '../components/login/login.component';
import { RegisterComponent } from '../components/register/register.component';
import { SettingsComponent } from '../components/settings/settings.component';
import { SuitabilityComponent } from '../components/suitability/suitability.component';
import { TutoredStudentsComponent } from '../components/tutored-students/tutored-students.component';

import { AuthGuard } from '../guards/auth.guard';
import { ProfileResolver } from '../resolvers/profile.resolver';

const routes: Routes = [
    {
        path: '',
        pathMatch: 'full',
        component: LandingComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'login',
        component: LoginComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'register',
        component: RegisterComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'kvkk',
        component: PdplComponent,
    },
    {
        path: 'app',
        canActivate: [AuthGuard],
        component: ApplicationComponent,
        children: [
            {
                path: 'home',
                component: HomeComponent
            },
            {
                path: 'offers',
                component: OffersComponent
            },
            {
                path: 'paid-service',
                component: PaidServiceComponent
            },
            {
                path: 'lectures',
                component: LecturesComponent
            },
            {
                path: 'settings',
                component: SettingsComponent
            },
            {
                path: 'suitability',
                component: SuitabilityComponent
            },
            {
                path: 'tutored-students',
                component: TutoredStudentsComponent
            },
            {
                path: 'profile/:id',
                component: ProfileComponent,
                resolve: { profile: ProfileResolver }
            }
        ]
    }
];

@NgModule({
    imports: [ RouterModule.forRoot(routes, { scrollPositionRestoration: 'enabled', onSameUrlNavigation: 'reload' }) ],
    exports: [ RouterModule ],
    providers: [ ProfileResolver ]
})
export class AppRoutingModule { }
