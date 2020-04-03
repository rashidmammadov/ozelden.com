import { Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges } from '@angular/core';
import { DATE_TIME } from '../../../constants/date-time.constant';

@Component({
    selector: 'app-select-date-as-list',
    templateUrl: './select-date-as-list.component.html',
    styleUrls: ['./select-date-as-list.component.scss']
})
export class SelectDateAsListComponent implements OnInit, OnChanges {

    @Input() public date: string | number | Date;
    @Output() public callback = new EventEmitter();
    public currentDate = new Date();
    public days: number[] = [];
    public months: number[] = [];
    public years: number[] = [];
    public selectedDay: number = 1;
    public selectedMonth: number = 1;
    public selectedYear: number = this.currentDate.getFullYear() - 18;
    public MONTHS_MAP = DATE_TIME.MONTHS_MAP;

    constructor() { }

    ngOnInit(): void {
        this.setSelectors();
        this.setGivenDateAsSelected();
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.date) {
            this.setGivenDateAsSelected();
        }
    }

    public changeSelectedDate() {
        const selectedDate = new Date(this.selectedYear, this.selectedMonth - 1, this.selectedDay);
        this.callback.emit(selectedDate.getTime().toString());
    }

    private setSelectors() {
        for (let i = 1; i <= 31; i++) this.days.push(i);
        for (let i = 1; i <= 12; i++) this.months.push(i);
        for (let i = this.currentDate.getFullYear(); i >= this.currentDate.getFullYear() - 70; i--) this.years.push(i);
    }

    private setGivenDateAsSelected() {
        if (this.date) {
            const date = new Date(Number(this.date));
            this.selectedDay = date.getDate();
            this.selectedMonth = date.getMonth() + 1;
            this.selectedYear = date.getFullYear();
        }
    }

}
