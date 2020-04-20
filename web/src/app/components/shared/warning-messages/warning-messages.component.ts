import { Component, Input } from '@angular/core';
import { MESSAGES } from '../../../constants/messages.constant';

@Component({
    selector: 'app-warning-messages',
    templateUrl: './warning-messages.component.html',
    styleUrls: ['./warning-messages.component.scss']
})
export class WarningMessagesComponent {

    @Input() message: string;
    messages = MESSAGES.ERROR;

    constructor() { }

}
