import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AboutComponent } from '../components/about/about.component';
import { ApplicationComponent } from '../components/application/application.component';
import { ContractComponent } from '../components/contract/contract.component';
import { HomeComponent } from '../components/home/home.component';
import { OffersComponent } from '../components/offers/offers.component';
import { PaidServiceComponent } from '../components/paid-service/paid-service.component';
import { PdplComponent } from '../components/pdpl/pdpl.component';
import { ProductsComponent } from '../components/products/products.component';
import { ProfileComponent } from '../components/profile/profile.component';
import { LandingComponent } from '../components/landing/landing.component';
import { LecturesComponent } from '../components/lectures/lectures.component';
import { LoginComponent } from '../components/login/login.component';
import { RegisterComponent } from '../components/register/register.component';
import { SearchComponent } from '../components/search/search.component';
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
        path: 'about-us',
        component: AboutComponent,
    },
    {
        path: 'contract',
        component: ContractComponent,
    },
    {
        path: 'kvkk',
        component: PdplComponent,
    },
    {
        path: 'products',
        component: ProductsComponent,
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
    },
    {
        path: ':lecture_area/:lecture_theme',
        canActivate: [AuthGuard],
        component: SearchComponent
    },
    {
        path: ':lecture_area',
        canActivate: [AuthGuard],
        component: SearchComponent
    }
];

@NgModule({
    imports: [ RouterModule.forRoot(routes, { scrollPositionRestoration: 'enabled', onSameUrlNavigation: 'reload' }) ],
    exports: [ RouterModule ],
    providers: [ ProfileResolver ]
})
export class AppRoutingModule { }
