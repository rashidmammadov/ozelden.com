import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { InfoType } from '../../interfaces/info-type';

@Component({
    selector: 'app-info-cards-list',
    templateUrl: './info-cards-list.component.html',
    styleUrls: ['./info-cards-list.component.scss']
})
export class InfoCardsListComponent implements OnChanges {

    @Input() public data: InfoType[];
    @Input() public column: number = 2;
    itemWidth: number = 100;
    gridView: InfoType[][] = [];

    constructor() { }

    ngOnChanges(changes: SimpleChanges): void {
        this.itemWidth = 100 / this.column;
        if (changes.data && changes.data.currentValue) {
            this.gridView = this.dataAsGridView(changes.data.currentValue, this.column);
        }
    }

    private dataAsGridView(data, column: number) {
        let groups = [];
        if (data && data.length) {
            const groupCount = Math.ceil(data.length / column);
            let start = 0;
            let end = column;
            for (let i = 0; i < groupCount; i++) {
                let groupItems = data.slice(start, end);
                groups.push(groupItems);
                start += column;
                end += column;
            }
        }
        return groups;
    }

}
