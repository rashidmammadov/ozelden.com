import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddAnnouncementComponentBottomSheet } from './add-announcement-component-bottom-sheet.component';

describe('AddAnnouncementComponent', () => {
  let component: AddAnnouncementComponentBottomSheet;
  let fixture: ComponentFixture<AddAnnouncementComponentBottomSheet>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddAnnouncementComponentBottomSheet ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddAnnouncementComponentBottomSheet);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
