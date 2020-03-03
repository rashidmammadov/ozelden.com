import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InfoCardsListComponent } from './info-cards-list.component';

describe('InfoCardsListComponent', () => {
  let component: InfoCardsListComponent;
  let fixture: ComponentFixture<InfoCardsListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InfoCardsListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InfoCardsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
