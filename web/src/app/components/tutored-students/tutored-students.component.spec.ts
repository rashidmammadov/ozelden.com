import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TutoredStudentsComponent } from './tutored-students.component';

describe('TutoredStudentsComponent', () => {
  let component: TutoredStudentsComponent;
  let fixture: ComponentFixture<TutoredStudentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TutoredStudentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TutoredStudentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
