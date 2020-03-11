import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AskOfferDialogComponent } from './ask-offer-dialog.component';

describe('AskOfferDialogComponent', () => {
  let component: AskOfferDialogComponent;
  let fixture: ComponentFixture<AskOfferDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AskOfferDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AskOfferDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
