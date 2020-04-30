import { Component, OnInit } from '@angular/core';
import { MetaService } from '../../services/meta/meta.service';

@Component({
    selector: 'app-products',
    templateUrl: './products.component.html',
    styleUrls: ['./products.component.scss']
})
export class ProductsComponent implements OnInit {

    constructor(private metaService: MetaService) {
        metaService.updateOgMetaTags('ozelden.com - Ürünler ve Hizmetler');
    }

    ngOnInit(): void {
    }

}
