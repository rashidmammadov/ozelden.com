import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TutorStudentsComponent } from './tutor-students.component';

describe('TutorStudentsComponent', () => {
  let component: TutorStudentsComponent;
  let fixture: ComponentFixture<TutorStudentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TutorStudentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TutorStudentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
