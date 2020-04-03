import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SelectDateAsListComponent } from './select-date-as-list.component';

describe('SelectDateAsListComponent', () => {
  let component: SelectDateAsListComponent;
  let fixture: ComponentFixture<SelectDateAsListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SelectDateAsListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SelectDateAsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
