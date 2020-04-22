import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PdplComponent } from './pdpl.component';

describe('PdplComponent', () => {
  let component: PdplComponent;
  let fixture: ComponentFixture<PdplComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PdplComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PdplComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
