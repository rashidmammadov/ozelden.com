import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { UtilityService } from '../../../services/utility/utility.service';
import { InfoType } from '../../../interfaces/info-type';
import { StudentType } from '../../../interfaces/student-type';

@Component({
    selector: 'app-grid-list',
    templateUrl: './grid-list.component.html',
    styleUrls: ['./grid-list.component.scss']
})
export class GridListComponent implements OnChanges {

    @Input() public data: InfoType[] | StudentType[];
    @Input() public column: number = 2;
    @Input() public item: string;
    itemWidth: number = 100;
    gridView: StudentType[][] = [];

    constructor() { }

    ngOnChanges(changes: SimpleChanges): void {
        this.itemWidth = 100 / this.column;
        if (changes.data && changes.data.currentValue) {
            this.gridView = UtilityService.dataAsGridView(changes.data.currentValue, this.column);
        }
    }

}
