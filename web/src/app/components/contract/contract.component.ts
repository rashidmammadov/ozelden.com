import { Component, OnInit } from '@angular/core';
import { MetaService } from '../../services/meta/meta.service';

@Component({
    selector: 'app-contract',
    templateUrl: './contract.component.html',
    styleUrls: ['./contract.component.scss']
})
export class ContractComponent implements OnInit {

    constructor(private metaService: MetaService) {
        metaService.updateOgMetaTags('ozelden.com - Mesafeli Satış Sözleşmesi');
    }

    ngOnInit(): void {
    }

}
