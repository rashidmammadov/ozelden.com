import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from "@angular/material/dialog";
import { OneSignalService } from '../../../services/one-signal/one-signal.service';
import { UtilityService } from '../../../services/utility/utility.service';
import { ToastService } from '../../../services/toast/toast.service';
import { IHttpResponse } from '../../../interfaces/i-http-response';
import { OneSignalType } from '../../../interfaces/one-signal-type';

@Component({
    selector: 'app-one-signal-dialog',
    templateUrl: './one-signal-dialog.component.html',
    styleUrls: ['./one-signal-dialog.component.scss']
})
export class OneSignalDialogComponent implements OnInit {

    private static dialog;
    private static service;
    private static oneSignal: OneSignalType = {} as OneSignalType;

    constructor(public dialogRef: MatDialogRef<OneSignalDialogComponent>,
                @Inject(MAT_DIALOG_DATA) public OneSignal, private oneSignalService: OneSignalService) {
        OneSignalDialogComponent.dialog = dialogRef;
        OneSignalDialogComponent.service = oneSignalService;
        OneSignalDialogComponent.oneSignal.device_type = navigator.userAgent;
    }

    ngOnInit(): void {
        // @ts-ignore
        let OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.isPushNotificationsEnabled(function(subscribing) {
                if (subscribing) {
                    OneSignalDialogComponent.dialog.close();
                }
            });

            OneSignal.on('subscriptionChange', function (isSubscribed) {
                if (isSubscribed) {
                    OneSignal.push(function () {
                        OneSignal.getUserId(function (userId) {
                            OneSignalDialogComponent.oneSignal.one_signal_device_id = userId;
                            OneSignalDialogComponent.addToSubscribers();
                        });
                    });
                }
            });
        });
    }

    private static addToSubscribers = async () => {
        const result = await OneSignalDialogComponent.service.add(OneSignalDialogComponent.oneSignal);
        UtilityService.handleResponseFromService(result, (response: IHttpResponse) => {
            ToastService.show(response.message);
            OneSignalDialogComponent.dialog.close();
        });
    }

}
