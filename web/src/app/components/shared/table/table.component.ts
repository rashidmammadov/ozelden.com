import {
  Component,
  Input,
  OnChanges,
  OnInit,
  Output,
  SimpleChanges,
  ViewChild,
  EventEmitter,
  HostListener
} from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort } from '@angular/material/sort';
import { PaginationType } from '../../../interfaces/pagination-type';
import { TableColumnType } from '../../../interfaces/table-column-type';

@Component({
    selector: 'app-table',
    templateUrl: './table.component.html',
    styleUrls: ['./table.component.scss']
})
export class TableComponent implements OnInit, OnChanges {

    @Input() public data;
    @Input() public columns: TableColumnType[];
    @Input() public pagination: PaginationType;
    @Output() public pageChanged = new EventEmitter<number>();
    displayedColumns: string[] = [];
    dataSource;
    width: number;

    // @ViewChild(MatSort, { static: true }) sort: MatSort;
    @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;

    constructor() { }

    ngOnInit(): void {
        this.width = this.getTableWidth();
        this.displayedColumns = [];
        this.columns.forEach(column => { this.displayedColumns.push(column.value); });
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.data) {
            this.dataSource = new MatTableDataSource(changes.data.currentValue);
            this.dataSource.paginator = this.paginator;
            // this.dataSource.sort = this.sort;
        }
    }

    @HostListener('window:resize', ['$event'])
    onResize() {
        this.width = this.getTableWidth();
    }

    changePage(page: number) {
        this.pageChanged.emit(page);
    }

    private getTableWidth(): number {
        return ((window.innerWidth >= 960 ? (window.innerWidth - 96) : window.innerWidth ) - 32);
    }

}
