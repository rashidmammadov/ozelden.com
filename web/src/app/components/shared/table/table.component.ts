import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort } from '@angular/material/sort';

@Component({
    selector: 'app-table',
    templateUrl: './table.component.html',
    styleUrls: ['./table.component.scss']
})
export class TableComponent implements OnInit {

    @Input() public data;
    displayedColumns: string[] = ['lecture_area', 'lecture_theme', 'experience', 'price'];
    dataSource;

    @ViewChild(MatSort, {static: true}) sort: MatSort;

    constructor() { }

    ngOnInit(): void {
        this.dataSource = new MatTableDataSource(this.data);
        this.dataSource.sort = this.sort;
    }

}
