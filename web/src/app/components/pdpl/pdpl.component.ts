import { Component, OnInit } from '@angular/core';
import { MetaService } from '../../services/meta/meta.service';

@Component({
    selector: 'app-pdpl',
    templateUrl: './pdpl.component.html',
    styleUrls: ['./pdpl.component.scss']
})
export class PdplComponent implements OnInit {

    constructor(private metaService: MetaService) {
        metaService.updateOgMetaTags('ozelden.com - Gizlilik Kuralları');
    }

    ngOnInit(): void {
    }

}
