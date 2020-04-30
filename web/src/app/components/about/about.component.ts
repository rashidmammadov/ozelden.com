import { Component, OnInit } from '@angular/core';
import { MetaService } from '../../services/meta/meta.service';

@Component({
    selector: 'app-about',
    templateUrl: './about.component.html',
    styleUrls: ['./about.component.scss']
})
export class AboutComponent implements OnInit {

    constructor(private metaService: MetaService) {
        metaService.updateOgMetaTags('ozelden.com - Hakkımızda');
    }

    ngOnInit(): void {
    }

}
