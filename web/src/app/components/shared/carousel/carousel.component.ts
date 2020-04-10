import { AfterViewInit, Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { InfoType } from '../../../interfaces/info-type';
import * as Hammer from 'ng-hammerjs';

@Component({
    selector: 'app-carousel',
    templateUrl: './carousel.component.html',
    styleUrls: ['./carousel.component.scss']
})
export class CarouselComponent implements AfterViewInit, OnChanges {

    @Input() public title: string;
    @Input() public data: InfoType[];
    items: InfoType[] = [];
    activeItem: InfoType;
    activeIndex: number;
    container;
    hammer;

    constructor() { }

    ngAfterViewInit(): void {
        this.container = document.querySelector('.carousel');
        this.hammer = new Hammer(this.container);
        this.hammer.on('swipe', (event) => {
            this.swipe(event);
        });
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes.data && changes.data.currentValue) {
            this.activeIndex = 0;
            this.items = this.data;
            this.changeItem(this.activeIndex);
        }
    }

    changeItem(index: number) {
        let classList = '';
        if ((this.activeIndex < index || (this.activeIndex === (this.items.length - 1) && index === 0))) {
            classList = 'animated fade-in-right';
        } else {
            classList = 'animated fade-in-left';
        }
        this.activeIndex = index;
        this.activeItem = this.items[this.activeIndex];
        setTimeout(() => {
            let item = document.querySelector(`.carousel #item-${this.activeIndex}`);
            if (item) {
                item.classList.value = classList;
            }
        });
    }

    private swipe(event) {
        setTimeout(() => {
            let activeIndex = this.activeIndex;
            const direction = Math.abs(event.deltaX) > 32 ? (event.deltaX > 0 ? 'right' : 'left') : '';
            if (direction === 'left') {
                document.querySelector(`.carousel #item-${activeIndex}`).classList.value = 'animated fade-out-left';
                activeIndex = (this.activeIndex === (this.items.length - 1)) ? 0 : this.activeIndex + 1;
            } else if (direction === 'right') {
                document.querySelector(`.carousel #item-${activeIndex}`).classList.value = 'animated fade-out-right';
                activeIndex = (this.activeIndex === 0) ? (this.items.length - 1) : this.activeIndex - 1;
            }
            this.changeItem(activeIndex);
            // document.querySelector(`#info-card-${cardId}`).classList.add('fade-in-right');
        });
    }

}
