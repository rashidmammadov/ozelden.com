import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OneSignalDialogComponent } from './one-signal-dialog.component';

describe('OneSignalDialogComponent', () => {
  let component: OneSignalDialogComponent;
  let fixture: ComponentFixture<OneSignalDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OneSignalDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OneSignalDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
