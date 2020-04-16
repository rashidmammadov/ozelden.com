import { Component, Inject } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

@Component({
    selector: 'app-forgot-password-dialog',
    templateUrl: './forgot-password-dialog.component.html',
    styleUrls: ['./forgot-password-dialog.component.scss']
})
export class ForgotPasswordDialogComponent {

    forgotPasswordForm = new FormGroup({
        email: new FormControl('', [Validators.required, Validators.email])
    });

    constructor(public dialog: MatDialogRef<ForgotPasswordDialogComponent>, @Inject(MAT_DIALOG_DATA) public email: string) {
        this.forgotPasswordForm.controls.email.setValue(email);
    }

    submit() {
        if (this.forgotPasswordForm.valid) {
            this.dialog.close(this.email);
        }
    }

}
