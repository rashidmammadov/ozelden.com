import { Component, Input, OnChanges, OnInit, SimpleChanges, ViewChild } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort } from '@angular/material/sort';
import { TableColumnType } from '../../../interfaces/table-column-type';

@Component({
    selector: 'app-table',
    templateUrl: './table.component.html',
    styleUrls: ['./table.component.scss']
})
export class TableComponent implements OnInit, OnChanges {

    @Input() public data;
    @Input() public columns: TableColumnType[];
    displayedColumns: string[] = [];
    dataSource;

    @ViewChild(MatSort, { static: true }) sort: MatSort;

    constructor() { }

    ngOnInit(): void {
        this.displayedColumns = [];
        this.columns.forEach(column => { this.displayedColumns.push(column.value); });
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.data) {
            this.dataSource = new MatTableDataSource(changes.data.currentValue);
            this.dataSource.sort = this.sort;
        }
    }

}
