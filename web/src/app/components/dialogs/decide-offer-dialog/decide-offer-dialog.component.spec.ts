import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DecideOfferDialogComponent } from './decide-offer-dialog.component';

describe('DecideOfferDialogComponent', () => {
  let component: DecideOfferDialogComponent;
  let fixture: ComponentFixture<DecideOfferDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DecideOfferDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DecideOfferDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
