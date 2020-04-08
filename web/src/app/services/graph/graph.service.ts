import { Injectable, Injector } from '@angular/core';
import { UtilityService } from '../utility/utility.service';
import { DATE_TIME } from '../../constants/date-time.constant';

@Injectable({
    providedIn: 'root'
})
export class GraphService {

    public static injector: Injector;

    public static prepareTooltipHTML(data) {
        let HTMLTags = [];
        UtilityService.isValid(data.gameImage) && HTMLTags.push(`<img src="${data.gameImage}"/>`);
        UtilityService.isValid(data.key) && HTMLTags.push(`<b>${data.key} ₺ aralığında</b>`);
        UtilityService.isValid(data.value) && HTMLTags.push(`Toplam <b>${data.value}</b> ders`);
        return HTMLTags.join('<br/>');
    }

    public static getTooltipXPosition(event) {
        let xPosition = event.layerX + 16;
        return `${xPosition}px`;
    }

    public static getTooltipYPosition(event) {
        let yPosition = event.layerY + 16;
        return `${yPosition}px`;
    }
}
