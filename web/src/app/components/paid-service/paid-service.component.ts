import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Store} from '@ngrx/store';
import { DepositDialogComponent } from '../dialogs/deposit-dialog/deposit-dialog.component';
import { ThreedsDialogComponent } from '../dialogs/threeds-dialog/threeds-dialog.component';
import { PaidService } from '../../services/paid/paid.service';
import { UtilityService } from '../../services/utility/utility.service';
import { IHttpResponse } from '../../interfaces/i-http-response';
import { PaidServiceType } from '../../interfaces/paid-service-type';
import { loaded, loading } from '../../store/actions/progress.action';
import { DATE_TIME } from '../../constants/date-time.constant';
import { PAID } from '../../constants/paid.constant';

@Component({
    selector: 'app-paid-service',
    templateUrl: './paid-service.component.html',
    styleUrls: ['./paid-service.component.scss']
})
export class PaidServiceComponent implements OnInit {

    public bid = {
        list: PAID.BID,
        checked: true,
        selected: PAID.BID[3].key
    };
    public boost = {
        list: PAID.BOOST,
        checked: true,
        selected: PAID.BOOST[3].key
    };
    public recommend = {
        list: PAID.RECOMMEND,
        checked: true,
        selected: PAID.RECOMMEND[2].key
    };
    totalPrice: number = 0;
    activePaidService: PaidServiceType;

    constructor(private dialog: MatDialog, private paidService: PaidService,
                private store: Store<{progress: boolean}>) { }

    ngOnInit(): void {
        this.getActivePaidServices();
        this.checkTotalPrice();
    }

    public checkTotalPrice() {
        this.totalPrice = 0;
        if (this.bid.checked) {
            this.totalPrice += this.bid.list.find((d) => d.key === this.bid.selected).price;
        }
        if (this.boost.checked) {
            this.totalPrice += this.boost.list.find((d) => d.key === this.boost.selected).price;
        }
        if (this.recommend.checked) {
            this.totalPrice += this.recommend.list.find((d) => d.key === this.recommend.selected).price;
        }
    }

    public openDepositDialog() {
        let packages = [];
        this.bid.checked && packages.push(this.bid.selected);
        this.boost.checked && packages.push(this.boost.selected);
        this.recommend.checked && packages.push(this.recommend.selected);
        const data = { price: this.totalPrice, packages: packages };
        if (packages.length) {
            this.dialog
                .open(DepositDialogComponent, {width: '500px', disableClose: true, data: data})
                .afterClosed().toPromise()
                .then(result => !!result && this.deposit(result));
        }
    }

    private getActivePaidServices = async () => {
        this.store.select(loading);
        const result = await this.paidService.get();
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.activePaidService = response.data;
            this.activePaidService.readableBoost =
                UtilityService.millisecondsToDate(this.activePaidService.boost, DATE_TIME.FORMAT.DATE);
            this.activePaidService.readableRecommend =
                UtilityService.millisecondsToDate(this.activePaidService.recommend, DATE_TIME.FORMAT.DATE);
        });
        this.store.select(loaded);
    };

    private deposit = async (params) => {
        this.store.select(loading);
        const result = await this.paidService.deposit(params);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            this.dialog
                .open(ThreedsDialogComponent, { width: '500px', disableClose: true, data: response.data })
                .afterClosed().toPromise().then(() => location.reload());
        });
        this.store.select(loaded);
    };

}
