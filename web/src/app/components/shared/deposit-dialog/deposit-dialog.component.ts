import {Component, Inject} from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import { REGEX } from '../../../constants/regex.constant';

@Component({
    selector: 'app-deposit-dialog',
    templateUrl: './deposit-dialog.component.html',
    styleUrls: ['./deposit-dialog.component.scss']
})
export class DepositDialogComponent {

    availableYears: number[] = [];
    private currentDate = new Date();

    depositForm = new FormGroup({
        price: new FormControl('', [Validators.required]),
        packages: new FormControl(''),
        card_number: new FormControl('', [Validators.required, Validators.pattern(REGEX.CARD_NUMBER)]),
        card_holder_name: new FormControl('', [Validators.required]),
        expire_month: new FormControl('', [Validators.required]),
        expire_year: new FormControl('', [Validators.required]),
        cvc: new FormControl('', [Validators.required])
    });

    constructor(public dialog: MatDialogRef<DepositDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public data: {price: number, packages: string[]}) {
        this.prepareDefaultValues();
    }

    deposit() {
        const controls = this.depositForm.controls;
        if (this.depositForm.valid) {
            this.dialog.close({
                price: controls.price.value,
                packages: controls.packages.value,
                card_number: controls.card_number.value,
                card_holder_name: controls.card_holder_name.value,
                expire_month: controls.expire_month.value,
                expire_year: controls.expire_year.value,
                cvc: controls.cvc.value
            });
        }
    }

    private prepareDefaultValues() {
        const minYear = Number(this.currentDate.getFullYear().toString().slice(2, 4));
        const currentMonth = this.currentDate.getMonth() + 1;
        for (let i = minYear; i <= minYear + 10; i++) { this.availableYears.push(i); }
        this.depositForm.controls.expire_month.setValue(currentMonth);
        this.depositForm.controls.expire_year.setValue(minYear);
        this.depositForm.controls.price.setValue(this.data.price);
        this.depositForm.controls.packages.setValue(this.data.packages);
        this.depositForm.controls.price.disable();
    }

}
